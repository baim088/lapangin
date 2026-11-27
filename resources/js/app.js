import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.renderQrSvg = async (text, size = 148) => {
    const { renderQrSvg } = await import('./qrcode');
    return renderQrSvg(text, size);
};

Alpine.start();
