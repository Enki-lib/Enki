import { carregaJson } from './pega-json.js';

window.addEventListener('load', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const isbn = urlParams.get('isbn');
    const conteudo = document.getElementById('conteudo');

    if (!isbn) {
        conteudo.innerHTML = '<p class="sem">ISBN não fornecido.</p>';
        return;
    }

    try {
        const livros = await carregaJson();
        const livro = livros.find(l => l.isbn === isbn);

        if (!livro) {
            conteudo.innerHTML = '<p class="sem">Livro não encontrado.</p>';
            return;
        }

        conteudo.innerHTML = `
            <div class="detalhes-livro">
                <h1>${livro.titulo}</h1>
                <div class="info-livro">
                    <p><label>Edição:</label> ${livro.edicao}</p>
                    <p><label>Data de Publicação:</label> ${new Date(livro.data_publicacao).toLocaleDateString('pt-BR')}</p>
                    <p><label>Gênero:</label> ${livro.genero}</p>
                    <p><label>ISBN:</label> ${livro.isbn}</p>
                </div>
                <div class="descricao">
                    <h2>Descrição</h2>
                    <p>${livro.descricao}</p>
                </div>
                <a href="index.html" class="btn-voltar">Voltar</a>
            </div>
        `;
    } catch (error) {
        console.error('Erro:', error);
        conteudo.innerHTML = '<p class="sem">Erro ao carregar detalhes do livro.</p>';
    }
});