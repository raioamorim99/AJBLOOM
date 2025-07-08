<?php
include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nome = $_POST["nome"];
  $email = $_POST["email"];
  $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
  $telefone = $_POST["telefone"];
  $endereco = $_POST["endereco"];

  $sql = "INSERT INTO usuarios (nome, email, senha_hash, telefone, endereco) 
          VALUES (?, ?, ?, ?, ?)";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $nome, $email, $senha, $telefone, $endereco);

  if ($stmt->execute()) {
    echo "✅ Usuário cadastrado com sucesso!";
  } else {
    echo "❌ Erro ao cadastrar: " . $conn->error;
  }

  $stmt->close();
  $conn->close();
}
?>
