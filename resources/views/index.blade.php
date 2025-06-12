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
            display: flex;
            flex-direction: column;
        }
        .book-card .card-img-top {
            height: 250px;
            object-fit: contain;
            background-color: #f8f9fa;
            padding: 10px;
        }
        .book-card .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .book-card .card-text {
            flex-grow: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
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
                        <div class="img-wrapper">
                            <img src="${book.coverUrl || '/images/no-cover.png'}" class="card-img-top" alt="${book.titulo_livro}">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-truncate" title="${book.titulo_livro}">${book.titulo_livro}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Edição: ${book.edicao_livro}</h6>
                            <p class="card-text">${book.assunto}</p>
                            <p class="card-text"><small class="text-muted">Publicado em: ${formatDate(book.ano_publicacao)}</small></p>
                            <div class="d-grid gap-2 mt-auto">
                                <a href="/livro-detalhes?id=${book.codigo_livro}" class="btn btn-primary">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Fetch book cover from Google Books API
        async function fetchBookCover(isbn) {
            try {
                const response = await axios.get(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`);
                if (response.data.items && response.data.items[0]?.volumeInfo?.imageLinks?.thumbnail) {
                    return response.data.items[0].volumeInfo.imageLinks.thumbnail;
                }
                return null;
            } catch (error) {
                console.error('Error fetching book cover:', error);
                return null;
            }
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
                
                console.log('Fetching books from:', url);
                const response = await axios.get(url, {
                    headers: token ? {
                        'Authorization': `Bearer ${token}`
                    } : {}
                });
                
                console.log('API Response:', response.data);
                
                if (!response.data.status) {
                    throw new Error('API returned status false');
                }
                
                const books = response.data.data;
                
                if (!Array.isArray(books)) {
                    throw new Error('Books data is not an array');
                }
                
                if (books.length === 0) {
                    container.innerHTML = '<div class="col-12"><p class="text-center">Nenhum livro encontrado.</p></div>';
                    return;
                }

                // Fetch book covers for all books
                const booksWithCovers = await Promise.all(books.map(async (book) => {
                    const coverUrl = await fetchBookCover(book.ISBN);
                    return { ...book, coverUrl };
                }));
                
                container.innerHTML = booksWithCovers.map(book => createBookCard(book)).join('');
                
            } catch (error) {
                console.error('Error loading books:', error);
                console.error('Error details:', error.response?.data);
                errorAlert.style.display = 'block';
                errorAlert.textContent = error.response?.data?.message || error.message || 'Erro ao carregar livros.';
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