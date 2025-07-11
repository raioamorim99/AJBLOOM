<?php
session_start();
include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nome = $_POST["nome"];
  $email = $_POST["email"];
  $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
  $telefone = $_POST["telefone"];
  $nascimento = $_POST["nascimento"]; // Novo campo

  $sql = "INSERT INTO usuarios (nome, email, senha_hash, telefone, data_nascimento) 
          VALUES (?, ?, ?, ?, ?)";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $nome, $email, $senha, $telefone, $nascimento);

  if ($stmt->execute()) {
    $_SESSION["usuario_id"] = $conn->insert_id;
    $_SESSION["usuario_nome"] = $nome;
    $_SESSION["mensagem_sucesso"] = "Cadastro feito com sucesso!";
    header("Location: ../pages/index.php");
    exit();
  } else {
    echo "❌ Erro ao cadastrar: " . $conn->error;
  }

  $stmt->close();
  $conn->close();
}
?>
