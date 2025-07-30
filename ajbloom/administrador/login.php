<?php
session_start();

// ✅ CONFIGURAÇÕES DE SEGURANÇA
$admin_usuario = 'ajbloom'; // Usuário para desenvolvimento
$admin_senha = 'ajbloom123'; // Senha para desenvolvimento

// Se já está logado, redireciona para admin
if (isset($_SESSION['admin_logado']) && $_SESSION['admin_logado'] === true) {
    header('Location: admin.php');
    exit();
}

$erro = '';
$sucesso = '';

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    // Verificar credenciais
    if ($usuario === $admin_usuario && $senha === $admin_senha) {
        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_usuario'] = $usuario;
        $_SESSION['ultimo_acesso'] = time();
        $_SESSION['ip_login'] = $_SERVER['REMOTE_ADDR'];
        
        // Log de acesso (opcional)
        error_log("Admin login: $usuario - IP: " . $_SERVER['REMOTE_ADDR'] . " - " . date('Y-m-d H:i:s'));
        
        header('Location: admin.php');
        exit();
    } else {
        $erro = 'Usuário ou senha incorretos!';
        
        // Log de tentativa inválida
        error_log("Tentativa de login inválida: $usuario - IP: " . $_SERVER['REMOTE_ADDR'] . " - " . date('Y-m-d H:i:s'));
        
        // Delay para dificultar ataques de força bruta
        sleep(2);
    }
}

// Verificar se foi redirecionado por timeout
if (isset($_GET['timeout'])) {
    $erro = 'Sua sessão expirou. Faça login novamente.';
}

// Verificar se foi logout
if (isset($_GET['logout'])) {
    $sucesso = 'Logout realizado com sucesso!';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AJBLOOM Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .logo {
            margin-bottom: 2rem;
        }
        
        .logo i {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        .logo h1 {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .logo p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        /* Campo de senha com botão de visualizar */
        .password-field {
            position: relative;
        }
        
        .password-field input {
            padding-right: 3rem;
        }
        
        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }
        
        .toggle-password:hover {
            color: #667eea;
        }
        
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .security-info {
            margin-top: 2rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #666;
        }
        
        .security-info i {
            color: #667eea;
            margin-right: 0.5rem;
        }
        
        /* Credenciais de desenvolvimento */
        .dev-credentials {
            margin-top: 1rem;
            padding: 1rem;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #856404;
        }
        
        .dev-credentials h4 {
            margin-bottom: 0.5rem;
            color: #856404;
        }
        
        .dev-credentials p {
            margin: 0.25rem 0;
            font-family: monospace;
            font-weight: bold;
        }
        
        .loading {
            display: none;
        }
        
        .loading.active {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
            margin-right: 0.5rem;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 2rem;
                margin: 1rem;
            }
            
            .logo h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <i class="fas fa-crown"></i>
            <h1>AJBLOOM ADMIN</h1>
            <p>Área Administrativa</p>
        </div>
        
        <?php if ($erro): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($sucesso) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" id="loginForm">
            <div class="form-group">
                <label for="usuario">
                    <i class="fas fa-user"></i> Usuário
                </label>
                <input type="text" id="usuario" name="usuario" required autocomplete="username" value="ajbloom">
            </div>
            
            <div class="form-group">
                <label for="senha">
                    <i class="fas fa-lock"></i> Senha
                </label>
                <div class="password-field">
                    <input type="password" id="senha" name="senha" required autocomplete="current-password" value="ajbloom123">
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn-login" id="btnLogin">
                <span class="loading" id="loading"></span>
                <i class="fas fa-sign-in-alt"></i> Entrar
            </button>
        </form>
        
        <!-- Credenciais para desenvolvimento -->
        <div class="dev-credentials">
            <h4><i class="fas fa-code"></i> Credenciais de Desenvolvimento</h4>
            <p>Usuário: ajbloom</p>
            <p>Senha: ajbloom123</p>
        </div>
        
        <div class="security-info">
            <p><i class="fas fa-shield-alt"></i> Área restrita e monitorada</p>
            <p><i class="fas fa-clock"></i> Sessão expira em 30 minutos</p>
        </div>
    </div>
    
    <script>
        // Função para mostrar/ocultar senha
        document.getElementById('togglePassword').addEventListener('click', function() {
            const senhaInput = document.getElementById('senha');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (senhaInput.type === 'password') {
                senhaInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                senhaInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
        
        // Loading no submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const loading = document.getElementById('loading');
            const btnLogin = document.getElementById('btnLogin');
            
            loading.classList.add('active');
            btnLogin.disabled = true;
            btnLogin.style.opacity = '0.7';
        });
        
        // Focar no campo usuário ao carregar
        document.getElementById('usuario').focus();
        
        // Limpar mensagens após 5 segundos
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
        
        // Atalho para preencher campos rapidamente (apenas desenvolvimento)
        document.addEventListener('keydown', function(e) {
            // Ctrl + Shift + L para preencher login rapidamente
            if (e.ctrlKey && e.shiftKey && e.key === 'L') {
                document.getElementById('usuario').value = 'ajbloom';
                document.getElementById('senha').value = 'ajbloom123';
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
