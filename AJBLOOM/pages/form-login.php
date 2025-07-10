<?php session_start(); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Acesse sua conta na Ajbloom e acompanhe suas preferências, histórico e novidades da moda feminina.">
  <title>Login - Acesse sua Conta Ajbloom</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(to bottom right, #90b7eb , #ffffff);
      font-family: 'Poppins', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-container {
      background-color: #ffffff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
      max-width: 500px;
      width: 100%;
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
      font-weight: 600;
    }

    .form-group {
      margin-bottom: 25px;
    }

    input[type="email"],
    input[type="password"] {
      width: 85%;
      padding: 12px 16px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      transition: border-color 0.3s;
    }

    input:focus {
      border-color: #c084fc;
      outline: none;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #90b7eb;
      border: none;
      color: white;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #5768ff;
    }

    .form-footer {
      text-align: center;
      margin-top: 20px;
    }

    .form-footer a {
      color: #90b7eb;
      text-decoration: none;
      font-size: 14px;
    }

    .form-footer a:hover {
      text-decoration: underline;
    }

    .icon-input {
      position: relative;
    }

    .icon-input i {
      position: absolute;
      top: 50%;
      left: 14px;
      transform: translateY(-50%);
      color: #aaa;
    }

    .icon-input input {
      padding-left: 40px;
    }

    .logo-container {
  text-align: center;
  margin-bottom: 1rem;
}

.logo-login {
  max-width: 250px;
  height: auto;
  display: inline-block;
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}

.social-login {
  text-align: center;
  margin: 30px 0 10px;
}

.social-buttons button {
  width: 100%;
  margin-bottom: 10px;
  padding: 10px;
  font-size: 15px;
  font-weight: 500;
  border: none;
  border-radius: 5px;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  cursor: pointer;
}

.google-btn {
  background-color: #db4437;
}
.facebook-btn {
  background-color: #4267B2;
}
.apple-btn {
  background-color: #333;
}

  </style>
</head>
<body>

  <form action="../backend/login.php" method="POST" class="login-container">

    <div class="logo-container">
  <img src="../assets/imagens/logo/logo.png" alt="Logo AJBLOOM" class="logo-login">
</div>

  
    <h2>Entrar na AJbloom</h2>

    <div class="form-group icon-input">
      <i class="fas fa-envelope"></i>
      <input type="email" name="email" placeholder="E-mail" required>
    </div>

    <div class="form-group icon-input">
      <i class="fas fa-lock"></i>
      <input type="password" name="senha" placeholder="Senha" required>
    </div>

    <div class="social-login">
  <p>Ou entre com</p>
  <div class="social-buttons">
    <button class="google-btn"><i class="fab fa-google"></i> Google</button>
    <button class="facebook-btn"><i class="fab fa-facebook-f"></i> Facebook</button>
    <button class="apple-btn"><i class="fab fa-apple"></i> Apple</button>
  </div>
</div>


    <button type="submit">Entrar</button>

    <div class="form-footer">
      <p>Ainda não tem conta? <a href="../pages/form-cadastro.html">Cadastre-se</a></p>
    </div>
  </form>

</body>
</html>
