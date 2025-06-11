<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro - Biblioteca Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .register-container {
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
        <div class="register-container">
            <h1 class="text-center mb-4">Registro</h1>
            <div class="alert alert-danger" id="errorAlert" role="alert"></div>
            <div class="alert alert-success" id="successAlert" role="alert"></div>
            
            <form id="registerForm" class="register-form">
                <div class="mb-3">
                    <label for="name" class="form-label">Nome:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Senha:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Senha:</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Registrar</button>
                
                <div class="text-center mt-3">
                    <a href="/login">Já tem uma conta? Faça login</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;
            
            const errorAlert = document.getElementById('errorAlert');
            const successAlert = document.getElementById('successAlert');
            
            // Hide alerts
            errorAlert.style.display = 'none';
            successAlert.style.display = 'none';
            
            // Basic validation
            if (password !== password_confirmation) {
                errorAlert.style.display = 'block';
                errorAlert.textContent = 'As senhas não coincidem.';
                return;
            }
            
            try {
                const response = await axios.post('/api/usuarios/registrar', {
                    name: name,
                    email: email,
                    password: password,
                    password_confirmation: password_confirmation
                });
                
                if (response.data.status) {
                    successAlert.style.display = 'block';
                    successAlert.textContent = 'Registro realizado com sucesso! Redirecionando para o login...';
                    
                    // Clear form
                    this.reset();
                    
                    // Redirect to login after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                }
            } catch (error) {
                errorAlert.style.display = 'block';
                errorAlert.textContent = error.response?.data?.message || 'Erro ao registrar. Tente novamente.';
            }
        });
    </script>
</body>
</html> 