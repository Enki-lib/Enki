<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro - Biblioteca Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Inputmask library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/inputmask.min.js"></script>
    <style>
        .register-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .alert {
            display: none;
            transition: opacity 0.5s ease-in-out;
        }
        .alert.fade-out {
            opacity: 0;
        }
        .form-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .form-section h4 {
            margin-bottom: 20px;
            color: #0d6efd;
            font-size: 1.2rem;
        }
        .invalid-feedback {
            display: none;
        }
        .was-validated .form-control:invalid ~ .invalid-feedback,
        .form-control.is-invalid ~ .invalid-feedback {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <h1 class="text-center mb-4">Registro</h1>
            <div class="alert alert-danger" id="errorAlert" role="alert"></div>
            <div class="alert alert-success" id="successAlert" role="alert"></div>
            
            <form id="registerForm" class="register-form" novalidate>
                <!-- Dados Pessoais -->
                <div class="form-section">
                    <h4>Dados Pessoais</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome:</label>
                            <input type="text" id="nome" name="nome" class="form-control" 
                                   pattern="[A-Za-zÀ-ÖØ-öø-ÿ\s]+" required>
                            <div class="invalid-feedback">
                                Nome deve conter apenas letras
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sobrenome" class="form-label">Sobrenome:</label>
                            <input type="text" id="sobrenome" name="sobrenome" class="form-control" 
                                   pattern="[A-Za-zÀ-ÖØ-öø-ÿ\s]+" required>
                            <div class="invalid-feedback">
                                Sobrenome deve conter apenas letras
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cpf" class="form-label">CPF:</label>
                            <input type="text" id="cpf" name="cpf" class="form-control" 
                                   pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" required>
                            <div class="invalid-feedback">
                                CPF inválido
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_nascimento" class="form-label">Data de Nascimento:</label>
                            <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" 
                                   max="{{ date('Y-m-d') }}" required>
                            <div class="invalid-feedback">
                                Data de nascimento inválida
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="form-section">
                    <h4>Endereço</h4>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="rua" class="form-label">Rua:</label>
                            <input type="text" id="rua" name="rua" class="form-control" 
                                   pattern="[A-Za-zÀ-ÖØ-öø-ÿ0-9\s\.,]+" required>
                            <div class="invalid-feedback">
                                Rua inválida
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="numero" class="form-label">Número:</label>
                            <input type="text" id="numero" name="numero" class="form-control" 
                                   pattern="[0-9]+" required>
                            <div class="invalid-feedback">
                                Número deve conter apenas dígitos
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="complemento" class="form-label">Complemento:</label>
                            <input type="text" id="complemento" name="complemento" class="form-control">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="bairro" class="form-label">Bairro:</label>
                            <input type="text" id="bairro" name="bairro" class="form-control" 
                                   pattern="[A-Za-zÀ-ÖØ-öø-ÿ0-9\s\.,]+" required>
                            <div class="invalid-feedback">
                                Bairro inválido
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="cidade" class="form-label">Cidade:</label>
                            <input type="text" id="cidade" name="cidade" class="form-control" 
                                   pattern="[A-Za-zÀ-ÖØ-öø-ÿ\s]+" required>
                            <div class="invalid-feedback">
                                Cidade deve conter apenas letras
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado:</label>
                            <input type="text" id="estado" name="estado" class="form-control" 
                                   pattern="[A-Z]{2}" maxlength="2" required>
                            <div class="invalid-feedback">
                                Use a sigla do estado em maiúsculo (ex: SP)
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dados de Acesso -->
                <div class="form-section">
                    <h4>Dados de Acesso</h4>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                            <div class="invalid-feedback">
                                Email inválido
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="senha" class="form-label">Senha:</label>
                            <input type="password" id="senha" name="senha" class="form-control" 
                                   pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" required>
                            <div class="invalid-feedback">
                                Senha deve ter no mínimo 8 caracteres, incluindo letras e números
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
                            <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control" required>
                            <div class="invalid-feedback">
                                As senhas não coincidem
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                    <div class="text-center mt-3">
                        <a href="/login">Já tem uma conta? Faça login</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Set up axios defaults for CSRF
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Function to display error messages with auto-hide
        function displayErrors(errors) {
            const errorAlert = document.getElementById('errorAlert');
            if (typeof errors === 'string') {
                errorAlert.innerHTML = errors;
            } else {
                const errorMessages = Object.values(errors).flat();
                errorAlert.innerHTML = errorMessages.join('<br>');
            }
            errorAlert.style.display = 'block';
            errorAlert.style.opacity = '1';
            errorAlert.classList.remove('fade-out');
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                errorAlert.classList.add('fade-out');
                setTimeout(() => {
                    errorAlert.style.display = 'none';
                }, 500); // Wait for fade animation to complete
            }, 5000);
        }

        // CPF Validation function
        function isValidCPF(cpf) {
            cpf = cpf.replace(/[^\d]/g, '');
            
            if (cpf.length !== 11) return false;
            
            if (/^(\d)\1+$/.test(cpf)) return false;
            
            let sum = 0;
            let remainder;
            
            for (let i = 1; i <= 9; i++) {
                sum += parseInt(cpf.substring(i-1, i)) * (11 - i);
            }
            
            remainder = (sum * 10) % 11;
            if (remainder === 10 || remainder === 11) remainder = 0;
            if (remainder !== parseInt(cpf.substring(9, 10))) return false;
            
            sum = 0;
            for (let i = 1; i <= 10; i++) {
                sum += parseInt(cpf.substring(i-1, i)) * (12 - i);
            }
            
            remainder = (sum * 10) % 11;
            if (remainder === 10 || remainder === 11) remainder = 0;
            if (remainder !== parseInt(cpf.substring(10, 11))) return false;
            
            return true;
        }

        // Setup input masks and validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const inputs = form.querySelectorAll('input');

            // Add validation class only after user interaction
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value) {
                        this.classList.add('was-validated-field');
                    }
                });

                input.addEventListener('input', function() {
                    if (this.classList.contains('was-validated-field')) {
                        this.classList.add('is-invalid');
                        if (this.checkValidity()) {
                            this.classList.remove('is-invalid');
                            this.classList.add('is-valid');
                        } else {
                            this.classList.remove('is-valid');
                            this.classList.add('is-invalid');
                        }
                    }
                });
            });

            // CPF mask
            const cpfMask = new Inputmask("999.999.999-99");
            cpfMask.mask(document.getElementById("cpf"));

            // Estado to uppercase
            const estadoInput = document.getElementById('estado');
            estadoInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Validate CPF on blur
            const cpfInput = document.getElementById('cpf');
            cpfInput.addEventListener('blur', function() {
                if (this.value) {
                    const cpf = this.value;
                    if (!isValidCPF(cpf)) {
                        this.setCustomValidity('CPF inválido');
                        this.classList.add('is-invalid');
                    } else {
                        this.setCustomValidity('');
                        this.classList.remove('is-invalid');
                        if (this.classList.contains('was-validated-field')) {
                            this.classList.add('is-valid');
                        }
                    }
                }
            });

            // Validate password confirmation
            const senhaInput = document.getElementById('senha');
            const confirmarSenhaInput = document.getElementById('confirmar_senha');
            
            confirmarSenhaInput.addEventListener('input', function() {
                if (this.value || this.classList.contains('was-validated-field')) {
                    if (this.value !== senhaInput.value) {
                        this.setCustomValidity('As senhas não coincidem');
                        this.classList.add('is-invalid');
                    } else {
                        this.setCustomValidity('');
                        this.classList.remove('is-invalid');
                        if (this.classList.contains('was-validated-field')) {
                            this.classList.add('is-valid');
                        }
                    }
                }
            });

            senhaInput.addEventListener('input', function() {
                if (confirmarSenhaInput.value || confirmarSenhaInput.classList.contains('was-validated-field')) {
                    if (confirmarSenhaInput.value !== this.value) {
                        confirmarSenhaInput.setCustomValidity('As senhas não coincidem');
                        confirmarSenhaInput.classList.add('is-invalid');
                    } else {
                        confirmarSenhaInput.setCustomValidity('');
                        confirmarSenhaInput.classList.remove('is-invalid');
                        if (confirmarSenhaInput.classList.contains('was-validated-field')) {
                            confirmarSenhaInput.classList.add('is-valid');
                        }
                    }
                }
            });
        });
        
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Add validation class to all fields on form submission
            this.classList.add('was-validated');
            const inputs = this.querySelectorAll('input');
            inputs.forEach(input => input.classList.add('was-validated-field'));
            
            // Form validation
            if (!this.checkValidity()) {
                e.stopPropagation();
                return;
            }
            
            const formData = {
                nome: document.getElementById('nome').value,
                sobrenome: document.getElementById('sobrenome').value,
                cpf: document.getElementById('cpf').value.replace(/\D/g, ''),
                data_nascimento: document.getElementById('data_nascimento').value,
                rua: document.getElementById('rua').value,
                numero: document.getElementById('numero').value,
                complemento: document.getElementById('complemento').value,
                bairro: document.getElementById('bairro').value,
                cidade: document.getElementById('cidade').value,
                estado: document.getElementById('estado').value,
                email: document.getElementById('email').value,
                senha: document.getElementById('senha').value
            };
            
            try {
                const response = await axios.post('/api/usuarios/registrar', formData);
                
                if (response.data.status) {
                    // Store the token and user data
                    localStorage.setItem('token', response.data.token);
                    localStorage.setItem('user', JSON.stringify(response.data.user));
                    
                    const successAlert = document.getElementById('successAlert');
                    successAlert.style.display = 'block';
                    successAlert.textContent = 'Registro realizado com sucesso! Redirecionando...';
                    
                    // Clear form
                    this.reset();
                    
                    // Remove validation classes
                    this.classList.remove('was-validated');
                    inputs.forEach(input => {
                        input.classList.remove('was-validated-field', 'is-valid', 'is-invalid');
                    });
                    
                    // Redirect to home page after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 2000);
                }
            } catch (error) {
                if (error.response?.status === 422) {
                    // Validation errors
                    displayErrors(error.response.data.error);
                } else {
                    // Other errors
                    displayErrors(error.response?.data?.message || 'Erro ao registrar. Tente novamente.');
                }
            }
        });
    </script>
</body>
</html> 