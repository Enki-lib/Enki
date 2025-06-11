<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Biblioteca Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .book-card {
            height: 100%;
        }
        .alert {
            display: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Biblioteca Virtual</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">Livros</a>
                    </li>
                    <li class="nav-item" id="adminMenu" style="display: none;">
                        <a class="nav-link" href="/cadastro-livro">Cadastrar Livro</a>
                    </li>
                </ul>
                <div class="d-flex" id="loginArea">
                    <a href="/login" class="btn btn-outline-light" id="btnLogin">Login</a>
                    <button class="btn btn-outline-light" onclick="logout()" id="btnLogout" style="display: none;">Sair</button>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="alert alert-danger" id="errorAlert" role="alert"></div>
        
        <div class="row mb-4">
            <div class="col">
                <h1>Catálogo de Livros</h1>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Buscar livros...">
                    <button class="btn btn-outline-secondary" type="button" id="searchButton">Buscar</button>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4" id="livrosContainer">
            <!-- Livros serão carregados aqui -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Check authentication
        function checkAuth() {
            const token = localStorage.getItem('token');
            const btnLogin = document.getElementById('btnLogin');
            const btnLogout = document.getElementById('btnLogout');
            const adminMenu = document.getElementById('adminMenu');
            
            if (token) {
                btnLogin.style.display = 'none';
                btnLogout.style.display = 'block';
                // You might want to check user role here to show/hide admin menu
                adminMenu.style.display = 'block';
            } else {
                btnLogin.style.display = 'block';
                btnLogout.style.display = 'none';
                adminMenu.style.display = 'none';
            }
            
            return token;
        }

        // Logout function
        function logout() {
            localStorage.removeItem('token');
            window.location.reload();
        }

        // Format date
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('pt-BR', options);
        }

        // Create book card
        function createBookCard(book) {
            return `
                <div class="col">
                    <div class="card book-card">
                        <div class="card-body">
                            <h5 class="card-title">${book.titulo}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Edição: ${book.edicao}</h6>
                            <p class="card-text">${book.descricao}</p>
                            <p class="card-text"><small class="text-muted">Publicado em: ${formatDate(book.data_publicacao)}</small></p>
                            <div class="d-grid gap-2">
                                <a href="/livro-detalhes?id=${book.id}" class="btn btn-primary">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Load books
        async function loadBooks(searchTerm = '') {
            const token = checkAuth();
            const errorAlert = document.getElementById('errorAlert');
            const container = document.getElementById('livrosContainer');
            
            try {
                let url = '/api/livros';
                if (searchTerm) {
                    url += `?search=${encodeURIComponent(searchTerm)}`;
                }
                
                const response = await axios.get(url, {
                    headers: token ? {
                        'Authorization': `Bearer ${token}`
                    } : {}
                });
                
                const books = response.data.data;
                
                if (books.length === 0) {
                    container.innerHTML = '<div class="col-12"><p class="text-center">Nenhum livro encontrado.</p></div>';
                    return;
                }
                
                container.innerHTML = books.map(book => createBookCard(book)).join('');
                
            } catch (error) {
                errorAlert.style.display = 'block';
                errorAlert.textContent = error.response?.data?.message || 'Erro ao carregar livros.';
            }
        }

        // Search functionality
        document.getElementById('searchButton').addEventListener('click', function() {
            const searchTerm = document.getElementById('searchInput').value;
            loadBooks(searchTerm);
        });

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value;
                loadBooks(searchTerm);
            }
        });

        // Initial load
        loadBooks();
    </script>
</body>
</html> 