<?php
session_start();
include '../backend/conexao.php';

// Listar todas as imagens locais
$imagens_dir = '../assets/imagens/produtos/';
$imagens_locais = [];

if (is_dir($imagens_dir)) {
    $files = scandir($imagens_dir);
    foreach ($files as $file) {
        if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp'])) {
            $filepath = $imagens_dir . $file;
            $imagens_locais[] = [
                'nome' => $file,
                'caminho' => $filepath,
                'tamanho' => filesize($filepath),
                'modificado' => filemtime($filepath),
                'usado' => false // Verificaremos depois
            ];
        }
    }
}

// Verificar quais imagens estão sendo usadas
$produtos_com_imagem = $conn->query("SELECT DISTINCT imagem FROM produtos WHERE imagem IS NOT NULL AND imagem != ''");
$imagens_usadas = [];
while ($row = $produtos_com_imagem->fetch_assoc()) {
    $imagens_usadas[] = $row['imagem'];
}

// Marcar imagens como usadas
foreach ($imagens_locais as &$imagem) {
    $caminho_relativo = 'assets/imagens/produtos/' . $imagem['nome'];
    if (in_array($caminho_relativo, $imagens_usadas)) {
        $imagem['usado'] = true;
    }
}

// Processar exclusão de imagem
if (isset($_POST['excluir_imagem'])) {
    $arquivo = $_POST['arquivo'];
    $caminho_completo = $imagens_dir . $arquivo;
    
    if (file_exists($caminho_completo)) {
        // Verificar se está sendo usada
        $caminho_relativo = 'assets/imagens/produtos/' . $arquivo;
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM produtos WHERE imagem = ?");
        $stmt->bind_param("s", $caminho_relativo);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        
        if ($resultado['total'] == 0) {
            if (unlink($caminho_completo)) {
                $sucesso = "Imagem excluída com sucesso!";
            } else {
                $erro = "Erro ao excluir imagem.";
            }
        } else {
            $erro = "Não é possível excluir. Imagem está sendo usada por {$resultado['total']} produto(s).";
        }
    }
    
    // Recarregar página para atualizar lista
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Imagens - AJBLOOM Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .content { padding: 2rem; }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        .images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        .image-card {
            background: white;
            border: 1px solid #e1e5e9;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .image-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f8f9fa;
        }
        .image-info {
            padding: 1rem;
        }
        .image-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
            word-break: break-all;
        }
        .image-meta {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 1rem;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-usado {
            background: #d4edda;
            color: #155724;
        }
        .status-nao-usado {
            background: #fff3cd;
            color: #856404;
        }
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0.25rem;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-info {
            background: #17a2b8;
            color: white;
        }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-images"></i> Gerenciar Imagens</h1>
            <p>Visualize e gerencie todas as imagens dos produtos</p>
        </div>

        <div class="content">
            <?php if (isset($sucesso)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= $sucesso ?>
                </div>
            <?php endif; ?>

            <?php if (isset($erro)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?= $erro ?>
                </div>
            <?php endif; ?>

            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?= count($imagens_locais) ?></div>
                    <div>Total de Imagens</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= count(array_filter($imagens_locais, fn($img) => $img['usado'])) ?></div>
                    <div>Em Uso</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= count(array_filter($imagens_locais, fn($img) => !$img['usado'])) ?></div>
                    <div>Não Utilizadas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= round(array_sum(array_column($imagens_locais, 'tamanho')) / 1024 / 1024, 1) ?>MB</div>
                    <div>Espaço Total</div>
                </div>
            </div>

            <div style="margin: 2rem 0; text-align: center;">
                <a href="adicionar-produto-com-upload.php" class="btn btn-info">
                    <i class="fas fa-plus"></i> Adicionar Nova Imagem
                </a>
                <a href="painel-admin.php" class="btn" style="background: #6c757d; color: white;">
                    <i class="fas fa-arrow-left"></i> Voltar ao Painel
                </a>
            </div>

            <div class="images-grid">
                <?php foreach ($imagens_locais as $imagem): ?>
                <div class="image-card">
                    <img src="<?= $imagem['caminho'] ?>" alt="<?= $imagem['nome'] ?>" class="image-preview">
                    <div class="image-info">
                        <div class="image-name"><?= $imagem['nome'] ?></div>
                        <div class="image-meta">
                            <strong>Tamanho:</strong> <?= round($imagem['tamanho'] / 1024, 1) ?>KB<br>
                            <strong>Modificado:</strong> <?= date('d/m/Y H:i', $imagem['modificado']) ?><br>
                            <strong>Formato:</strong> <?= strtoupper(pathinfo($imagem['nome'], PATHINFO_EXTENSION)) ?>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <?php if ($imagem['usado']): ?>
                                <span class="status-badge status-usado">
                                    <i class="fas fa-check"></i> Em Uso
                                </span>
                            <?php else: ?>
                                <span class="status-badge status-nao-usado">
                                    <i class="fas fa-exclamation"></i> Não Utilizada
                                </span>
                            <?php endif; ?>
                        </div>

                        <div>
                            <button onclick="visualizarImagem('<?= $imagem['caminho'] ?>', '<?= $imagem['nome'] ?>')" class="btn btn-info">
                                <i class="fas fa-eye"></i> Visualizar
                            </button>
                            
                            <?php if (!$imagem['usado']): ?>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta imagem?')">
                                    <input type="hidden" name="arquivo" value="<?= $imagem['nome'] ?>">
                                    <button type="submit" name="excluir_imagem" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Excluir
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($imagens_locais)): ?>
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <i class="fas fa-images" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                    <h3>Nenhuma imagem encontrada</h3>
                    <p>Adicione imagens através do formulário de produtos.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal para visualizar imagem -->
    <div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center;">
        <div style="max-width: 90%; max-height: 90%; background: white; border-radius: 10px; padding: 1rem;">
            <div style="text-align: right; margin-bottom: 1rem;">
                <button onclick="fecharModal()" style="background: #dc3545; color: white; border: none; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer;">
                    <i class="fas fa-times"></i> Fechar
                </button>
            </div>
            <img id="modalImage" style="max-width: 100%; max-height: 70vh; object-fit: contain;">
            <div id="modalTitle" style="text-align: center; margin-top: 1rem; font-weight: 600;"></div>
        </div>
    </div>

    <script>
        function visualizarImagem(caminho, nome) {
            document.getElementById('modalImage').src = caminho;
            document.getElementById('modalTitle').textContent = nome;
            document.getElementById('imageModal').style.display = 'flex';
        }

        function fecharModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Fechar modal clicando fora
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModal();
            }
        });
    </script>
</body>
</html>
