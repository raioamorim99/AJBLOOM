<?php
session_start();

// Log do logout
if (isset($_SESSION['admin_usuario'])) {
    error_log("Admin logout: " . $_SESSION['admin_usuario'] . " - IP: " . $_SERVER['REMOTE_ADDR'] . " - " . date('Y-m-d H:i:s'));
}

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Destruir o cookie de sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir a sessão
session_destroy();

// Redirecionar para login com mensagem de sucesso
header('Location: login.php?logout=1');
exit();
?>
