<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalhes do Livro - Biblioteca Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
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
            <div class="d-flex" id="loginArea">
                <button class="btn btn-outline-light" onclick="logout()">Sair</button>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="alert alert-danger" id="errorAlert" role="alert"></div>
        
        <div id="bookDetails" class="card">
            <div class="card-body">
                <h1 class="card-title mb-4" id="titulo"></h1>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Edição:</strong> <span id="edicao"></span></p>
                        <p><strong>Data de Publicação:</strong> <span id="data_publicacao"></span></p>
                        <p><strong>Gênero:</strong> <span id="genero"></span></p>
                        <p><strong>ISBN:</strong> <span id="isbn"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Descrição:</strong></p>
                        <p id="descricao"></p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h3>Status do Empréstimo</h3>
                    <p id="statusEmprestimo"></p>
                    <button id="btnEmprestar" class="btn btn-success" style="display: none;">Solicitar Empréstimo</button>
                    <button id="btnDevolver" class="btn btn-warning" style="display: none;">Devolver Livro</button>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="/" class="btn btn-secondary">Voltar</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Check authentication
        function checkAuth() {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '/login';
            }
            return token;
        }

        // Logout function
        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }

        // Format date
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('pt-BR', options);
        }

        // Get book ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const bookId = urlParams.get('id');

        // Load book details
        async function loadBookDetails() {
            const token = checkAuth();
            const errorAlert = document.getElementById('errorAlert');
            
            try {
                const response = await axios.get(`/api/livros/${bookId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                const book = response.data.data;
                
                // Fill book details
                document.getElementById('titulo').textContent = book.titulo;
                document.getElementById('edicao').textContent = book.edicao;
                document.getElementById('data_publicacao').textContent = formatDate(book.data_publicacao);
                document.getElementById('genero').textContent = book.genero;
                document.getElementById('isbn').textContent = book.isbn;
                document.getElementById('descricao').textContent = book.descricao;
                
                // Check loan status
                const statusElement = document.getElementById('statusEmprestimo');
                const btnEmprestar = document.getElementById('btnEmprestar');
                const btnDevolver = document.getElementById('btnDevolver');
                
                if (book.emprestimo) {
                    statusElement.textContent = `Emprestado para: ${book.emprestimo.usuario_nome}`;
                    if (book.emprestimo.usuario_id === book.usuario_atual_id) {
                        btnDevolver.style.display = 'block';
                    }
                } else {
                    statusElement.textContent = 'Disponível para empréstimo';
                    btnEmprestar.style.display = 'block';
                }
                
            } catch (error) {
                errorAlert.style.display = 'block';
                errorAlert.textContent = error.response?.data?.message || 'Erro ao carregar detalhes do livro.';
            }
        }

        // Handle loan request
        document.getElementById('btnEmprestar').addEventListener('click', async function() {
            const token = checkAuth();
            const errorAlert = document.getElementById('errorAlert');
            
            try {
                await axios.post('/api/emprestimos/registro', {
                    livro_id: bookId
                }, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                // Reload page to show updated status
                location.reload();
                
            } catch (error) {
                errorAlert.style.display = 'block';
                errorAlert.textContent = error.response?.data?.message || 'Erro ao solicitar empréstimo.';
            }
        });

        // Handle return request
        document.getElementById('btnDevolver').addEventListener('click', async function() {
            const token = checkAuth();
            const errorAlert = document.getElementById('errorAlert');
            
            try {
                await axios.put(`/api/emprestimos/editar/${bookId}`, {
                    status: 'devolvido'
                }, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                // Reload page to show updated status
                location.reload();
                
            } catch (error) {
                errorAlert.style.display = 'block';
                errorAlert.textContent = error.response?.data?.message || 'Erro ao devolver livro.';
            }
        });

        // Load book details when page loads
        loadBookDetails();
    </script>
</body>
</html> 