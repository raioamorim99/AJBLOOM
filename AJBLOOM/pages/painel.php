<?php
session_start();

// Verifica se está logado
if (!isset($_SESSION["usuario_id"])) {
  header("Location: form-login.html");
  exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel AJBLOOM</title>
</head>
<body>
  <h2>Bem-vinda, <?php echo $_SESSION["usuario_nome"]; ?>!</h2>
  <p>Você está logada com sucesso. Esta área é protegida.</p>
  <a href="logout.php">Sair</a>
</body>
</html>
