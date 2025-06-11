window.addEventListener('load', () => {
    const loginArea = document.getElementById('loginArea');
    if (!loginArea) return;
    
    const usuarioData = localStorage.getItem('usuarioLogado');
    
    if (usuarioData) {
        const usuario = JSON.parse(usuarioData);
        loginArea.innerHTML = `
            <span>Olá, ${usuario.tipo === 'funcionario' ? 'Funcionário' : 'Cliente'}</span>
            <a href="#" id="logout" style="margin-left: 10px;">(Sair)</a>
        `;
        
        document.getElementById('logout').addEventListener('click', () => {
            localStorage.removeItem('usuarioLogado');
            window.location.reload();
        });
    } else {
        loginArea.innerHTML = '<a href="login.html" class="btn-login">Login</a>';
    }
});