export function setupDate() {
    const dataAtual = document.getElementById('data-publicacao');
    if (!dataAtual) return;
    
    // Define a data máxima como hoje
    dataAtual.max = new Date().toISOString().split('T')[0];
    
    dataAtual.addEventListener('change', () => {
        const selectedDate = new Date(dataAtual.value);
        const today = new Date();
        const isValid = selectedDate <= today;
        
        showError('data-publicacao', isValid ? '' : 'Data não pode ser futura');
    });
}

function showError(id, message) {
    const field = document.getElementById(id);
    if (!field) return;
    
    field.classList.toggle('input-error', message !== '');
    const errorElement = document.getElementById(`${id}-error`);
    
    if (errorElement) {
        errorElement.textContent = message;
    } else if (message) {
        const errorElement = document.createElement('div');
        errorElement.id = `${id}-error`;
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        field.parentNode.appendChild(errorElement);
    }
}