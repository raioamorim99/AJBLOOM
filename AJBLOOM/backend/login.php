<?php
session_start();
include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $senha = $_POST["senha"];

  $sql = "SELECT * FROM usuarios WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $resultado = $stmt->get_result();

  if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    if (password_verify($senha, $usuario["senha_hash"])) {
      // Login OK → salvar dados na sessão
      $_SESSION["usuario_id"] = $usuario["id"];
      $_SESSION["usuario_nome"] = $usuario["nome"];
      header("Location: painel.php"); // redirecionar pro painel
      exit();
    } else {
      echo "❌ Senha incorreta.";
    }
  } else {
    echo "❌ Usuário não encontrado.";
  }

  $stmt->close();
  $conn->close();
}
?>
