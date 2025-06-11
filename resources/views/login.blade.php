<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Biblioteca Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .alert {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h1 class="text-center mb-4">Login</h1>
            <div class="alert alert-danger" id="errorAlert" role="alert"></div>
            <form id="loginForm" class="login-form">
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Senha:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Entrar</button>

                <div class="text-center mt-3">
                    <a href="/register">Não tem uma conta? Registre-se</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Set up axios defaults for CSRF
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorAlert = document.getElementById('errorAlert');
            
            try {
                const response = await axios.post('/api/login', {
                    email: email,
                    password: password
                });
                
                if (response.data.status) {
                    // Store the token
                    localStorage.setItem('token', response.data.token);
                    // Store user data if needed
                    localStorage.setItem('user', JSON.stringify(response.data.user));
                    // Redirect to the main page
                    window.location.href = '/';
                }
            } catch (error) {
                errorAlert.style.display = 'block';
                errorAlert.textContent = error.response?.data?.message || 'Erro ao fazer login. Tente novamente.';
                console.error('Login error:', error.response?.data); // Add this for debugging
            }
        });
    </script>
</body>
</html> 