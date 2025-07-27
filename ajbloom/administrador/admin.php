<?php
session_start();
include '../backend/conexao.php';

// Determinar ação baseada no parâmetro 'acao'
$acao = $_GET['acao'] ?? 'painel';

// Estatísticas gerais
$stats = [
    'total_produtos' => $conn->query("SELECT COUNT(*) as total FROM produtos")->fetch_assoc()['total'],
    'produtos_lancamento' => $conn->query("SELECT COUNT(*) as total FROM produtos WHERE lancamento = 1")->fetch_assoc()['total'],
    'produtos_vendidos' => $conn->query("SELECT COUNT(*) as total FROM produtos WHERE mais_vendido = 1")->fetch_assoc()['total'],
    'categorias' => $conn->query("SELECT COUNT(DISTINCT categoria) as total FROM produtos")->fetch_assoc()['total'],
    'valor_total_estoque' => $conn->query("SELECT SUM(preco) as total FROM produtos")->fetch_assoc()['total'] ?? 0,
    'produto_mais_caro' => $conn->query("SELECT MAX(preco) as max_preco FROM produtos")->fetch_assoc()['max_preco'] ?? 0,
    'produtos_promocao' => $conn->query("SELECT COUNT(*) as total FROM produtos WHERE preco_antigo IS NOT NULL")->fetch_assoc()['total']
];

// Produtos recentes para o painel
$produtos_recentes = $conn->query("SELECT * FROM produtos ORDER BY id DESC LIMIT 5");

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
        // Verificar se é uma imagem válida
        $image_info = getimagesize($file['tmp_name']);
        if ($image_info === false) {
            $erro_upload = "Arquivo não é uma imagem válida.";
        } else {
            // Determinar formato de saída baseado no suporte WebP
            $webp_supported = function_exists('imagewebp');
            $output_extension = $webp_supported ? 'webp' : $file_extension;
            
            $new_filename = 'produto_' . time() . '_' . uniqid() . '.' . $output_extension;
            $upload_path = $upload_dir . $new_filename;
            
            // Processar e salvar imagem
            $conversion_success = false;
            
            switch ($image_info['mime']) {
                case 'image/jpeg':
                    $source = imagecreatefromjpeg($file['tmp_name']);
                    break;
                case 'image/png':
                    $source = imagecreatefrompng($file['tmp_name']);
                    break;
                case 'image/webp':
                    if (function_exists('imagecreatefromwebp')) {
                        $source = imagecreatefromwebp($file['tmp_name']);
                    } else {
                        // Se não suporta WebP, copiar arquivo diretamente
                        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                            $conversion_success = true;
                        }
                        $source = false;
                    }
                    break;
                default:
                    $source = false;
            }
            
            if ($source !== false) {
                // Redimensionar se necessário
                $width = imagesx($source);
                $height = imagesy($source);
                $max_size = 800;
                
                if ($width > $max_size || $height > $max_size) {
                    $ratio = min($max_size / $width, $max_size / $height);
                    $new_width = intval($width * $ratio);
                    $new_height = intval($height * $ratio);
                    
                    $resized = imagecreatetruecolor($new_width, $new_height);
                    
                    // Preservar transparência para PNG
                    if ($image_info['mime'] === 'image/png') {
                        imagealphablending($resized, false);
                        imagesavealpha($resized, true);
                        $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                        imagefill($resized, 0, 0, $transparent);
                    }
                    
                    imagecopyresampled($resized, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                    imagedestroy($source);
                    $source = $resized;
                }
                
                // Salvar no formato apropriado
                if ($webp_supported && $output_extension === 'webp') {
                    $conversion_success = imagewebp($source, $upload_path, 85);
                } else {
                    switch ($file_extension) {
                        case 'jpg':
                        case 'jpeg':
                            $conversion_success = imagejpeg($source, $upload_path, 90);
                            break;
                        case 'png':
                            $conversion_success = imagepng($source, $upload_path, 8);
                            break;
                        default:
                            $conversion_success = false;
                    }
                }
                
                if ($source) {
                    imagedestroy($source);
                }
            }
            
            if ($conversion_success) {
                $imagem_temp = 'assets/imagens/produtos/' . $new_filename;
                $formato_usado = $webp_supported ? 'WebP' : strtoupper($file_extension);
                $sucesso_upload = "Imagem enviada e otimizada com sucesso! Formato: $formato_usado";
                
                // Retornar JSON para AJAX
                if (isset($_POST['ajax'])) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'message' => $sucesso_upload,
                        'image_path' => $imagem_temp,
                        'image_url' => '../' . $imagem_temp,
                        'format' => $formato_usado
                    ]);
                    exit();
                }
            } else {
                $erro_upload = "Erro ao processar a imagem.";
            }
        }
    } else {
        if ($file['error'] !== 0) {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $erro_upload = "Arquivo muito grande. Máximo 5MB.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $erro_upload = "Upload incompleto. Tente novamente.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $erro_upload = "Nenhum arquivo selecionado.";
                    break;
                default:
                    $erro_upload = "Erro no upload do arquivo.";
            }
        } else {
            $erro_upload = "Formato não suportado. Use JPG, PNG ou WebP.";
        }
    }
    
    if (isset($_POST['ajax']) && isset($erro_upload)) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => $erro_upload
        ]);
        exit();
    }
}

// Processar ações POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_FILES['imagem_upload'])) {
    $post_acao = $_POST['acao'] ?? '';
    
    switch ($post_acao) {
        case 'adicionar_produto':
            $nome = $_POST['nome'];
            $preco = floatval($_POST['preco']);
            $preco_antigo = !empty($_POST['preco_antigo']) ? floatval($_POST['preco_antigo']) : null;
            $categoria = $_POST['categoria'];
            $imagem = $_POST['imagem_final'] ?? $_POST['imagem_url'] ?? '';
            $descricao = $_POST['descricao'];
            $lancamento = isset($_POST['lancamento']) ? 1 : 0;
            $mais_vendido = isset($_POST['mais_vendido']) ? 1 : 0;

            $sql = "INSERT INTO produtos (nome, preco, preco_antigo, categoria, imagem, descricao, lancamento, mais_vendido) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdssssii", $nome, $preco, $preco_antigo, $categoria, $imagem, $descricao, $lancamento, $mais_vendido);
            
            if ($stmt->execute()) {
                $sucesso = "Produto adicionado com sucesso!";
                $acao = 'produtos'; // Redirecionar para lista
            } else {
                $erro = "Erro ao adicionar produto: " . $conn->error;
            }
            break;
            
        case 'editar_produto':
            $id = intval($_POST['id']);
            $nome = $_POST['nome'];
            $preco = floatval($_POST['preco']);
            $preco_antigo = !empty($_POST['preco_antigo']) ? floatval($_POST['preco_antigo']) : null;
            $categoria = $_POST['categoria'];
            $imagem = $_POST['imagem_final'] ?? $_POST['imagem_url'] ?? $_POST['imagem_atual'] ?? '';
            $descricao = $_POST['descricao'];
            $lancamento = isset($_POST['lancamento']) ? 1 : 0;
            $mais_vendido = isset($_POST['mais_vendido']) ? 1 : 0;

            $sql = "UPDATE produtos SET nome = ?, preco = ?, preco_antigo = ?, categoria = ?, imagem = ?, descricao = ?, lancamento = ?, mais_vendido = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdsssssii", $nome, $preco, $preco_antigo, $categoria, $imagem, $descricao, $lancamento, $mais_vendido, $id);
            
            if ($stmt->execute()) {
                $sucesso = "Produto atualizado com sucesso!";
                $acao = 'produtos';
            } else {
                $erro = "Erro ao atualizar produto: " . $conn->error;
            }
            break;
            
        case 'excluir_produto':
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $sucesso = "Produto excluído com sucesso!";
            } else {
                $erro = "Erro ao excluir produto: " . $conn->error;
            }
            break;
    }
}

// Buscar produtos para listagem
$busca = isset($_GET['busca']) ? '%' . $_GET['busca'] . '%' : '%';
$categoria_filtro = isset($_GET['categoria']) && $_GET['categoria'] !== '' ? $_GET['categoria'] : '';

$sql = "SELECT * FROM produtos WHERE nome LIKE ?";
$params = [$busca];
$types = "s";

if (!empty($categoria_filtro)) {
    $sql .= " AND categoria = ?";
    $params[] = $categoria_filtro;
    $types .= "s";
}

$sql .= " ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$produtos = $stmt->get_result();

// Buscar produto específico para edição
$produto_editar = null;
if ($acao === 'editar' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $produto_editar = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin AJBLOOM - <?= ucfirst($acao) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 280px;
            background: #2c3e50;
            color: white;
            padding: 2rem 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: #ecf0f1;
            font-size: 1.5rem;
        }
        .sidebar ul {
            list-style: none;
        }
        .sidebar li {
            margin: 0.5rem 0;
        }
        .sidebar a {
            display: block;
            padding: 1rem 2rem;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background: #34495e;
            color: white;
            border-left-color: #3498db;
            transform: translateX(5px);
        }
        .main-content {
            flex: 1;
            padding: 2rem;
            background: white;
            margin: 1rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow-y: auto;
            max-height: calc(100vh - 2rem);
        }
        .header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #ecf0f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 2rem;
        }
        .header-actions {
            display: flex;
            gap: 1rem;
        }
        
        /* ===== PAINEL APRIMORADO ===== */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            transition: transform 0.3s;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transition: all 0.3s;
        }
        .stat-card:hover::before {
            top: -25%;
            right: -25%;
        }
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .quick-actions {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        .quick-actions h3 {
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .action-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            cursor: pointer;
        }
        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .action-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        
        .recent-products {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
        }
        .recent-products h3 {
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }
        .recent-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: white;
            margin-bottom: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .recent-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 1rem;
        }
        .recent-info h4 {
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }
        .recent-info p {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* ===== PRODUTOS COM IMAGENS ===== */
        .produtos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
        }
        .produto-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s;
            position: relative;
        }
        .produto-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        .produto-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: #f8f9fa;
            border-bottom: 1px solid #e1e5e9;
            transition: transform 0.3s ease;
        }

        .produto-img:hover {
            transform: scale(1.05);
        }

        /* Adicionar loading state para imagens */
        .produto-img[src=""], .produto-img:not([src]) {
            background: #f8f9fa url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><circle cx="20" cy="20" r="18" fill="none" stroke="%23ddd" stroke-width="2"/><circle cx="20" cy="20" r="18" fill="none" stroke="%23007bff" stroke-width="2" stroke-linecap="round" stroke-dasharray="28" stroke-dashoffset="28"><animate attributeName="stroke-dashoffset" dur="1s" values="28;0" repeatCount="indefinite"/></circle></svg>') center center no-repeat;
            background-size: 40px 40px;
        }
        .produto-info {
            padding: 1.5rem;
        }
        .produto-info h3 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }
        .produto-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #666;
        }
        .produto-preco {
            font-size: 1.3rem;
            font-weight: bold;
            color: #27ae60;
            margin-bottom: 1rem;
        }
        .produto-badges {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-lancamento {
            background: #d4edda;
            color: #155724;
        }
        .badge-vendido {
            background: #fff3cd;
            color: #856404;
        }
        .produto-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        /* ===== UPLOAD DE IMAGEM APRIMORADO ===== */
        .image-upload-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .upload-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .tab-btn {
            padding: 1rem 2rem;
            border: 2px solid #e1e5e9;
            background: white;
            border-radius: 10px;
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
            border-radius: 15px;
            padding: 3rem;
            text-align: center;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-area:hover,
        .upload-area.dragover {
            border-color: #5a6fd8;
            background: #f8f9ff;
            transform: scale(1.02);
        }
        .upload-icon {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        .file-input {
            display: none;
        }
        .preview-container {
            border: 2px solid #e1e5e9;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            background: white;
            min-height: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 1rem;
        }
        .preview-img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 10px;
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
        
        /* ===== BOTÕES E FORMULÁRIOS ===== */
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            margin: 0.25rem;
            font-size: 0.9rem;
        }
        .btn-primary { background: #3498db; color: white; }
        .btn-success { background: #27ae60; color: white; }
        .btn-warning { background: #f39c12; color: white; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
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
            color: #2c3e50;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        .alert-success { 
            background: #d4edda; 
            color: #155724; 
            border-left: 4px solid #28a745;
        }
        .alert-error { 
            background: #f8d7da; 
            color: #721c24; 
            border-left: 4px solid #dc3545;
        }
        
        .search-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            align-items: center;
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
        }
        .search-bar input,
        .search-bar select {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        /* ===== RESPONSIVIDADE ===== */
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                padding: 1rem 0;
            }
            .sidebar ul {
                display: flex;
                overflow-x: auto;
            }
            .sidebar li {
                min-width: 120px;
            }
            .main-content {
                margin: 0.5rem;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            .produtos-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* ===== LOADING ===== */
        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Aprimorada -->
        <div class="sidebar">
            <h2><i class="fas fa-crown"></i> AJBLOOM ADMIN</h2>
            <ul>
                <li><a href="?acao=painel" class="<?= $acao === 'painel' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Painel
                </a></li>
                <li><a href="?acao=produtos" class="<?= $acao === 'produtos' ? 'active' : '' ?>">
                    <i class="fas fa-box"></i> Produtos
                </a></li>
                <li><a href="?acao=adicionar" class="<?= $acao === 'adicionar' ? 'active' : '' ?>">
                    <i class="fas fa-plus"></i> Adicionar
                </a></li>
                <li><a href="?acao=configuracoes" class="<?= $acao === 'configuracoes' ? 'active' : '' ?>">
                    <i class="fas fa-cog"></i> Configurações
                </a></li>
                <li><a href="../pages/index.php" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Ver Site
                </a></li>
            </ul>
        </div>

        <!-- Conteúdo Principal -->
        <div class="main-content">
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

            <?php switch($acao): 
                case 'painel': ?>
                    <div class="header">
                        <div>
                            <h1><i class="fas fa-tachometer-alt"></i> Painel Administrativo</h1>
                            <p>Bem-vindo ao sistema de gerenciamento AJBLOOM</p>
                        </div>
                        <div class="header-actions">
                            <span style="color: #666; font-size: 0.9rem;">
                                <i class="fas fa-clock"></i> <?= date('d/m/Y H:i') ?>
                            </span>
                        </div>
                    </div>

                    <!-- Estatísticas Aprimoradas -->
                    <div class="dashboard-grid">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-box"></i></div>
                            <div class="stat-number"><?= $stats['total_produtos'] ?></div>
                            <div class="stat-label">Total de Produtos</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-star"></i></div>
                            <div class="stat-number"><?= $stats['produtos_lancamento'] ?></div>
                            <div class="stat-label">Lançamentos</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-fire"></i></div>
                            <div class="stat-number"><?= $stats['produtos_vendidos'] ?></div>
                            <div class="stat-label">Mais Vendidos</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-percentage"></i></div>
                            <div class="stat-number"><?= $stats['produtos_promocao'] ?></div>
                            <div class="stat-label">Em Promoção</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                            <div class="stat-number">R$ <?= number_format($stats['valor_total_estoque'], 0, ',', '.') ?></div>
                            <div class="stat-label">Valor Total Estoque</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                            <div class="stat-number">R$ <?= number_format($stats['produto_mais_caro'], 2, ',', '.') ?></div>
                            <div class="stat-label">Produto Mais Caro</div>
                        </div>
                    </div>

                    <!-- Ações Rápidas -->
                    <div class="quick-actions">
                        <h3><i class="fas fa-bolt"></i> Ações Rápidas</h3>
                        <div class="actions-grid">
                            <div class="action-card" onclick="location.href='?acao=adicionar'">
                                <div class="action-icon"><i class="fas fa-plus-circle"></i></div>
                                <h4>Novo Produto</h4>
                                <p>Adicionar produto com upload</p>
                            </div>
                            <div class="action-card" onclick="location.href='?acao=produtos'">
                                <div class="action-icon"><i class="fas fa-list"></i></div>
                                <h4>Ver Produtos</h4>
                                <p>Gerenciar produtos existentes</p>
                            </div>
                            <div class="action-card" onclick="location.href='importar-produtos.php'">
                                <div class="action-icon"><i class="fas fa-upload"></i></div>
                                <h4>Importar CSV</h4>
                                <p>Importação em lote</p>
                            </div>
                            <div class="action-card" onclick="location.href='gerenciar-imagens.php'">
                                <div class="action-icon"><i class="fas fa-images"></i></div>
                                <h4>Gerenciar Imagens</h4>
                                <p>Organizar arquivos</p>
                            </div>
                            <div class="action-card" onclick="location.href='configuracoes.php'">
                                <div class="action-icon"><i class="fas fa-cog"></i></div>
                                <h4>Configurações</h4>
                                <p>Sistema e backup</p>
                            </div>
                            <div class="action-card" onclick="window.open('../pages/index.php', '_blank')">
                                <div class="action-icon"><i class="fas fa-eye"></i></div>
                                <h4>Visualizar Site</h4>
                                <p>Ver como cliente</p>
                            </div>
                        </div>
                    </div>

                    <!-- Produtos Recentes -->
                    <div class="recent-products">
                        <h3><i class="fas fa-clock"></i> Produtos Recentes</h3>
                        <?php while($produto = $produtos_recentes->fetch_assoc()): ?>
                        <div class="recent-item">
                            <img src="../<?= $produto['imagem'] ?: '/placeholder.svg?height=60&width=60&text=Sem+Imagem' ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                            <div class="recent-info">
                                <h4><?= htmlspecialchars($produto['nome']) ?></h4>
                                <p><?= ucfirst($produto['categoria']) ?> • R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                            </div>
                            <div style="margin-left: auto;">
                                <a href="?acao=editar&id=<?= $produto['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php break;

                case 'produtos': ?>
                    <div class="header">
                        <div>
                            <h1><i class="fas fa-box"></i> Gerenciar Produtos</h1>
                            <p>Lista de todos os produtos cadastrados com imagens</p>
                        </div>
                        <div class="header-actions">
                            <a href="?acao=adicionar" class="btn btn-success">
                                <i class="fas fa-plus"></i> Novo Produto
                            </a>
                        </div>
                    </div>

                    <form method="GET" class="search-bar">
                        <input type="hidden" name="acao" value="produtos">
                        <input type="text" name="busca" placeholder="Buscar produtos..." value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
                        <select name="categoria">
                            <option value="">Todas as categorias</option>
                            <option value="camisetas" <?= ($categoria_filtro == 'camisetas') ? 'selected' : '' ?>>Camisetas</option>
                            <option value="vestidos" <?= ($categoria_filtro == 'vestidos') ? 'selected' : '' ?>>Vestidos</option>
                            <option value="acessorios" <?= ($categoria_filtro == 'acessorios') ? 'selected' : '' ?>>Acessórios</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="?acao=produtos" class="btn btn-secondary">
                            <i class="fas fa-refresh"></i> Limpar
                        </a>
                    </form>

                    <div class="produtos-grid">
                        <?php 
        // Resetar o ponteiro do resultado se necessário
        if ($produtos->num_rows > 0) {
            $produtos->data_seek(0);
            while($produto = $produtos->fetch_assoc()): 
                // Determinar o caminho correto da imagem
                $imagem_src = '';
                if (!empty($produto['imagem'])) {
                    if (filter_var($produto['imagem'], FILTER_VALIDATE_URL)) {
                        // É uma URL externa
                        $imagem_src = $produto['imagem'];
                    } else {
                        // É um arquivo local
                        $imagem_path = '../' . ltrim($produto['imagem'], '/');
                        if (file_exists($imagem_path)) {
                            $imagem_src = $imagem_path;
                        } else {
                            // Tentar outros caminhos possíveis
                            $possible_paths = [
                                '../assets/imagens/produtos/' . basename($produto['imagem']),
                                '../' . $produto['imagem'],
                                $produto['imagem']
                            ];
                            foreach ($possible_paths as $path) {
                                if (file_exists($path)) {
                                    $imagem_src = $path;
                                    break;
                                }
                            }
                        }
                    }
                }
                
                // Se ainda não encontrou imagem, usar placeholder
                if (empty($imagem_src)) {
                    $imagem_src = 'data:image/svg+xml;base64,' . base64_encode('
                        <svg width="320" height="220" xmlns="http://www.w3.org/2000/svg">
                            <rect width="100%" height="100%" fill="#f8f9fa"/>
                            <text x="50%" y="50%" font-family="Arial" font-size="16" fill="#6c757d" text-anchor="middle" dy=".3em">
                                Sem Imagem
                            </text>
                        </svg>
                    ');
                }
        ?>
        <div class="produto-card">
            <img src="<?= htmlspecialchars($imagem_src) ?>" 
                 alt="<?= htmlspecialchars($produto['nome']) ?>" 
                 class="produto-img"
                 loading="lazy"
                 onerror="this.src='data:image/svg+xml;base64,<?= base64_encode('<svg width=\'320\' height=\'220\' xmlns=\'http://www.w3.org/2000/svg\'><rect width=\'100%\' height=\'100%\' fill=\'#f8f9fa\'/><text x=\'50%\' y=\'50%\' font-family=\'Arial\' font-size=\'16\' fill=\'#dc3545\' text-anchor=\'middle\' dy=\'.3em\'>Erro ao Carregar</text></svg>') ?>'">
            <div class="produto-info">
                <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                <div class="produto-meta">
                    <span><i class="fas fa-tag"></i> <?= ucfirst($produto['categoria']) ?></span>
                    <span><i class="fas fa-calendar"></i> ID: <?= $produto['id'] ?></span>
                </div>
                <div class="produto-preco">
                    <?php if($produto['preco_antigo']): ?>
                        <span style="text-decoration: line-through; color: #999; font-size: 0.9rem;">
                            R$ <?= number_format($produto['preco_antigo'], 2, ',', '.') ?>
                        </span>
                    <?php endif; ?>
                    R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                </div>
                
                <div class="produto-badges">
                    <?php if($produto['lancamento']): ?>
                        <span class="badge badge-lancamento">Lançamento</span>
                    <?php endif; ?>
                    <?php if($produto['mais_vendido']): ?>
                        <span class="badge badge-vendido">Mais Vendido</span>
                    <?php endif; ?>
                </div>
                
                <div class="produto-actions">
                    <a href="?acao=editar&id=<?= $produto['id'] ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="../pages/produto.php?id=<?= $produto['id'] ?>" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> Ver
                    </a>
                    <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                        <input type="hidden" name="acao" value="excluir_produto">
                        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php 
            endwhile;
        } else {
            echo '<div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                    <i class="fas fa-box-open" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                    <h3>Nenhum produto encontrado</h3>
                    <p>Não há produtos cadastrados ou que correspondam à sua busca.</p>
                    <a href="?acao=adicionar" class="btn btn-success" style="margin-top: 1rem;">
                        <i class="fas fa-plus"></i> Adicionar Primeiro Produto
                    </a>
                  </div>';
        }
        ?>
    </div>
    <?php break;

                case 'adicionar': ?>
                    <div class="header">
                        <div>
                            <h1><i class="fas fa-plus"></i> Adicionar Produto</h1>
                            <p>Cadastre um novo produto com upload de imagem</p>
                        </div>
                    </div>

                    <!-- Sistema de Upload Aprimorado -->
                    <div class="image-upload-section">
                        <h3><i class="fas fa-images"></i> Adicionar Imagem do Produto</h3>
                        
                        <div class="upload-tabs">
                            <button type="button" class="tab-btn active" onclick="switchTab('upload')">
                                <i class="fas fa-upload"></i> Upload de Arquivo
                            </button>
                            <button type="button" class="tab-btn" onclick="switchTab('url')">
                                <i class="fas fa-link"></i> URL Externa
                            </button>
                        </div>

                        <!-- Tab Upload -->
                        <div id="upload-tab" class="tab-content active">
                            <div class="upload-area" onclick="document.getElementById('file-input').click()">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <h4>Clique ou arraste uma imagem aqui</h4>
                                <p><strong>Formatos aceitos:</strong> JPG, PNG<?= function_exists('imagewebp') ? ', WebP' : '' ?></p>
                                <p><small>Máximo 5MB - Será otimizada automaticamente<?= function_exists('imagewebp') ? ' (WebP quando possível)' : '' ?></small></p>
                                <input type="file" id="file-input" accept=".jpg,.jpeg,.png,.webp" class="file-input">
                            </div>
                            
                            <div style="text-align: center; margin-top: 1rem;">
                                <button type="button" onclick="uploadImage()" class="btn btn-success">
                                    <i class="fas fa-upload"></i> Fazer Upload
                                </button>
                            </div>
                        </div>

                        <!-- Tab URL -->
                        <div id="url-tab" class="tab-content">
                            <div class="form-group">
                                <label for="imagem_url">
                                    <i class="fas fa-link"></i> URL da Imagem Externa
                                </label>
                                <input type="url" id="imagem_url" placeholder="https://exemplo.com/imagem.jpg">
                                <button type="button" class="btn btn-secondary" onclick="testarURL()" style="margin-top: 0.5rem;">
                                    <i class="fas fa-eye"></i> Testar URL
                                </button>
                            </div>
                        </div>

                        <!-- Preview da Imagem -->
                        <div class="preview-container" id="preview-container">
                            <i class="fas fa-image" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                            <p style="color: #666;">Nenhuma imagem selecionada</p>
                        </div>
                        
                        <div class="loading" id="upload-loading">
                            <div class="spinner"></div>
                            <p>Fazendo upload e otimizando imagem...</p>
                        </div>
                    </div>

                    <!-- Formulário do Produto -->
                    <form method="POST" id="form-produto">
                        <input type="hidden" name="acao" value="adicionar_produto">
                        <input type="hidden" name="imagem_final" id="imagem_final">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label><i class="fas fa-tag"></i> Nome do Produto *</label>
                                <input type="text" name="nome" required placeholder="Ex: Vestido Floral Elegante">
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-list"></i> Categoria *</label>
                                <select name="categoria" required>
                                    <option value="">Selecione uma categoria</option>
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
                                <textarea name="descricao" rows="4" placeholder="Descrição detalhada do produto..."></textarea>
                            </div>
                        </div>

                        <div style="margin: 1.5rem 0;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; margin-right: 2rem; margin-bottom: 1rem;">
                                <input type="checkbox" name="lancamento">
                                <i class="fas fa-star"></i> Produto em Lançamento
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="mais_vendido">
                                <i class="fas fa-fire"></i> Mais Vendido
                            </label>
                        </div>

                        <div style="text-align: center; margin-top: 2rem;">
                            <button type="submit" class="btn btn-success" style="padding: 1rem 3rem; font-size: 1.1rem;">
                                <i class="fas fa-save"></i> Salvar Produto
                            </button>
                            <a href="?acao=produtos" class="btn btn-secondary" style="padding: 1rem 3rem; font-size: 1.1rem;">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                        </div>
                    </form>
                    <?php break;

                case 'editar': ?>
                    <?php if ($produto_editar): ?>
                    <div class="header">
                        <div>
                            <h1><i class="fas fa-edit"></i> Editar Produto</h1>
                            <p>Editando: <?= htmlspecialchars($produto_editar['nome']) ?></p>
                        </div>
                    </div>

                    <!-- Sistema de Upload para Edição -->
                    <div class="image-upload-section">
                        <h3><i class="fas fa-images"></i> Alterar Imagem do Produto</h3>
                        
                        <div class="upload-tabs">
                            <button type="button" class="tab-btn active" onclick="switchTab('upload')">
                                <i class="fas fa-upload"></i> Upload de Arquivo
                            </button>
                            <button type="button" class="tab-btn" onclick="switchTab('url')">
                                <i class="fas fa-link"></i> URL Externa
                            </button>
                        </div>

                        <!-- Tab Upload -->
                        <div id="upload-tab" class="tab-content active">
                            <div class="upload-area" onclick="document.getElementById('file-input').click()">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <h4>Clique ou arraste uma nova imagem aqui</h4>
                                <p><strong>Formatos aceitos:</strong> JPG, PNG<?= function_exists('imagewebp') ? ', WebP' : '' ?></p>
                                <p><small>Deixe em branco para manter a imagem atual</small></p>
                                <input type="file" id="file-input" accept=".jpg,.jpeg,.png,.webp" class="file-input">
                            </div>
                            
                            <div style="text-align: center; margin-top: 1rem;">
                                <button type="button" onclick="uploadImage()" class="btn btn-success">
                                    <i class="fas fa-upload"></i> Fazer Upload
                                </button>
                            </div>
                        </div>

                        <!-- Tab URL -->
                        <div id="url-tab" class="tab-content">
                            <div class="form-group">
                                <label for="imagem_url">
                                    <i class="fas fa-link"></i> URL da Imagem Externa
                                </label>
                                <input type="url" id="imagem_url" value="<?= htmlspecialchars($produto_editar['imagem']) ?>" placeholder="https://exemplo.com/imagem.jpg">
                                <button type="button" class="btn btn-secondary" onclick="testarURL()" style="margin-top: 0.5rem;">
                                    <i class="fas fa-eye"></i> Testar URL
                                </button>
                            </div>
                        </div>

                        <!-- Preview da Imagem -->
                        <div class="preview-container" id="preview-container">
                            <?php if ($produto_editar['imagem']): ?>
                                <?php 
                                $imagem_path = $produto_editar['imagem'];
                                if (!str_starts_with($imagem_path, 'http')) {
                                    $imagem_path = '../' . $imagem_path;
                                }
                                ?>
                                <img src="<?= htmlspecialchars($imagem_path) ?>" alt="Preview" class="preview-img">
                                <div class="image-info">
                                    <strong>Imagem Atual:</strong> <?= basename($produto_editar['imagem']) ?><br>
                                    <small>Tipo: <?= str_starts_with($produto_editar['imagem'], 'http') ? 'URL Externa' : 'Arquivo Local' ?></small>
                                </div>
                            <?php else: ?>
                                <i class="fas fa-image" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                                <p style="color: #666;">Nenhuma imagem definida</p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="loading" id="upload-loading">
                            <div class="spinner"></div>
                            <p>Fazendo upload e otimizando imagem...</p>
                        </div>
                    </div>

                    <form method="POST" id="form-produto">
                        <input type="hidden" name="acao" value="editar_produto">
                        <input type="hidden" name="id" value="<?= $produto_editar['id'] ?>">
                        <input type="hidden" name="imagem_atual" value="<?= htmlspecialchars($produto_editar['imagem']) ?>">
                        <input type="hidden" name="imagem_final" id="imagem_final">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label><i class="fas fa-tag"></i> Nome do Produto *</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($produto_editar['nome']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-list"></i> Categoria *</label>
                                <select name="categoria" required>
                                    <option value="">Selecione uma categoria</option>
                                    <option value="camisetas" <?= $produto_editar['categoria'] === 'camisetas' ? 'selected' : '' ?>>Camisetas</option>
                                    <option value="vestidos" <?= $produto_editar['categoria'] === 'vestidos' ? 'selected' : '' ?>>Vestidos</option>
                                    <option value="acessorios" <?= $produto_editar['categoria'] === 'acessorios' ? 'selected' : '' ?>>Acessórios</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-dollar-sign"></i> Preço (R$) *</label>
                                <input type="number" name="preco" step="0.01" value="<?= $produto_editar['preco'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-percentage"></i> Preço Anterior (R$)</label>
                                <input type="number" name="preco_antigo" step="0.01" value="<?= $produto_editar['preco_antigo'] ?>">
                            </div>
                            <div class="form-group full-width">
                                <label><i class="fas fa-align-left"></i> Descrição</label>
                                <textarea name="descricao" rows="4"><?= htmlspecialchars($produto_editar['descricao']) ?></textarea>
                            </div>
                        </div>

                        <div style="margin: 1.5rem 0;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; margin-right: 2rem; margin-bottom: 1rem;">
                                <input type="checkbox" name="lancamento" <?= $produto_editar['lancamento'] ? 'checked' : '' ?>>
                                <i class="fas fa-star"></i> Produto em Lançamento
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="mais_vendido" <?= $produto_editar['mais_vendido'] ? 'checked' : '' ?>>
                                <i class="fas fa-fire"></i> Mais Vendido
                            </label>
                        </div>

                        <div style="text-align: center; margin-top: 2rem;">
                            <button type="submit" class="btn btn-success" style="padding: 1rem 3rem; font-size: 1.1rem;">
                                <i class="fas fa-save"></i> Salvar Alterações
                            </button>
                            <a href="?acao=produtos" class="btn btn-secondary" style="padding: 1rem 3rem; font-size: 1.1rem;">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                        </div>
                    </form>
                    <?php else: ?>
                        <div style="text-align: center; padding: 3rem;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: #f39c12; margin-bottom: 1rem;"></i>
                            <h3>Produto não encontrado</h3>
                            <p>O produto que você está tentando editar não existe.</p>
                            <a href="?acao=produtos" class="btn btn-primary" style="margin-top: 1rem;">
                                <i class="fas fa-arrow-left"></i> Voltar aos Produtos
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php break;

                case 'configuracoes': ?>
                    <div class="header">
                        <h1><i class="fas fa-cog"></i> Configurações</h1>
                        <p>Configurações gerais do sistema</p>
                    </div>

                    <div style="text-align: center; padding: 3rem;">
                        <i class="fas fa-tools" style="font-size: 4rem; color: #667eea; margin-bottom: 2rem;"></i>
                        <h3>🚧 Configurações Avançadas</h3>
                        <p style="margin-bottom: 2rem;">Acesse as configurações completas do sistema</p>
                        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                            <a href="configuracoes.php" class="btn btn-primary">
                                <i class="fas fa-cog"></i> Configurações Completas
                            </a>
                            <a href="importar-produtos.php" class="btn btn-success">
                                <i class="fas fa-upload"></i> Importar Produtos CSV
                            </a>
                            <a href="gerenciar-imagens.php" class="btn btn-warning">
                                <i class="fas fa-images"></i> Gerenciar Imagens
                            </a>
                        </div>
                    </div>
                    <?php break;

                default: ?>
                    <div style="text-align: center; padding: 3rem;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: #f39c12; margin-bottom: 1rem;"></i>
                        <h1>Página não encontrada</h1>
                        <p>A página solicitada não existe.</p>
                        <a href="?acao=painel" class="btn btn-primary" style="margin-top: 1rem;">
                            <i class="fas fa-home"></i> Voltar ao Painel
                        </a>
                    </div>
            <?php endswitch; ?>
        </div>
    </div>

    <script>
        // Variáveis globais
        let isDragging = false;
        let uploadInProgress = false;

        // Alternar entre tabs
        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            document.querySelector(`button[onclick="switchTab('${tab}')"]`).classList.add('active');
            document.getElementById(`${tab}-tab`).classList.add('active');
        }

        // Upload de imagem com AJAX
        function uploadImage() {
            if (uploadInProgress) {
                showAlert('error', 'Upload já em andamento. Aguarde...');
                return;
            }

            const fileInput = document.getElementById('file-input');
            const file = fileInput.files[0];
            
            if (!file) {
                showAlert('error', 'Selecione uma imagem primeiro!');
                return;
            }
            
            // Verificar tipo de arquivo
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                showAlert('error', 'Formato não suportado. Use JPG, PNG ou WebP.');
                return;
            }
            
            // Verificar tamanho (5MB)
            if (file.size > 5 * 1024 * 1024) {
                showAlert('error', 'Arquivo muito grande. Máximo 5MB.');
                return;
            }
            
            uploadInProgress = true;
            const formData = new FormData();
            formData.append('imagem_upload', file);
            formData.append('ajax', '1');
            
            document.getElementById('upload-loading').style.display = 'block';
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('upload-loading').style.display = 'none';
                uploadInProgress = false;
                
                if (data.success) {
                    document.getElementById('preview-container').innerHTML = `
                        <img src="${data.image_url}" alt="Preview" class="preview-img">
                        <div class="image-info">
                            <strong>✅ Upload realizado com sucesso!</strong><br>
                            <small>Arquivo: ${file.name} | Formato: ${data.format || 'Otimizado'}</small>
                        </div>
                    `;
                    document.getElementById('imagem_final').value = data.image_path;
                    showAlert('success', data.message);
                } else {
                    showAlert('error', data.message || 'Erro desconhecido no upload');
                }
            })
            .catch(error => {
                document.getElementById('upload-loading').style.display = 'none';
                uploadInProgress = false;
                console.error('Erro no upload:', error);
                showAlert('error', 'Erro ao fazer upload: ' + error.message);
            });
        }

        // Testar URL externa
        function testarURL() {
            const url = document.getElementById('imagem_url').value.trim();
            if (!url) {
                showAlert('error', 'Digite uma URL primeiro');
                return;
            }

            // Validar formato da URL
            try {
                new URL(url);
            } catch (e) {
                showAlert('error', 'URL inválida. Verifique o formato.');
                return;
            }

            const img = new Image();
            img.crossOrigin = 'anonymous';
            
            img.onload = function() {
                document.getElementById('preview-container').innerHTML = `
                    <img src="${url}" alt="Preview" class="preview-img">
                    <div class="image-info">
                        <strong>✅ URL válida!</strong><br>
                        <small>Dimensões: ${this.width}x${this.height}px</small>
                    </div>
                `;
                document.getElementById('imagem_final').value = url;
                showAlert('success', 'URL da imagem validada com sucesso!');
            };
            
            img.onerror = function() {
                showAlert('error', 'Não foi possível carregar a imagem. Verifique a URL e se a imagem permite acesso externo.');
            };
            
            img.src = url;
        }

        // Inicializar drag and drop
        function initDragAndDrop() {
            const uploadArea = document.querySelector('.upload-area');
            const fileInput = document.getElementById('file-input');

            if (!uploadArea || !fileInput) return;

            // Prevenir comportamento padrão do navegador
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            // Destacar área de drop
            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });

            // Lidar com arquivos soltos
            uploadArea.addEventListener('drop', handleDrop, false);

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight(e) {
                uploadArea.classList.add('dragover');
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    fileInput.files = files;
                    previewFile(files[0]);
                }
            }

            // Preview do arquivo selecionado
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    previewFile(e.target.files[0]);
                }
            });
        }

        // Preview de arquivo
        function previewFile(file) {
            // Verificar se é imagem
            if (!file.type.startsWith('image/')) {
                showAlert('error', 'Por favor, selecione apenas arquivos de imagem.');
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('preview-container').innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="preview-img">
                    <div class="image-info">
                        <strong>📁 Arquivo Selecionado:</strong> ${file.name}<br>
                        <small>Tamanho: ${(file.size / 1024 / 1024).toFixed(2)} MB | Tipo: ${file.type}</small><br>
                        <small>Clique em "Fazer Upload" para processar</small>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }

        // Mostrar alertas
        function showAlert(type, message) {
            // Remover alertas existentes
            document.querySelectorAll('.alert').forEach(alert => {
                if (!alert.classList.contains('alert-permanent')) {
                    alert.remove();
                }
            });

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'error'}`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> 
                ${message}
            `;
            
            const mainContent = document.querySelector('.main-content');
            mainContent.insertBefore(alertDiv, mainContent.firstChild);
            
            // Remover alerta após 5 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Validação do formulário
        function initFormValidation() {
            const form = document.getElementById('form-produto');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                const nome = this.querySelector('input[name="nome"]').value.trim();
                const categoria = this.querySelector('select[name="categoria"]').value;
                const preco = this.querySelector('input[name="preco"]').value;
                
                if (!nome || !categoria || !preco) {
                    e.preventDefault();
                    showAlert('error', 'Preencha todos os campos obrigatórios!');
                    return;
                }
                
                // Verificar se tem imagem (para adicionar)
                const isAdding = this.querySelector('input[name="acao"]').value === 'adicionar_produto';
                const imagemFinal = document.getElementById('imagem_final').value;
                const imagemUrl = document.getElementById('imagem_url')?.value;
                
                if (isAdding && !imagemFinal && !imagemUrl) {
                    e.preventDefault();
                    showAlert('error', 'Adicione uma imagem para o produto!');
                    return;
                }
                
                // Se tem URL mas não fez upload, usar URL
                if (!imagemFinal && imagemUrl) {
                    document.getElementById('imagem_final').value = imagemUrl;
                }
            });
        }

        // Auto-save do formulário
        function initAutoSave() {
            const form = document.getElementById('form-produto');
            if (!form) return;

            function autoSave() {
                const formData = new FormData(form);
                const data = {};
                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }
                localStorage.setItem('produto_draft', JSON.stringify(data));
            }

            // Auto-save a cada 30 segundos
            setInterval(autoSave, 30000);

            // Auto-save ao sair da página
            window.addEventListener('beforeunload', autoSave);
        }

        // Carregar draft salvo
        function loadDraft() {
            if (!window.location.search.includes('acao=adicionar')) return;

            const draft = localStorage.getItem('produto_draft');
            if (!draft) return;

            try {
                const data = JSON.parse(draft);
                Object.keys(data).forEach(key => {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input) {
                        if (input.type === 'checkbox') {
                            input.checked = data[key] === 'on';
                        } else {
                            input.value = data[key];
                        }
                    }
                });
            } catch (e) {
                console.error('Erro ao carregar draft:', e);
            }
        }

        // Atalhos de teclado
        function initKeyboardShortcuts() {
            document.addEventListener('keydown', function(e) {
                // Ctrl + S para salvar
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    const form = document.getElementById('form-produto');
                    if (form) {
                        form.submit();
                    }
                }
                
                // Ctrl + N para novo produto
                if (e.ctrlKey && e.key === 'n') {
                    e.preventDefault();
                    window.location.href = '?acao=adicionar';
                }

                // ESC para limpar preview
                if (e.key === 'Escape') {
                    const previewContainer = document.getElementById('preview-container');
                    if (previewContainer) {
                        previewContainer.innerHTML = `
                            <i class="fas fa-image" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                            <p style="color: #666;">Nenhuma imagem selecionada</p>
                        `;
                        document.getElementById('imagem_final').value = '';
                    }
                }
            });
        }

        // Tratamento de erro de imagens
        function initImageErrorHandling() {
            document.addEventListener('DOMContentLoaded', function() {
                const images = document.querySelectorAll('.produto-img');
                images.forEach(img => {
                    img.addEventListener('error', function() {
                        this.style.background = '#f8f9fa';
                        this.style.display = 'flex';
                        this.style.alignItems = 'center';
                        this.style.justifyContent = 'center';
                        this.style.color = '#6c757d';
                        this.style.fontSize = '14px';
                        this.innerHTML = '<div style="text-align: center;"><i class="fas fa-image" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>Imagem não encontrada</div>';
                    });
                    
                    img.addEventListener('load', function() {
                        this.style.opacity = '1';
                    });
                    
                    // Iniciar com opacity 0 para transição suave
                    img.style.opacity = '0';
                    img.style.transition = 'opacity 0.3s ease';
                });
            });
        }

        // Inicialização principal
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🌸 AJBLOOM Admin - Sistema inicializado!');
            
            // Inicializar todos os componentes
            initDragAndDrop();
            initFormValidation();
            initAutoSave();
            initKeyboardShortcuts();
            initImageErrorHandling();
            loadDraft();
            
            console.log('✅ Drag and drop ativo');
            console.log('✅ Validação de formulário ativa');
            console.log('✅ Auto-save configurado');
            console.log('✅ Atalhos de teclado ativos');
            console.log('✅ Sistema pronto para uso!');
        });

        // Limpar draft quando produto for salvo
        if (window.location.search.includes('sucesso=1')) {
            localStorage.removeItem('produto_draft');
        }
    </script>
</body>
</html>
