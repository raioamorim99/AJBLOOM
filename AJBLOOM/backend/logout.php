<?php
session_start();
session_unset(); // limpa as variáveis
session_destroy(); // encerra a sessão
header("Location: ../pages/index.php"); // redireciona pro login
exit();
?>
