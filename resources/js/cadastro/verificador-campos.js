export function validateField(id, errorMsg, maxLength = null) {
    const field = document.getElementById(id);
    if (!field) return false;
    
    let valid = field.value.trim() !== '';
    
    if (valid && maxLength !== null) {
        valid = field.value.length <= maxLength;
        errorMsg = valid ? errorMsg : `Máximo de ${maxLength} caracteres`;
    }
    
    showError(id, valid ? '' : errorMsg);
    return valid;
}

export function validateGenero(id, errorMsg) {
    const field = document.getElementById(id);
    if (!field) return false;
    
    const value = field.value.trim();
    const valid = value !== '';
    showError(id, valid ? '' : errorMsg);
    return valid;
}

export function validateISBN(id, errorMsg) {
    const field = document.getElementById(id);
    if (!field) return false;
    
    const value = field.value.trim();
    const valid = /^\d{10,13}$/.test(value); // Aceita ISBN de 10 ou 13 dígitos
    showError(id, valid ? '' : errorMsg);
    return valid;
}

export function showError(id, message) {
    const field = document.getElementById(id);
    if (!field) return;
    
    // Remove erro existente
    let errorElement = document.getElementById(`${id}-error`);
    
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.id = `${id}-error`;
        errorElement.className = 'error-message';
        field.parentNode.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
    field.classList.toggle('input-error', message !== '');
}