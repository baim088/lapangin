/**
 * Lightweight zero-dependency ISO/IEC 18004 QR Code SVG generator (Byte mode, ECC Level M).
 */
const QRMath = (() => {
    const EXP_TABLE = new Array(256);
    const LOG_TABLE = new Array(256);
    for (let i = 0; i < 8; i++) EXP_TABLE[i] = 1 << i;
    for (let i = 8; i < 256; i++) {
        EXP_TABLE[i] = EXP_TABLE[i - 4] ^ EXP_TABLE[i - 5] ^ EXP_TABLE[i - 6] ^ EXP_TABLE[i - 8];
    }
    for (let i = 0; i < 255; i++) LOG_TABLE[EXP_TABLE[i]] = i;

    return {
        glog(n) {
            if (n < 1) throw new Error(`glog(${n})`);
            return LOG_TABLE[n];
        },
        gexp(n) {
            while (n < 0) n += 255;
            while (n >= 256) n -= 255;
            return EXP_TABLE[n];
        },
    };
})();

class QRPolynomial {
    constructor(num, shift) {
        let offset = 0;
        while (offset < num.length && num[offset] === 0) offset++;
        this.num = new Array(num.length - offset + shift);
        for (let i = 0; i < num.length - offset; i++) this.num[i] = num[i + offset];
        for (let i = num.length - offset; i < this.num.length; i++) this.num[i] = 0;
    }
    get(index) { return this.num[index]; }
    getLength() { return this.num.length; }
    multiply(e) {
        const num = new Array(this.getLength() + e.getLength() - 1).fill(0);
        for (let i = 0; i < this.getLength(); i++) {
            for (let j = 0; j < e.getLength(); j++) {
                num[i + j] ^= QRMath.gexp(QRMath.glog(this.get(i)) + QRMath.glog(e.get(j)));
            }
        }
        return new QRPolynomial(num, 0);
    }
    mod(e) {
        if (this.getLength() - e.getLength() < 0) return this;
        const ratio = QRMath.glog(this.get(0)) - QRMath.glog(e.get(0));
        const num = this.num.slice();
        for (let i = 0; i < e.getLength(); i++) {
            num[i] ^= QRMath.gexp(QRMath.glog(e.get(i)) + ratio);
        }
        return new QRPolynomial(num, 0).mod(e);
    }
}

class QRBitBuffer {
    constructor() {
        this.buffer = [];
        this.length = 0;
    }
    put(num, length) {
        for (let i = 0; i < length; i++) {
            this.putBit(((num >>> (length - i - 1)) & 1) === 1);
        }
    }
    putBit(bit) {
        const bufIndex = Math.floor(this.length / 8);
        if (this.buffer.length <= bufIndex) this.buffer.push(0);
        if (bit) this.buffer[bufIndex] |= 0x80 >>> (this.length % 8);
        this.length++;
    }
}

// RS block tables for ECC Level M (1..10)
const RS_BLOCK_TABLE_M = {
    1: [[1, 26, 16]],
    2: [[1, 44, 28]],
    3: [[1, 70, 44]],
    4: [[2, 50, 32]],
    5: [[2, 67, 43]],
    6: [[4, 43, 27]],
    7: [[4, 49, 31]],
    8: [[2, 60, 38], [2, 61, 39]],
    9: [[3, 58, 36], [2, 59, 37]],
    10: [[4, 69, 43], [1, 70, 44]],
};

const ALIGNMENT_PATTERNS = {
    1: [],
    2: [6, 18],
    3: [6, 22],
    4: [6, 26],
    5: [6, 30],
    6: [6, 34],
    7: [6, 22, 38],
    8: [6, 24, 42],
    9: [6, 26, 46],
    10: [6, 28, 50],
};

function getBCHTypeInfo(data) {
    let d = data << 10;
    while (getBCHDigit(d) - getBCHDigit(0b10100110111) >= 0) {
        d ^= 0b10100110111 << (getBCHDigit(d) - getBCHDigit(0b10100110111));
    }
    return ((data << 10) | d) ^ 0b101010000010010;
}

function getBCHTypeNumber(data) {
    let d = data << 12;
    while (getBCHDigit(d) - getBCHDigit(0b1111100100101) >= 0) {
        d ^= 0b1111100100101 << (getBCHDigit(d) - getBCHDigit(0b1111100100101));
    }
    return (data << 12) | d;
}

function getBCHDigit(data) {
    let digit = 0;
    while (data !== 0) {
        digit++;
        data >>>= 1;
    }
    return digit;
}

function getRSBlocks(typeNumber) {
    const spec = RS_BLOCK_TABLE_M[typeNumber];
    const list = [];
    for (const [count, totalCount, dataCount] of spec) {
        for (let i = 0; i < count; i++) list.push({ totalCount, dataCount });
    }
    return list;
}

function getErrorCorrectPolynomial(ecLength) {
    let a = new QRPolynomial([1], 0);
    for (let i = 0; i < ecLength; i++) {
        a = a.multiply(new QRPolynomial([1, QRMath.gexp(i)], 0));
    }
    return a;
}

function createData(typeNumber, bytes) {
    const rsBlocks = getRSBlocks(typeNumber);
    const buffer = new QRBitBuffer();
    buffer.put(4, 4); // 8-bit byte mode
    buffer.put(bytes.length, typeNumber < 10 ? 8 : 16);
    for (const b of bytes) buffer.put(b, 8);

    const totalDataCount = rsBlocks.reduce((s, r) => s + r.dataCount, 0);
    if (buffer.length + 4 <= totalDataCount * 8) buffer.put(0, 4);
    while (buffer.length % 8 !== 0) buffer.putBit(false);
    while (buffer.length < totalDataCount * 8) {
        buffer.put(0xEC, 8);
        if (buffer.length >= totalDataCount * 8) break;
        buffer.put(0x11, 8);
    }

    let offset = 0;
    let maxDcCount = 0;
    let maxEcCount = 0;
    const dcdata = [];
    const ecdata = [];

    for (let r = 0; r < rsBlocks.length; r++) {
        const dcCount = rsBlocks[r].dataCount;
        const ecCount = rsBlocks[r].totalCount - dcCount;
        maxDcCount = Math.max(maxDcCount, dcCount);
        maxEcCount = Math.max(maxEcCount, ecCount);
        const dc = buffer.buffer.slice(offset, offset + dcCount);
        offset += dcCount;
        dcdata.push(dc);

        const rsPoly = getErrorCorrectPolynomial(ecCount);
        const rawPoly = new QRPolynomial(dc, rsPoly.getLength() - 1);
        const modPoly = rawPoly.mod(rsPoly);
        const ec = new Array(rsPoly.getLength() - 1);
        for (let i = 0; i < ec.length; i++) {
            const modIndex = i + modPoly.getLength() - ec.length;
            ec[i] = modIndex >= 0 ? modPoly.get(modIndex) : 0;
        }
        ecdata.push(ec);
    }

    const data = [];
    for (let i = 0; i < maxDcCount; i++) {
        for (let r = 0; r < rsBlocks.length; r++) {
            if (i < dcdata[r].length) data.push(dcdata[r][i]);
        }
    }
    for (let i = 0; i < maxEcCount; i++) {
        for (let r = 0; r < rsBlocks.length; r++) {
            if (i < ecdata[r].length) data.push(ecdata[r][i]);
        }
    }
    return data;
}

export function buildQrMatrix(text) {
    const bytes = Array.from(new TextEncoder().encode(text));
    let typeNumber = 1;
    for (; typeNumber <= 10; typeNumber++) {
        const rsBlocks = getRSBlocks(typeNumber);
        const totalDataBytes = rsBlocks.reduce((s, r) => s + r.dataCount, 0);
        const headerBytes = Math.ceil((4 + (typeNumber < 10 ? 8 : 16)) / 8);
        if (bytes.length + headerBytes <= totalDataBytes) break;
    }
    if (typeNumber > 10) typeNumber = 10;

    const moduleCount = typeNumber * 4 + 17;
    const modules = Array.from({ length: moduleCount }, () => new Array(moduleCount).fill(null));

    const setupPositionProbe = (row, col) => {
        for (let r = -1; r <= 7; r++) {
            if (row + r <= -1 || moduleCount <= row + r) continue;
            for (let c = -1; c <= 7; c++) {
                if (col + c <= -1 || moduleCount <= col + c) continue;
                const isDark = (0 <= r && r <= 6 && (c === 0 || c === 6))
                    || (0 <= c && c <= 6 && (r === 0 || r === 6))
                    || (2 <= r && r <= 4 && 2 <= c && c <= 4);
                modules[row + r][col + c] = isDark;
            }
        }
    };

    setupPositionProbe(0, 0);
    setupPositionProbe(moduleCount - 7, 0);
    setupPositionProbe(0, moduleCount - 7);

    // Alignment patterns
    const pos = ALIGNMENT_PATTERNS[typeNumber];
    for (let i = 0; i < pos.length; i++) {
        for (let j = 0; j < pos.length; j++) {
            const row = pos[i];
            const col = pos[j];
            if (modules[row][col] !== null) continue;
            for (let r = -2; r <= 2; r++) {
                for (let c = -2; c <= 2; c++) {
                    modules[row + r][col + c] = r === -2 || r === 2 || c === -2 || c === 2 || (r === 0 && c === 0);
                }
            }
        }
    }

    // Timing patterns
    for (let r = 8; r < moduleCount - 8; r++) {
        if (modules[r][6] === null) modules[r][6] = r % 2 === 0;
    }
    for (let c = 8; c < moduleCount - 8; c++) {
        if (modules[6][c] === null) modules[6][c] = c % 2 === 0;
    }

    // Type info reservation (ECC M = 0b00, mask = 0 -> 0b00000)
    const formatBits = getBCHTypeInfo((0 << 3) | 0);
    for (let i = 0; i < 15; i++) {
        const mod = ((formatBits >> i) & 1) === 1;
        if (i < 6) modules[i][8] = mod;
        else if (i < 8) modules[i + 1][8] = mod;
        else modules[moduleCount - 15 + i][8] = mod;

        if (i < 8) modules[8][moduleCount - i - 1] = mod;
        else if (i < 9) modules[8][15 - i - 1 + 1] = mod;
        else modules[8][15 - i - 1] = mod;
    }
    modules[moduleCount - 8][8] = true;

    if (typeNumber >= 7) {
        const typeBits = getBCHTypeNumber(typeNumber);
        for (let i = 0; i < 18; i++) {
            const mod = ((typeBits >> i) & 1) === 1;
            modules[Math.floor(i / 3)][(i % 3) + moduleCount - 8 - 3] = mod;
            modules[(i % 3) + moduleCount - 8 - 3][Math.floor(i / 3)] = mod;
        }
    }

    // Map data with mask pattern 0: (row + col) % 2 === 0
    const data = createData(typeNumber, bytes);
    let inc = -1;
    let row = moduleCount - 1;
    let bitIndex = 7;
    let byteIndex = 0;

    for (let col = moduleCount - 1; col > 0; col -= 2) {
        if (col === 6) col--;
        while (true) {
            for (let c = 0; c < 2; c++) {
                if (modules[row][col - c] === null) {
                    let dark = false;
                    if (byteIndex < data.length) {
                        dark = ((data[byteIndex] >>> bitIndex) & 1) === 1;
                    }
                    if ((row + (col - c)) % 2 === 0) dark = !dark;
                    modules[row][col - c] = dark;
                    bitIndex--;
                    if (bitIndex === -1) {
                        byteIndex++;
                        bitIndex = 7;
                    }
                }
            }
            row += inc;
            if (row < 0 || moduleCount <= row) {
                row -= inc;
                inc = -inc;
                break;
            }
        }
    }

    return modules;
}

export function renderQrSvg(text, size = 180) {
    const matrix = buildQrMatrix(text || '');
    const count = matrix.length;
    const margin = 2;
    const total = count + margin * 2;
    let rects = '';
    for (let r = 0; r < count; r++) {
        for (let c = 0; c < count; c++) {
            if (matrix[r][c]) {
                rects += `<rect x="${c + margin}" y="${r + margin}" width="1" height="1" fill="#0f2444"/>`;
            }
        }
    }
    return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${total} ${total}" width="${size}" height="${size}" shape-rendering="crispEdges"><rect width="${total}" height="${total}" fill="#ffffff"/>${rects}</svg>`;
}
