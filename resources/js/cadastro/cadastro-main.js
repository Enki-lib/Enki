import { initValidation } from './verificador-formulario.js';
import { setupDate } from './seta-data.js';

window.addEventListener('load', () => {
    setupDate();
    initValidation();
});