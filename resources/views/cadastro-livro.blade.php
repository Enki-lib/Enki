<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cadastro de Livros - Biblioteca Virtual</title>
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
        <h1 class="mb-4">Cadastro de Livros</h1>
        
        <div class="alert alert-success" id="successAlert" role="alert"></div>
        <div class="alert alert-danger" id="errorAlert" role="alert"></div>
        
        <form id="bookForm" class="form-livro">
            <div class="mb-3">
                <label for="id_categoria" class="form-label">Categoria:</label>
                <select id="id_categoria" name="id_categoria" class="form-select" required>
                    <option value="">Selecione uma categoria</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="titulo_livro" class="form-label">Título do Livro:</label>
                <input type="text" id="titulo_livro" name="titulo_livro" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="edicao_livro" class="form-label">Edição:</label>
                <input type="text" id="edicao_livro" name="edicao_livro" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="ano_publicacao" class="form-label">Data de Publicação:</label>
                <input type="date" id="ano_publicacao" name="ano_publicacao" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="assunto" class="form-label">Assunto:</label>
                <textarea id="assunto" name="assunto" class="form-control" rows="3" required></textarea>
            </div>
            
            <div class="mb-3">
                <label for="ISBN" class="form-label">ISBN:</label>
                <input type="text" id="ISBN" name="ISBN" class="form-control" required
                       pattern="[0-9]{13}"
                       title="O ISBN deve conter 13 dígitos numéricos">
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Cadastrar Livro</button>
                <a href="/" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
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

        // Load categories
        async function loadCategories() {
            try {
                const token = checkAuth();
                const response = await axios.get('/api/categorias', {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                if (response.data.status) {
                    const select = document.getElementById('id_categoria');
                    response.data.categorias.forEach(categoria => {
                        const option = document.createElement('option');
                        option.value = categoria.id_categoria;
                        option.textContent = categoria.nome_categoria;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading categories:', error);
                const errorAlert = document.getElementById('errorAlert');
                errorAlert.style.display = 'block';
                errorAlert.textContent = 'Erro ao carregar categorias. Por favor, recarregue a página.';
            }
        }

        // Call checkAuth and loadCategories on page load
        checkAuth();
        loadCategories();

        document.getElementById('bookForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            
            const formData = {
                id_categoria: parseInt(document.getElementById('id_categoria').value),
                titulo_livro: document.getElementById('titulo_livro').value,
                edicao_livro: document.getElementById('edicao_livro').value,
                ano_publicacao: document.getElementById('ano_publicacao').value,
                assunto: document.getElementById('assunto').value,
                ISBN: document.getElementById('ISBN').value
            };
            
            try {
                const response = await axios.post('/api/livros/registro', formData, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });
                
                if (response.data.status) {
                    successAlert.style.display = 'block';
                    successAlert.textContent = 'Livro cadastrado com sucesso!';
                    errorAlert.style.display = 'none';
                    
                    // Clear form
                    this.reset();
                    
                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 2000);
                }
            } catch (error) {
                errorAlert.style.display = 'block';
                errorAlert.textContent = error.response?.data?.message || 'Erro ao cadastrar livro. Tente novamente.';
                successAlert.style.display = 'none';
                console.error('Error details:', error.response?.data);
            }
        });
    </script>
</body>
</html> 