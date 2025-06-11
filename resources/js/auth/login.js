document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    // Usuários pré-definidos
    const usuarios = {
        func: { senha: '123', tipo: 'funcionario' },
        cliente: { senha: '345', tipo: 'cliente' }
    };
    
    if (usuarios[username] && usuarios[username].senha === password) {
        // Salva o tipo de usuário no localStorage
        localStorage.setItem('usuarioLogado', JSON.stringify({
            username: username,
            tipo: usuarios[username].tipo
        }));
        
        // Redireciona para a página inicial
        window.location.href = 'index.html';
    } else {
        alert('Usuário ou senha incorretos!');
    }
});