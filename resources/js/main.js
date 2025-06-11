import { carregaJson } from './livros/pega-json.js';
import { renderizaGeral } from './livros/carrega-livros.js';

document.addEventListener('DOMContentLoaded', async () => {
    // Verifica se é funcionário para adicionar menu extra
    const usuarioData = localStorage.getItem('usuarioLogado');
    
    if (usuarioData) {
        const usuario = JSON.parse(usuarioData);
        if (usuario.tipo === 'funcionario') {
            const sidebar = document.getElementById('sidebarMenu');
            const novoItem = document.createElement('li');
            novoItem.innerHTML = '<a href="cadastro-livro.html">Cadastrar Livro</a>';
            sidebar.appendChild(novoItem);
        }
    }
    
    // Carrega e exibe os livros
    try {
        const livros = await carregaJson();
        renderizaGeral(livros);
    } catch (error) {
        console.error('Erro ao carregar livros:', error);
        document.getElementById('livrosContainer').innerHTML = `
            <p class="sem">Erro ao carregar os livros. Por favor, tente novamente.</p>
        `;
    }
});