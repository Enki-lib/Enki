import { validateField, validateGenero, validateISBN } from './verificador-campos.js';

export function initValidation() {
    const form = document.querySelector('.form-livro');
    if (form) {
        form.addEventListener('submit', validate);
    }
}

function validate(e) {
    e.preventDefault();
    let isValid = true;

    isValid = validateField('titulo', 'Campo obrigatório', 100) && isValid;
    isValid = validateField('edicao', 'Campo obrigatório', 100) && isValid;
    isValid = validateField('data-publicacao', 'Data inválida') && isValid;
    isValid = validateGenero('genero', 'Deve ser um gênero cadastrado') && isValid;
    isValid = validateField('descricao', 'Campo obrigatório', 100) && isValid;
    isValid = validateISBN('isbn', 'Máximo 13 dígitos numéricos') && isValid;

    if (isValid) {
        //Enviar os dados para o banco caso esteja tudo certo
        e.target.reset();
        alert('Livro cadastrado com sucesso!');
        window.location.href = 'index.html';
    }
}