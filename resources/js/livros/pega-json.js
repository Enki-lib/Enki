export async function carregaJson() {
    try {
        const response = await fetch('../data/livros.json');
        if (!response.ok) throw new Error(`Erro HTTP! status: ${response.status}`);
        const data = await response.json();
        return data.livros;
    } catch (error) {
        console.error('Erro ao carregar JSON:', error);
        return [];
    }
}