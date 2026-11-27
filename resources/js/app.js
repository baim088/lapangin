import Alpine from 'alpinejs';
import { renderQrSvg } from './qrcode';

window.Alpine = Alpine;
window.renderQrSvg = renderQrSvg;

Alpine.start();
