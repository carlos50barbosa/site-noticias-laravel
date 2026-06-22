import Alpine from 'alpinejs';
import { initEditors, initImageFields } from './editor';

window.Alpine = Alpine;
Alpine.start();

function boot() {
    initEditors();
    initImageFields();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
