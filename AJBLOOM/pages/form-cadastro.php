<?php session_start(); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Cadastre-se na Ajbloom e fique por dentro das novidades, lançamentos e promoções exclusivas.">
  <title>	Cadastro - Crie sua Conta na Ajbloom</title>
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

    .cadastro-container {
      background-color: #ffffff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
      max-width: 450px;
      width: 100%;
    }

    .cadastro-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
      font-weight: 600;
    }

    .form-group {
      margin-bottom: 20px;
      position: relative;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    textarea {
      width: 88%;
      padding: 12px 16px;
      padding-left: 40px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      resize: none;
      transition: border-color 0.3s;
    }

    input:focus,
    textarea:focus {
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

    .icon-input i {
      position: absolute;
      top: 50%;
      left: 14px;
      transform: translateY(-50%);
      color: #aaa;
    }

    /* Estilização do campo de data */
input[type="date"] {
  width: 100%;
  padding: 12px 45px;
  border-radius: 8px;
  border: 1px solid #ccc;
  background-color: #f9f9f9;
  font-size: 16px;
  color: #333;
  box-sizing: border-box;
  margin-bottom: 16px;
  position: relative;
}

/* Ícone de calendário alinhado com aparência clean */
input[type="date"]::-webkit-calendar-picker-indicator {
  position: absolute;
  right: 15px;
  color: #999;
  cursor: pointer;
  font-size: 18px;
  opacity: 0.8;
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
    /* Estilização do logo */
   .logo-container {
  text-align: center;
  margin-bottom: 1rem;
}

.logo-cadastro {
  max-width: 250px;
  height: auto;
  display: inline-block;
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}

  </style>
</head>


<body>

  

  

  <form action="../backend/cadastro.php" method="POST" class="cadastro-container">
    <div class="logo-container">
  <img src="../assets/imagens/logo/logo.png" alt="Logo AJBLOOM" class="logo-cadastro">
</div>

  
    <h2>Crie sua conta AJBLOOM</h2>

    <div class="form-group icon-input">
      <i class="fas fa-user"></i>
      <input type="text" name="nome" placeholder="Nome completo " required>
    </div>

    <div class="form-group icon-input">
      <i class="fas fa-envelope"></i>
      <input type="email" name="email" placeholder="E-mail" required>
    </div>

    <div class="form-group icon-input">
      <i class="fas fa-lock"></i>
      <input type="password" name="senha" placeholder="Senha" required>
    </div>

    <div class="form-group icon-input">
  <i class="fas fa-calendar-alt"></i>
  <input type="date" name="nascimento" required>
</div>

    <div class="form-group icon-input">
      <i class="fas fa-phone"></i>
      <input type="text" name="telefone" placeholder="Telefone">
    </div>

    

    <button type="submit">Cadastrar</button>

    <div class="form-footer">
      <p>Já tem uma conta? <a href="../pages/form-login.html">Entrar</a></p>
    </div>
  </form>

</body>
</html>
