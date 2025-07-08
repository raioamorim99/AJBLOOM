<?php
session_start();
session_unset(); // limpa as variáveis
session_destroy(); // encerra a sessão
header("Location: form-login.html"); // redireciona pro login
exit();
?>
