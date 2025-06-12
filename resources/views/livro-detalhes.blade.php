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
            margin-bottom: 1rem;
        }
        .status-badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        .status-disponivel {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-emprestado {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .status-reservado {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .loan-info {
            background-color: #e9ecef;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
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
        <div class="alert alert-success" id="successAlert" role="alert"></div>
        <div class="alert alert-danger" id="errorAlert" role="alert"></div>
        
        <div id="bookDetails" class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img id="bookCover" src="" alt="Capa do livro" class="img-fluid mb-3" style="height: 250px; object-fit: contain;">
                    </div>
                    <div class="col-md-8">
                        <h1 class="card-title mb-4" id="titulo"></h1>
                        <div class="status-badge" id="statusBadge"></div>
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
                            <div id="loanInfo" class="loan-info" style="display: none;">
                                <p id="statusEmprestimo" class="mb-3"></p>
                                <p id="loanDates" class="mb-2"></p>
                                <p id="renewalInfo" class="mb-2"></p>
                                <p id="fineInfo" class="mb-2 text-danger"></p>
                            </div>
                            <div class="mt-3">
                                <button id="btnEmprestar" class="btn btn-success" style="display: none;">Solicitar Empréstimo</button>
                                <button id="btnRenovar" class="btn btn-info text-white" style="display: none;">Renovar Empréstimo</button>
                                <button id="btnDevolver" class="btn btn-warning" style="display: none;">Devolver Livro</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="/" class="btn btn-secondary" onclick="return true;">Voltar</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Global variables
        let currentBook = null;

        // Check authentication
        function checkAuth() {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '/login';
                return null;
            }
            return `Bearer ${token}`;
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

        // Format currency
        function formatCurrency(value) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(value);
        }

        // Get book ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const bookId = urlParams.get('id');

        function showError(message) {
            const errorAlert = document.getElementById('errorAlert');
            errorAlert.textContent = message;
            errorAlert.style.display = 'block';
            setTimeout(() => {
                errorAlert.style.display = 'none';
            }, 5000);
        }

        function showSuccess(message) {
            const successAlert = document.getElementById('successAlert');
            successAlert.textContent = message;
            successAlert.style.display = 'block';
            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 5000);
        }

        function updateStatusBadge(status) {
            const badge = document.getElementById('statusBadge');
            badge.className = 'status-badge';
            
            switch (status) {
                case 'Disponível':
                    badge.classList.add('status-disponivel');
                    badge.textContent = '✓ Disponível';
                    break;
                case 'Emprestado':
                    badge.classList.add('status-emprestado');
                    badge.textContent = '⚠ Emprestado';
                    break;
                case 'Reservado':
                    badge.classList.add('status-reservado');
                    badge.textContent = '⌛ Reservado';
                    break;
            }
        }

        // Load book details
        async function loadBookDetails() {
            if (!bookId) return; // Don't try to load if there's no ID

            const token = checkAuth();
            if (!token) return;
            
            try {
                const response = await axios.get(`/api/livros/${bookId}`, {
                    headers: {
                        'Authorization': token
                    }
                });
                
                currentBook = response.data.data;
                
                // Fill book details
                document.getElementById('titulo').textContent = currentBook.titulo_livro;
                document.getElementById('edicao').textContent = currentBook.edicao_livro;
                document.getElementById('data_publicacao').textContent = formatDate(currentBook.ano_publicacao);
                document.getElementById('genero').textContent = currentBook.categoria?.nome_categoria || 'Não especificado';
                document.getElementById('isbn').textContent = currentBook.ISBN;
                document.getElementById('descricao').textContent = currentBook.assunto;
                
                // Update status badge
                updateStatusBadge(currentBook.status);
                
                // Load book cover
                const coverImg = document.getElementById('bookCover');
                try {
                    const coverUrl = await fetchBookCover(currentBook.ISBN);
                    coverImg.src = coverUrl;
                } catch (error) {
                    coverImg.src = '/images/no-cover.png';
                }
                
                // Handle loan information
                const loanInfo = document.getElementById('loanInfo');
                const statusElement = document.getElementById('statusEmprestimo');
                const btnEmprestar = document.getElementById('btnEmprestar');
                const btnRenovar = document.getElementById('btnRenovar');
                const btnDevolver = document.getElementById('btnDevolver');
                
                if (currentBook.emprestimo) {
                    loanInfo.style.display = 'block';
                    statusElement.textContent = `Emprestado para: ${currentBook.emprestimo.usuario_nome}`;
                    
                    // Show loan dates
                    document.getElementById('loanDates').innerHTML = `
                        <strong>Data do Empréstimo:</strong> ${formatDate(currentBook.emprestimo.data_emprestimo)}<br>
                        <strong>Data de Devolução:</strong> ${formatDate(currentBook.emprestimo.data_devolucao)}
                    `;
                    
                    // Show renewal info
                    document.getElementById('renewalInfo').textContent = 
                        `Renovações realizadas: ${currentBook.emprestimo.num_renovacoes} de 2`;
                    
                    // Show fine if exists
                    if (currentBook.emprestimo.multa > 0) {
                        document.getElementById('fineInfo').textContent = 
                            `Multa pendente: ${formatCurrency(currentBook.emprestimo.multa)}`;
                    }
                    
                    if (currentBook.emprestimo.usuario_id === currentBook.usuario_atual_id) {
                        btnDevolver.style.display = 'inline-block';
                        if (currentBook.emprestimo.num_renovacoes < 2 && currentBook.emprestimo.multa <= 0) {
                            btnRenovar.style.display = 'inline-block';
                        }
                    }
                } else {
                    loanInfo.style.display = 'none';
                    statusElement.textContent = 'Disponível para empréstimo';
                    btnEmprestar.style.display = 'inline-block';
                }
            } catch (error) {
                if (error.response?.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                if (error.response?.status !== 404) {
                    showError(error.response?.data?.message || 'Erro ao carregar detalhes do livro.');
                }
            }
        }

        // Handle loan request
        document.getElementById('btnEmprestar').addEventListener('click', async function() {
            const token = checkAuth();
            if (!token) return;
            
            try {
                const response = await axios.post('/api/emprestimos/registro', {
                    livro_codigo_livro: bookId
                }, {
                    headers: {
                        'Authorization': token
                    }
                });
                
                showSuccess('Empréstimo realizado com sucesso!');
                await loadBookDetails();
                
            } catch (error) {
                if (error.response?.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                showError(error.response?.data?.message || 'Erro ao solicitar empréstimo.');
            }
        });

        // Handle renewal request
        document.getElementById('btnRenovar').addEventListener('click', async function() {
            const token = checkAuth();
            if (!token) return;
            
            try {
                const response = await axios.put(`/api/emprestimos/editar/${currentBook.emprestimo.id}`, {
                    action: 'renovar'
                }, {
                    headers: {
                        'Authorization': token
                    }
                });
                
                showSuccess('Empréstimo renovado com sucesso!');
                await loadBookDetails();
                
            } catch (error) {
                if (error.response?.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                showError(error.response?.data?.message || 'Erro ao renovar empréstimo.');
            }
        });

        // Handle return request
        document.getElementById('btnDevolver').addEventListener('click', async function() {
            const token = checkAuth();
            if (!token) return;
            
            try {
                if (!currentBook || !currentBook.emprestimo) {
                    showError('Informações do empréstimo não encontradas.');
                    return;
                }

                const response = await axios.put(`/api/emprestimos/editar/${currentBook.emprestimo.id}`, {
                    action: 'devolver'
                }, {
                    headers: {
                        'Authorization': token
                    }
                });
                
                showSuccess('Livro devolvido com sucesso!');
                await loadBookDetails();
                
            } catch (error) {
                if (error.response?.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                showError(error.response?.data?.message || 'Erro ao devolver livro.');
            }
        });

        async function fetchBookCover(isbn) {
            const response = await axios.get(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`);
            if (response.data.items && response.data.items[0]?.volumeInfo?.imageLinks?.thumbnail) {
                return response.data.items[0].volumeInfo.imageLinks.thumbnail;
            }
            throw new Error('Cover not found');
        }

        // Load book details when page loads
        if (bookId) { // Only load if we have a book ID
            loadBookDetails();
        }
    </script>
</body>
</html> 