<?php
session_start();
include '../backend/conexao.php';

// Processar upload de imagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem_upload'])) {
    $upload_dir = '../assets/imagens/produtos/';
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file = $_FILES['imagem_upload'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
    
    if (in_array($file_extension, $allowed_types) && $file['error'] === 0) {
        $new_filename = 'produto_novo_' . time() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            // Converter para WebP se necessário
            if ($file_extension !== 'webp') {
                $webp_path = $upload_dir . 'produto_novo_' . time() . '.webp';
                if (convertToWebP($upload_path, $webp_path)) {
                    unlink($upload_path);
                    $upload_path = $webp_path;
                    $new_filename = basename($webp_path);
                }
            }
            
            $imagem_temp = 'assets/imagens/produtos/' . $new_filename;
            $sucesso_upload = "Imagem enviada com sucesso! Preencha os outros campos.";
        }
    }
}

// Processar formulário principal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_FILES['imagem_upload'])) {
    $nome = $_POST['nome'];
    $preco = floatval($_POST['preco']);
    $preco_antigo = !empty($_POST['preco_antigo']) ? floatval($_POST['preco_antigo']) : null;
    $categoria = $_POST['categoria'];
    $imagem = $_POST['imagem_url'] ?: ($_POST['imagem_temp'] ?? '');
    $descricao = $_POST['descricao'];
    $lancamento = isset($_POST['lancamento']) ? 1 : 0;
    $mais_vendido = isset($_POST['mais_vendido']) ? 1 : 0;

    $sql = "INSERT INTO produtos (nome, preco, preco_antigo, categoria, imagem, descricao, lancamento, mais_vendido) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssssii", $nome, $preco, $preco_antigo, $categoria, $imagem, $descricao, $lancamento, $mais_vendido);
    
    if ($stmt->execute()) {
        $produto_id = $conn->insert_id;
        header("Location: editar-produto-com-upload.php?id=$produto_id&sucesso=1");
        exit();
    } else {
        $erro = "Erro ao adicionar produto: " . $conn->error;
    }
}

function convertToWebP($source, $destination) {
    $info = getimagesize($source);
    if ($info === false) return false;
    
    $image = null;
    switch ($info['mime']) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        default:
            return false;
    }
    
    if ($image === null) return false;
    
    // Redimensionar se muito grande
    $width = imagesx($image);
    $height = imagesy($image);
    $max_size = 800;
    
    if ($width > $max_size || $height > $max_size) {
        $ratio = min($max_size / $width, $max_size / $height);
        $new_width = intval($width * $ratio);
        $new_height = intval($height * $ratio);
        
        $resized = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagedestroy($image);
        $image = $resized;
    }
    
    $result = imagewebp($image, $destination, 85);
    imagedestroy($image);
    
    return $result;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto com Upload - AJBLOOM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Mesmo CSS da página de edição */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        /* Resto do CSS igual ao arquivo de edição */
        .form-container { padding: 2rem; }
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 0.75rem; border: 2px solid #e1e5e9;
            border-radius: 8px; font-size: 1rem;
        }
        .image-section { background: #f8f9fa; border-radius: 12px; padding: 2rem; margin: 2rem 0; }
        .image-tabs { display: flex; gap: 1rem; margin-bottom: 2rem; }
        .tab-btn { padding: 0.75rem 1.5rem; border: 2px solid #e1e5e9; background: white; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .tab-btn.active { border-color: #28a745; background: #28a745; color: white; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .upload-area { border: 3px dashed #28a745; border-radius: 12px; padding: 2rem; text-align: center; background: white; cursor: pointer; }
        .upload-area:hover { border-color: #20c997; background: #f8fff8; }
        .upload-icon { font-size: 3rem; color: #28a745; margin-bottom: 1rem; }
        .file-input { display: none; }
        .preview-container { border: 2px solid #e1e5e9; border-radius: 12px; padding: 1rem; text-align: center; background: white; min-height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .preview-img { max-width: 100%; max-height: 300px; border-radius: 8px; margin-bottom: 1rem; }
        .btn { padding: 0.75rem 2rem; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; }
        .btn-success { background: #28a745; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-group { display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-plus-circle"></i> Adicionar Produto com Upload</h1>
            <p>Crie um novo produto com imagem local ou URL</p>
        </div>

        <div class="form-container">
            <?php if (isset($sucesso_upload)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-upload"></i> <?= $sucesso_upload ?>
                </div>
            <?php endif; ?>

            <?php if (isset($erro)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?= $erro ?>
                </div>
            <?php endif; ?>

            <!-- Upload de Imagem Primeiro -->
            <div class="image-section">
                <h3><i class="fas fa-images"></i> Adicionar Imagem do Produto</h3>
                
                <div class="image-tabs">
                    <button type="button" class="tab-btn active" onclick="switchTab('upload')">
                        <i class="fas fa-upload"></i> Upload WebP/JPG/PNG
                    </button>
                    <button type="button" class="tab-btn" onclick="switchTab('url')">
                        <i class="fas fa-link"></i> URL Externa
                    </button>
                </div>

                <!-- Tab Upload -->
                <div id="upload-tab" class="tab-content active">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="upload-area" onclick="document.getElementById('file-input').click()">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <h4>📸 Clique ou arraste sua imagem aqui</h4>
                            <p><strong>Formatos aceitos:</strong> JPG, PNG, WebP</p>
                            <p><small>Será otimizada automaticamente para WebP</small></p>
                            <input type="file" id="file-input" name="imagem_upload" accept=".jpg,.jpeg,.png,.webp" class="file-input">
                        </div>
                        
                        <div style="text-align: center; margin-top: 1rem;">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload"></i> Fazer Upload
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tab URL -->
                <div id="url-tab" class="tab-content">
                    <input type="url" placeholder="https://exemplo.com/imagem.jpg" style="width: 100%; padding: 1rem; border: 2px solid #e1e5e9; border-radius: 8px;">
                    <button type="button" class="btn btn-secondary" style="margin-top: 1rem;" onclick="testarURL()">
                        <i class="fas fa-eye"></i> Testar URL
                    </button>
                </div>

                <!-- Preview -->
                <div class="preview-container" id="preview-container">
                    <?php if (isset($imagem_temp)): ?>
                        <img src="../<?= $imagem_temp ?>" alt="Preview" class="preview-img">
                        <p><strong>✅ Imagem carregada!</strong> Agora preencha os dados do produto.</p>
                    <?php else: ?>
                        <i class="fas fa-image" style="font-size: 3rem; color: #ccc;"></i>
                        <p>Nenhuma imagem selecionada</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Formulário do Produto -->
            <form method="POST">
                <?php if (isset($imagem_temp)): ?>
                    <input type="hidden" name="imagem_temp" value="<?= $imagem_temp ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Nome do Produto *</label>
                        <input type="text" name="nome" required placeholder="Ex: Camiseta Floral WebP">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-list"></i> Categoria *</label>
                        <select name="categoria" required>
                            <option value="">Selecione</option>
                            <option value="camisetas">Camisetas</option>
                            <option value="vestidos">Vestidos</option>
                            <option value="acessorios">Acessórios</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-dollar-sign"></i> Preço (R$) *</label>
                        <input type="number" name="preco" step="0.01" required placeholder="79.90">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-percentage"></i> Preço Anterior (R$)</label>
                        <input type="number" name="preco_antigo" step="0.01" placeholder="99.90">
                    </div>

                    <div class="form-group full-width">
                        <label><i class="fas fa-align-left"></i> Descrição</label>
                        <textarea name="descricao" rows="3" placeholder="✓ Tecido premium&#10;✓ Imagem em WebP otimizada&#10;✓ Carregamento rápido"></textarea>
                    </div>
                </div>

                <div style="display: flex; gap: 2rem; margin: 1rem 0;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="lancamento">
                        <i class="fas fa-star"></i> Lançamento
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="mais_vendido">
                        <i class="fas fa-fire"></i> Mais Vendido
                    </label>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Criar Produto
                    </button>
                    <a href="painel-admin.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            document.querySelector(`button[onclick="switchTab('${tab}')"]`).classList.add('active');
            document.getElementById(`${tab}-tab`).classList.add('active');
        }

        // Drag and drop
        const uploadArea = document.querySelector('.upload-area');
        const fileInput = document.getElementById('file-input');

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#20c997';
            uploadArea.style.background = '#f8fff8';
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '#28a745';
            uploadArea.style.background = 'white';
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                previewFile(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                previewFile(e.target.files[0]);
            }
        });

        function previewFile(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('preview-container').innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="preview-img">
                    <p><strong>📁 ${file.name}</strong><br>
                    <small>${(file.size / 1024 / 1024).toFixed(2)} MB | ${file.type}</small></p>
                `;
            };
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>
