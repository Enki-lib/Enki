export function renderizaGeral(livros) {
    const container = document.getElementById('livrosContainer');
    
    if (!livros || livros.length === 0) {
        container.innerHTML = '<p class="sem">Nenhum livro encontrado.</p>';
        return;
    }

    container.innerHTML = `
        <div class="cards-view">
            ${livros.map(livro => `
                <div class="card" data-isbn="${livro.isbn}">
                    <h3>${livro.titulo}</h3>
                    <p>${livro.descricao}</p>
                    <p><strong>Gênero:</strong> ${livro.genero}</p>
                </div>
            `).join('')}
        </div>
    `;

    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('click', () => {
            const isbn = card.getAttribute('data-isbn');
            window.location.href = `livro-detalhes.html?isbn=${isbn}`;
        });
    });
}