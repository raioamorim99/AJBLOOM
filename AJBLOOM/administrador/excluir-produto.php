<?php
session_start();
include '../backend/conexao.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: painel-admin.php");
    exit();
}

// Buscar produto para confirmação
$stmt = $conn->prepare("SELECT nome FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$produto = $stmt->get_result()->fetch_assoc();

if (!$produto) {
    header("Location: painel-admin.php");
    exit();
}

// Processar exclusão
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: painel-admin.php?excluido=1");
        exit();
    } else {
        $erro = "Erro ao excluir produto: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Produto - AJBLOOM Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .container {
            max-width: 500px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            text-align: center;
        }

        .header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 2rem;
        }

        .header i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .header h1 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .content {
            padding: 2rem;
        }

        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .warning-box h3 {
            color: #856404;
            margin-bottom: 1rem;
        }

        .warning-box p {
            color: #856404;
            line-height: 1.6;
        }

        .produto-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-exclamation-triangle"></i>
            <h1>Confirmar Exclusão</h1>
            <p>Esta ação não pode ser desfeita</p>
        </div>

        <div class="content">
            <?php if (isset($erro)): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?= $erro ?>
                </div>
            <?php endif; ?>

            <div class="warning-box">
                <h3><i class="fas fa-warning"></i> Atenção!</h3>
                <p>Você está prestes a excluir permanentemente o produto:</p>
                
                <div class="produto-info">
                    <strong><?= htmlspecialchars($produto['nome']) ?></strong><br>
                    <small>ID: #<?= $id ?></small>
                </div>
                
                <p>Esta ação removerá o produto de:</p>
                <ul style="text-align: left; margin-top: 1rem;">
                    <li>Catálogo do site</li>
                    <li>Resultados de busca</li>
                    <li>Relatórios futuros</li>
                </ul>
            </div>

            <form method="POST">
                <div class="btn-group">
                    <button type="submit" name="confirmar" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Sim, Excluir
                    </button>
                    <a href="painel-admin.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
