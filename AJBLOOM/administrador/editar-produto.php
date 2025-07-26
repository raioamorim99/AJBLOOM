<?php
session_start();
include '../backend/conexao.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: painel-admin.php");
    exit();
}

// Buscar produto
$stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$produto = $stmt->get_result()->fetch_assoc();

if (!$produto) {
    header("Location: painel-admin.php");
    exit();
}

// Processar upload de imagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem_upload'])) {
    $upload_dir = '../assets/imagens/produtos/';
    
    // Criar diretório se não existir
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file = $_FILES['imagem_upload'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
    
    if (in_array($file_extension, $allowed_types) && $file['error'] === 0) {
        // Gerar nome único
        $new_filename = 'produto_' . $id . '_' . time() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            // Otimizar imagem se não for WebP
            if ($file_extension !== 'webp') {
                $webp_path = $upload_dir . 'produto_' . $id . '_' . time() . '.webp';
                if (convertToWebP($upload_path, $webp_path)) {
                    unlink($upload_path); // Remove original
                    $upload_path = $webp_path;
                    $new_filename = basename($webp_path);
                }
            }
            
            // Atualizar banco com nova imagem
            $relative_path = 'assets/imagens/produtos/' . $new_filename;
            $stmt = $conn->prepare("UPDATE produtos SET imagem = ? WHERE id = ?");
            $stmt->bind_param("si", $relative_path, $id);
            $stmt->execute();
            
            $sucesso_upload = "Imagem enviada e otimizada com sucesso!";
            $produto['imagem'] = $relative_path;
        } else {
            $erro_upload = "Erro ao fazer upload da imagem.";
        }
    } else {
        $erro_upload = "Formato não suportado. Use JPG, PNG ou WebP.";
    }
}

// Processar formulário principal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_FILES['imagem_upload'])) {
    $nome = $_POST['nome'];
    $preco = floatval($_POST['preco']);
    $preco_antigo = !empty($_POST['preco_antigo']) ? floatval($_POST['preco_antigo']) : null;
    $categoria = $_POST['categoria'];
    $imagem = $_POST['imagem_url']; // URL externa
    $descricao = $_POST['descricao'];
    $lancamento = isset($_POST['lancamento']) ? 1 : 0;
    $mais_vendido = isset($_POST['mais_vendido']) ? 1 : 0;

    $sql = "UPDATE produtos SET nome = ?, preco = ?, preco_antigo = ?, categoria = ?, imagem = ?, descricao = ?, lancamento = ?, mais_vendido = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsssssii", $nome, $preco, $preco_antigo, $categoria, $imagem, $descricao, $lancamento, $mais_vendido, $id);
    
    if ($stmt->execute()) {
        $sucesso = "Produto atualizado com sucesso!";
        // Recarregar dados
        $stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $produto = $stmt->get_result()->fetch_assoc();
    } else {
        $erro = "Erro ao atualizar produto: " . $conn->error;
    }
}

// Função para converter imagem para WebP
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
    
    $result = imagewebp($image, $destination, 85); // 85% qualidade
    imagedestroy($image);
    
    return $result;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto com Upload - AJBLOOM Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .form-container {
            padding: 2rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Seção de imagem aprimorada */
        .image-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 2rem;
            margin: 2rem 0;
        }

        .image-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .tab-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid #e1e5e9;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .tab-btn.active {
            border-color: #667eea;
            background: #667eea;
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .upload-area {
            border: 3px dashed #667eea;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background: white;
            transition: all 0.3s;
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: #5a6fd8;
            background: #f8f9ff;
        }

        .upload-area.dragover {
            border-color: #28a745;
            background: #f8fff8;
        }

        .upload-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }

        .file-input {
            display: none;
        }

        .preview-container {
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            background: white;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .preview-img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .image-info {
            background: #e3f2fd;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
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

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .format-support {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1rem;
        }

        .format-badge {
            padding: 0.25rem 0.75rem;
            background: #28a745;
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .image-tabs {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-edit"></i> Editar Produto com Upload</h1>
            <p>Atualize as informações do produto #<?= $produto['id'] ?></p>
        </div>

        <div class="form-container">
            <?php if (isset($sucesso)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= $sucesso ?>
                </div>
            <?php endif; ?>

            <?php if (isset($sucesso_upload)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-upload"></i> <?= $sucesso_upload ?>
                </div>
            <?php endif; ?>

            <?php if (isset($erro) || isset($erro_upload)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?= $erro ?? $erro_upload ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="form-produto">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nome">
                            <i class="fas fa-tag"></i> Nome do Produto *
                        </label>
                        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="categoria">
                            <i class="fas fa-list"></i> Categoria *
                        </label>
                        <select id="categoria" name="categoria" required>
                            <option value="">Selecione uma categoria</option>
                            <option value="camisetas" <?= $produto['categoria'] === 'camisetas' ? 'selected' : '' ?>>Camisetas</option>
                            <option value="vestidos" <?= $produto['categoria'] === 'vestidos' ? 'selected' : '' ?>>Vestidos</option>
                            <option value="acessorios" <?= $produto['categoria'] === 'acessorios' ? 'selected' : '' ?>>Acessórios</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="preco">
                            <i class="fas fa-dollar-sign"></i> Preço Atual (R$) *
                        </label>
                        <input type="number" id="preco" name="preco" step="0.01" min="0" value="<?= $produto['preco'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="preco_antigo">
                            <i class="fas fa-percentage"></i> Preço Anterior (R$)
                        </label>
                        <input type="number" id="preco_antigo" name="preco_antigo" step="0.01" min="0" value="<?= $produto['preco_antigo'] ?>">
                    </div>
                </div>

                <!-- Seção de Imagem Aprimorada -->
                <div class="image-section">
                    <h3><i class="fas fa-images"></i> Gerenciar Imagem do Produto</h3>
                    
                    <div class="image-tabs">
                        <button type="button" class="tab-btn active" onclick="switchTab('upload')">
                            <i class="fas fa-upload"></i> Upload de Arquivo
                        </button>
                        <button type="button" class="tab-btn" onclick="switchTab('url')">
                            <i class="fas fa-link"></i> URL Externa
                        </button>
                    </div>

                    <!-- Tab Upload -->
                    <div id="upload-tab" class="tab-content active">
                        <form method="POST" enctype="multipart/form-data" id="upload-form">
                            <div class="upload-area" onclick="document.getElementById('file-input').click()">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <h4>Clique ou arraste uma imagem aqui</h4>
                                <p>Formatos suportados: JPG, PNG, WebP</p>
                                <p><small>Máximo 5MB - Será otimizada automaticamente</small></p>
                                <input type="file" id="file-input" name="imagem_upload" accept=".jpg,.jpeg,.png,.webp" class="file-input">
                            </div>
                            
                            <div class="format-support">
                                <span class="format-badge">JPG</span>
                                <span class="format-badge">PNG</span>
                                <span class="format-badge">WebP</span>
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
                        <div class="form-group">
                            <label for="imagem_url">
                                <i class="fas fa-link"></i> URL da Imagem Externa
                            </label>
                            <input type="url" id="imagem_url" name="imagem_url" value="<?= htmlspecialchars($produto['imagem']) ?>" placeholder="https://exemplo.com/imagem.jpg">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="testarURL()" style="margin-top: 0.5rem;">
                                <i class="fas fa-eye"></i> Testar URL
                            </button>
                        </div>
                    </div>

                    <!-- Preview da Imagem -->
                    <div class="preview-container" id="preview-container">
                        <?php if ($produto['imagem']): ?>
                            <?php 
                            $imagem_path = $produto['imagem'];
                            // Se não começar com http, é arquivo local
                            if (!str_starts_with($imagem_path, 'http')) {
                                $imagem_path = '../' . $imagem_path;
                            }
                            ?>
                            <img src="<?= htmlspecialchars($imagem_path) ?>" alt="Preview" class="preview-img" id="preview-img">
                            <div class="image-info">
                                <strong>Imagem Atual:</strong> <?= basename($produto['imagem']) ?><br>
                                <small>Tipo: <?= str_starts_with($produto['imagem'], 'http') ? 'URL Externa' : 'Arquivo Local' ?></small>
                            </div>
                        <?php else: ?>
                            <i class="fas fa-image" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                            <p style="color: #666;">Nenhuma imagem definida</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descricao">
                        <i class="fas fa-align-left"></i> Descrição
                    </label>
                    <textarea id="descricao" name="descricao" rows="4"><?= htmlspecialchars($produto['descricao']) ?></textarea>
                </div>

                <div style="display: flex; gap: 2rem; margin: 1rem 0;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="lancamento" <?= $produto['lancamento'] ? 'checked' : '' ?>>
                        <i class="fas fa-star"></i> Produto em Lançamento
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="mais_vendido" <?= $produto['mais_vendido'] ? 'checked' : '' ?>>
                        <i class="fas fa-fire"></i> Mais Vendido
                    </label>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                    <a href="painel-admin.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Alternar entre tabs
        function switchTab(tab) {
            // Remover active de todos
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            // Ativar tab selecionada
            document.querySelector(`button[onclick="switchTab('${tab}')"]`).classList.add('active');
            document.getElementById(`${tab}-tab`).classList.add('active');
        }

        // Drag and drop
        const uploadArea = document.querySelector('.upload-area');
        const fileInput = document.getElementById('file-input');

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                previewFile(files[0]);
            }
        });

        // Preview do arquivo selecionado
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
                    <div class="image-info">
                        <strong>Arquivo Selecionado:</strong> ${file.name}<br>
                        <small>Tamanho: ${(file.size / 1024 / 1024).toFixed(2)} MB | Tipo: ${file.type}</small>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }

        // Testar URL
        function testarURL() {
            const url = document.getElementById('imagem_url').value;
            if (!url) {
                alert('Digite uma URL primeiro');
                return;
            }

            const img = new Image();
            img.onload = function() {
                document.getElementById('preview-container').innerHTML = `
                    <img src="${url}" alt="Preview" class="preview-img">
                    <div class="image-info">
                        <strong>URL Externa:</strong> ${url.split('/').pop()}<br>
                        <small>Dimensões: ${this.width}x${this.height}px</small>
                    </div>
                `;
            };
            img.onerror = function() {
                alert('Não foi possível carregar a imagem. Verifique a URL.');
            };
            img.src = url;
        }
    </script>
</body>
</html>
