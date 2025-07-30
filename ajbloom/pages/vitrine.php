<?php
session_start();
include("../backend/conexao.php");

// Parâmetros da busca
$busca = isset($_GET["busca"]) ? '%' . $_GET["busca"] . '%' : '%';
$categoria = isset($_GET["categoria"]) ? $_GET["categoria"] : '';
$preco_min = isset($_GET["preco_min"]) ? floatval($_GET["preco_min"]) : 0;
$preco_max = isset($_GET["preco_max"]) ? floatval($_GET["preco_max"]) : 1000;
$lancamento = isset($_GET["lancamento"]) ? 1 : 0;
$mais_vendido = isset($_GET["mais_vendido"]) ? 1 : 0;

// Construção da query SQL
$sql = "SELECT id, nome, preco, preco_antigo, categoria, imagem, lancamento, mais_vendido 
        FROM produtos 
        WHERE nome LIKE ? 
        AND preco BETWEEN ? AND ?";
$params = [$busca, $preco_min, $preco_max];
$types = "sdd";

// Filtros adicionais
if (!empty($categoria)) {
    $sql .= " AND categoria = ?";
    $params[] = $categoria;
    $types .= "s";
}

if ($lancamento) {
    $sql .= " AND lancamento = 1";
}

if ($mais_vendido) {
    $sql .= " AND mais_vendido = 1";
}

// Execução da query
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$resultado = $stmt->get_result();

// ✅ CORRIGIDO: Função para corrigir caminho da imagem
function corrigirCaminhoImagem($imagem) {
    if (empty($imagem)) {
        return '/placeholder.svg?height=400&width=400&text=Sem+Imagem';
    }
    
    // Se é URL externa, retorna como está
    if (filter_var($imagem, FILTER_VALIDATE_URL)) {
        return $imagem;
    }
    
    // Remove barras duplas e normaliza o caminho
    $imagem = ltrim($imagem, '/');
    
    // ✅ CORRIGIDO: Primeiro, verificar se a imagem existe nos uploads do admin
    $caminho_admin = '../administrador/uploads/' . basename($imagem);
    if (file_exists($caminho_admin)) {
        return $caminho_admin;
    }
    
    // Se já começa com assets/, adiciona apenas ../
    if (strpos($imagem, 'assets/') === 0) {
        $caminho_assets = '../' . $imagem;
        if (file_exists($caminho_assets)) {
            return $caminho_assets;
        }
    }
    
    // Se não tem assets/, assume que está na pasta de produtos
    if (!strpos($imagem, 'assets/')) {
        $caminho_produtos = '../assets/imagens/produtos/' . basename($imagem);
        if (file_exists($caminho_produtos)) {
            return $caminho_produtos;
        }
    }
    
    // Caso padrão - retorna o caminho original
    return '../' . $imagem;
}

// Título da categoria
$titulo_categoria = "Todos os Produtos";
if (!empty($categoria)) {
    $titulo_categoria = ucfirst($categoria);
}

$base = '/';?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Vitrine - AJBLOOM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Explore todos os produtos da AJBLOOM. Encontre roupas femininas com estilo e personalidade.">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Raleway&family=Montserrat:wght@400;600&family=Dancing+Script&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/index.css?v=5.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.6.1/nouislider.min.css">
  <style>
    /* ✅ CORRIGIDO: Body com padding para o header fixo */
    body {
      background-color: #ffffff;
      font-family: 'Raleway', sans-serif;
      margin: 0;
      padding-top: 100px; /* Espaço para o header fixo */
      color: #333;
    }

    /* ✅ CORRIGIDO: Header da vitrine com centralização vertical perfeita */
    .vitrine-header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: clamp(90px, 18vw, 110px);
      display: flex;
      align-items: center; /* ✅ CORRIGIDO: Centralização vertical perfeita */
      justify-content: center;
      padding: 0 var(--container-padding, 2rem);
      background: white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      z-index: 1000;
      transition: all 0.3s ease;
    }

    .vitrine-topo {
      display: flex;
      align-items: center; /* ✅ CORRIGIDO: Centralização vertical dos elementos internos */
      justify-content: center;
      width: 100%;
      max-width: 1200px;
      height: 100%; /* ✅ ADICIONADO: Ocupar toda altura do header */
      gap: 1rem;
      position: relative;
    }

    /* ✅ CORRIGIDO: Logo centralizada vertical e horizontalmente */
    .vitrine-logo {
      height: clamp(60px, 12vw, 100px); /* ✅ AJUSTADO: Altura menor para melhor proporção */
      display: flex;
      align-items: center;
      justify-content: center; /* ✅ ADICIONADO: Centralização horizontal da imagem */
      flex-shrink: 0;
      z-index: 10;
    }

    .vitrine-logo img {
      max-height: 100%;
      width: auto;
      object-fit: contain;
    }

    /* ✅ CORRIGIDO: Menu desktop centralizado verticalmente */
    .vitrine-nav-desktop {
      position: absolute;
      right: 0;
      top: 50%; /* ✅ ADICIONADO: Posicionamento vertical central */
      transform: translateY(-50%); /* ✅ ADICIONADO: Centralização vertical perfeita */
      display: none;
    }

    .vitrine-nav-desktop ul {
      display: flex;
      list-style: none;
      gap: 2rem;
      margin: 0;
      padding: 0;
    }

    .vitrine-nav-desktop a {
      font-size: 1rem;
      color: #333;
      padding: 0.5rem 1rem;
      position: relative;
      transition: all 0.3s ease;
      border-radius: 8px;
      font-weight: 500;
      text-decoration: none;
    }

    .vitrine-nav-desktop a::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0%;
      height: 2px;
      background-color: #90b7eb;
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    .vitrine-nav-desktop a:hover::after {
      width: 80%;
    }

    .vitrine-nav-desktop a:hover {
      color: #90b7eb;
      background: rgba(144, 183, 235, 0.1);
    }

    /* ✅ CORRIGIDO: Menu hambúrguer centralizado verticalmente */
    .vitrine-menu-toggle {
      position: absolute;
      left: 0; /* ✅ CORRIGIDO: Posicionado à esquerda */
      top: 50%; /* ✅ ADICIONADO: Posicionamento vertical central */
      transform: translateY(-50%); /* ✅ ADICIONADO: Centralização vertical perfeita */
      display: flex;
      flex-direction: column;
      justify-content: space-around;
      width: 32px;
      height: 32px;
      background: transparent;
      border: none;
      cursor: pointer;
      padding: 4px;
      z-index: 1100;
      transition: all 0.3s ease;
      flex-shrink: 0;
    }

    .vitrine-menu-toggle:hover {
      transform: translateY(-50%) scale(1.1); /* ✅ CORRIGIDO: Manter centralização no hover */
    }

    .vitrine-menu-toggle .bar {
      width: 100%;
      height: 3px;
      background-color: #90b7eb;
      border-radius: 3px;
      transition: all 0.3s ease;
    }

    .vitrine-menu-toggle.open .bar:nth-child(1) {
      transform: rotate(45deg) translate(6px, 6px);
    }

    .vitrine-menu-toggle.open .bar:nth-child(2) {
      opacity: 0;
    }

    .vitrine-menu-toggle.open .bar:nth-child(3) {
      transform: rotate(-45deg) translate(6px, -6px);
    }

    /* ✅ CORRIGIDO: Menu lateral com overlay apenas na parte direita */
    .vitrine-menu-lateral {
      position: fixed;
      top: 0;
      left: -100%;
      width: min(300px, 80vw);
      height: 100vh;
      background: white; /* ✅ CORRIGIDO: Fundo branco para legibilidade */
      box-shadow: 2px 0 20px rgba(0, 0, 0, 0.3); /* ✅ AUMENTADO: Sombra mais forte */
      z-index: 3000; /* ✅ AUMENTADO: z-index muito alto */
      transition: left 0.3s ease;
      padding: 2rem 0;
      overflow-y: auto;
    }

    .vitrine-menu-lateral.open {
      left: 0;
    }

    .vitrine-close-menu {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: none;
      border: none;
      font-size: 2rem;
      cursor: pointer;
      color: #90b7eb;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: all 0.3s ease;
      z-index: 3001; /* ✅ AUMENTADO: z-index para o botão fechar */
    }

    .vitrine-close-menu:hover {
      background: rgba(144, 183, 235, 0.1);
      transform: rotate(90deg);
    }

    .vitrine-menu-lista {
      list-style: none;
      padding: 2rem 0;
      margin: 0;
    }

    .vitrine-menu-lista li {
      margin: 0;
    }

    .vitrine-menu-lista a {
      display: block;
      padding: 1rem 2rem;
      color: #333;
      font-size: 1.1rem;
      font-weight: 500;
      transition: all 0.3s ease;
      border-left: 4px solid transparent;
      text-decoration: none;
      position: relative;
      z-index: 3002; /* ✅ AUMENTADO: z-index para os links */
    }

    .vitrine-menu-lista a:hover {
      background: rgba(144, 183, 235, 0.1);
      border-left-color: #90b7eb;
      color: #90b7eb;
      transform: translateX(8px);
    }

    /* ✅ CORRIGIDO: Overlay que cobre apenas a parte direita */
    .vitrine-overlay {
      position: fixed;
      top: 0;
      left: min(300px, 80vw); /* ✅ CORRIGIDO: Começa onde o menu termina */
      width: calc(100% - min(300px, 80vw)); /* ✅ CORRIGIDO: Largura apenas da parte direita */
      height: 100%;
      background: rgba(0, 0, 0, 0.6); /* ✅ CORRIGIDO: Overlay mais escuro */
      z-index: 2000; /* ✅ CORRIGIDO: z-index menor que o menu */
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .vitrine-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* ✅ RESPONSIVIDADE DO HEADER */
    @media (min-width: 1025px) {
      .vitrine-nav-desktop {
        display: block;
      }

      .vitrine-menu-toggle {
        display: none;
      }
    }

    @media (min-width: 768px) and (max-width: 1024px) {
      .vitrine-nav-desktop {
        display: block;
      }

      .vitrine-menu-toggle {
        display: none;
      }
    }

    @media (max-width: 767px) {
      .vitrine-header {
        height: clamp(80px, 16vw, 120px);
        padding: 0 1rem;
      }

      .vitrine-logo {
        height: clamp(50px, 10vw, 70px); /* ✅ AJUSTADO: Altura menor no mobile */
      }

      .vitrine-nav-desktop {
        display: none;
      }

      .vitrine-menu-toggle {
        width: 28px;
        height: 28px;
      }

      .vitrine-menu-toggle:hover {
        transform: translateY(-50%) scale(1.1); /* ✅ MANTIDO: Centralização no hover mobile */
      }

      body {
        padding-top: 140px;
      }
    }

    /* ✅ MELHORADO: Container principal */
    .vitrine-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
    }

    /* ✅ NOVO: Estilização melhorada dos filtros */
    .filtros-section {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 3rem;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(144, 183, 235, 0.1);
    }

    .filtros-titulo {
      font-size: 1.5rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 1.5rem;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .filtros-titulo i {
      color: #90b7eb;
    }

    .filtros-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .filtro-group {
      background: white;
      padding: 1.5rem;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }

    .filtro-group:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .filtro-group label {
      display: block;
      margin-bottom: 0.75rem;
      font-weight: 600;
      color: #333;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .filtro-group label i {
      color: #90b7eb;
      font-size: 1rem;
    }

    .filtros input, .filtros select {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 2px solid #e1e5e9;
      border-radius: 10px;
      font-size: 1rem;
      background: white;
      transition: all 0.3s ease;
      font-family: 'Raleway', sans-serif;
    }

    .filtros input:focus, .filtros select:focus {
      outline: none;
      border-color: #90b7eb;
      box-shadow: 0 0 0 3px rgba(144, 183, 235, 0.1);
    }

    .filtros input::placeholder {
      color: #999;
    }

    /* ✅ MELHORADO: Slider de preço */
    .price-range-container {
      background: white;
      padding: 1.5rem;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      grid-column: 1 / -1;
    }

    .price-range-header {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1rem;
      font-weight: 600;
      color: #333;
    }

    .price-range-header i {
      color: #90b7eb;
    }

    #price-slider {
      margin: 1.5rem 0;
    }

    .noUi-target {
      background: #e1e5e9;
      border: none;
      box-shadow: none;
      height: 6px;
      border-radius: 3px;
    }

    .noUi-connect {
      background: linear-gradient(135deg, #90b7eb, #a8c5f0);
    }

    .noUi-handle {
      width: 22px;
      height: 22px;
      top: -8px;
      right: -11px;
      border-radius: 50%;
      background: white;
      border: 3px solid #90b7eb;
      box-shadow: 0 4px 15px rgba(144, 183, 235, 0.3);
      cursor: pointer;
    }

    .noUi-handle:before, .noUi-handle:after {
      display: none;
    }

    .price-range-values {
      display: flex;
      justify-content: space-between;
      margin-top: 1rem;
      font-weight: 600;
      color: #90b7eb;
    }

    /* ✅ MELHORADO: Checkboxes */
    .checkbox-section {
      background: white;
      padding: 1.5rem;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      grid-column: 1 / -1;
    }

    .checkbox-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
    }

    .checkbox-label {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-weight: 500;
      cursor: pointer;
      padding: 0.75rem;
      border-radius: 10px;
      transition: all 0.3s ease;
    }

    .checkbox-label:hover {
      background: rgba(144, 183, 235, 0.1);
    }

    .checkbox-label input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: #90b7eb;
    }

    /* ✅ MELHORADO: Botão de filtrar */
    .btn-filtrar {
      background: linear-gradient(135deg, #90b7eb, #a8c5f0);
      color: white;
      padding: 1rem 2rem;
      border: none;
      border-radius: 15px;
      font-size: 1.1rem;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      box-shadow: 0 4px 15px rgba(144, 183, 235, 0.3);
    }

    .btn-filtrar:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(144, 183, 235, 0.5);
    }

    /* ✅ MELHORADO: Título da categoria */
    .category-highlight {
      text-align: center;
      margin: 2rem 0 3rem;
      font-size: 2.5rem;
      color: #333;
      font-weight: 700;
      text-transform: capitalize;
      position: relative;
    }

    .category-highlight::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background: linear-gradient(135deg, #90b7eb, #a8c5f0);
      border-radius: 2px;
    }

    /* ✅ MELHORADO: Grid de produtos */
    .produtos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      margin-bottom: 3rem;
    }

    .produto-card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      position: relative;
      border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .produto-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .produto-img {
      width: 100%;
      height: 280px;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .produto-card:hover .produto-img {
      transform: scale(1.05);
    }

    .produto-info {
      padding: 1.5rem;
    }

    .produto-info h3 {
      font-size: 1.2rem;
      margin-bottom: 0.75rem;
      color: #333;
      font-weight: 600;
      line-height: 1.3;
    }

    .produto-info a {
      text-decoration: none;
      color: inherit;
    }

    .produto-info a:hover h3 {
      color: #90b7eb;
    }

    .preco {
      font-size: 1.3rem;
      font-weight: 700;
      color: #e74c3c;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .preco .antigo {
      text-decoration: line-through;
      color: #999;
      font-size: 1rem;
      font-weight: 400;
    }

    .desconto-badge {
      background: #e74c3c;
      color: white;
      padding: 0.25rem 0.5rem;
      border-radius: 12px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    /* ✅ MELHORADO: Mensagem quando não há produtos */
    .sem-produtos {
      text-align: center;
      padding: 4rem 2rem;
      color: #666;
      grid-column: 1 / -1;
    }

    .sem-produtos i {
      font-size: 4rem;
      color: #90b7eb;
      margin-bottom: 1rem;
    }

    .sem-produtos h3 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
      color: #333;
    }

    .sem-produtos p {
      font-size: 1.1rem;
      margin-bottom: 2rem;
    }

    .btn-voltar {
      background: linear-gradient(135deg, #90b7eb, #a8c5f0);
      color: white;
      padding: 1rem 2rem;
      border: none;
      border-radius: 15px;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.3s ease;
    }

    .btn-voltar:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(144, 183, 235, 0.4);
    }

    /* ✅ RESPONSIVIDADE */
    @media (max-width: 768px) {
      body {
        padding-top: 140px;
      }

      .vitrine-container {
        padding: 1rem;
      }

      .filtros-section {
        padding: 1.5rem;
      }

      .filtros-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
      }

      .checkbox-grid {
        grid-template-columns: 1fr;
      }

      .category-highlight {
        font-size: 2rem;
      }

      .produtos-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
      }

      .produto-img {
        height: 220px;
      }
    }

    @media (max-width: 480px) {
      body {
        padding-top: 120px;
      }

      .vitrine-container {
        padding: 0.5rem;
      }

      .filtros-section {
        padding: 1rem;
        margin-bottom: 2rem;
      }

      .category-highlight {
        font-size: 1.8rem;
      }

      .produtos-grid {
        grid-template-columns: 1fr;
      }

      .produto-img {
        height: 200px;
      }
    }
  </style>
</head>
<body>

<!-- ✅ HEADER CORRIGIDO COM CENTRALIZAÇÃO VERTICAL PERFEITA -->
<header class="vitrine-header">
  <div class="vitrine-topo">
    <!-- Menu hambúrguer (mobile) -->
    <button id="vitrine-menu-toggle" class="vitrine-menu-toggle" aria-label="Abrir menu">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </button>

    <!-- Logotipo centralizado -->
    <div class="vitrine-logo">
      <img src="../assets/imagens/logo/logo.webp" alt="AJbloom" />
    </div> 

    <!-- Menu desktop (direita) -->
    <nav class="vitrine-nav-desktop" aria-label="Menu principal">
      <ul>
        <li><a href="index.php">Home</a></li>
      </ul>
    </nav>
  </div>

  <!-- Menu lateral -->
  <nav id="vitrine-side-menu" class="vitrine-menu-lateral" aria-hidden="true">
    <button id="vitrine-close-menu" class="vitrine-close-menu" aria-label="Fechar menu">×</button>
    <ul class="vitrine-menu-lista">
      <li><a href="index.php">Home</a></li>
      <li><a href="https://wa.me/5541999999999?text=Olá%2C+preciso+de+ajuda+com+os+produtos+da+AJBLOOM%21" target="_blank">Ajuda / Suporte</a></li>
    </ul>
  </nav>
</header>

<div id="vitrine-overlay" class="vitrine-overlay"></div>

<main class="vitrine-container">
  <!-- ✅ MELHORADO: Seção de filtros -->
  <section class="filtros-section">
    <h2 class="filtros-titulo">
      <i class="fas fa-filter"></i>
      Filtrar Produtos
    </h2>
    
    <form method="GET" class="filtros">
      <div class="filtros-grid">
        <div class="filtro-group">
          <label for="busca">
            <i class="fas fa-search"></i>
            Buscar produto
          </label>
          <input type="text" name="busca" id="busca" placeholder="Digite o nome do produto..." value="<?php echo htmlspecialchars($_GET['busca'] ?? '') ?>">
        </div>
        
        <div class="filtro-group">
          <label for="categoria">
            <i class="fas fa-tags"></i>
            Categoria
          </label>
          <select name="categoria" id="categoria">
            <option value="">Todas as categorias</option>
            <option value="camisetas" <?php if($categoria=="camisetas") echo "selected"; ?>>Camisetas</option>
            <option value="vestidos" <?php if($categoria=="vestidos") echo "selected"; ?>>Vestidos</option>
            <option value="acessorios" <?php if($categoria=="acessorios") echo "selected"; ?>>Acessórios</option>
            <option value="calcas" <?php if($categoria=="calcas") echo "selected"; ?>>Calças</option>
          </select>
        </div>
      </div>
      
      <div class="price-range-container">
        <div class="price-range-header">
          <i class="fas fa-dollar-sign"></i>
          Faixa de preço
        </div>
        <div id="price-slider"></div>
        <div class="price-range-values">
          <span id="price-min">R$ <?php echo number_format($preco_min, 2, ',', '.'); ?></span>
          <span id="price-max">R$ <?php echo number_format($preco_max, 2, ',', '.'); ?></span>
        </div>
        <input type="hidden" name="preco_min" id="preco_min" value="<?php echo $preco_min; ?>">
        <input type="hidden" name="preco_max" id="preco_max" value="<?php echo $preco_max; ?>">
      </div>
      
      <div class="checkbox-section">
        <div class="checkbox-grid">
          <label class="checkbox-label">
            <input type="checkbox" name="lancamento" <?php if(isset($_GET['lancamento'])) echo 'checked'; ?>>
            <i class="fas fa-star"></i>
            Mostrar lançamentos
          </label>
          
          <label class="checkbox-label">
            <input type="checkbox" name="mais_vendido" <?php if(isset($_GET['mais_vendido'])) echo 'checked'; ?>>
            <i class="fas fa-fire"></i>
            Mais vendidos
          </label>
        </div>
      </div>
      
      <button type="submit" class="btn-filtrar">
        <i class="fas fa-search"></i>
        Filtrar Produtos
      </button>
    </form>
  </section>

  <h2 class="category-highlight"><?php echo htmlspecialchars($titulo_categoria); ?></h2>

  <!-- ✅ MELHORADO: Grid de produtos sem tags -->
  <div class="produtos-grid">
    <?php if ($resultado->num_rows > 0): ?>
      <?php while($produto = $resultado->fetch_assoc()): ?>
        <div class="produto-card">
          <a href="produto.php?id=<?php echo $produto['id']; ?>">
            <img src="<?php echo corrigirCaminhoImagem($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" class="produto-img" loading="lazy" onerror="this.src='/placeholder.svg?height=280&width=280&text=Imagem+não+encontrada';">
            <div class="produto-info">
              <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
              <div class="preco">
                <?php if (!empty($produto['preco_antigo'])): ?>
                  <span class="antigo">R$ <?php echo number_format($produto['preco_antigo'], 2, ',', '.'); ?></span>
                  <?php 
                  $desconto = (($produto['preco_antigo'] - $produto['preco']) / $produto['preco_antigo']) * 100;
                  ?>
                  <span class="desconto-badge"><?php echo round($desconto); ?>% OFF</span>
                <?php endif; ?>
                <span>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
              </div>
            </div>
          </a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="sem-produtos">
        <i class="fas fa-search"></i>
        <h3>Nenhum produto encontrado</h3>
        <p>Não encontramos produtos que correspondam aos seus filtros.</p>
        <a href="vitrine.php" class="btn-voltar">
          <i class="fas fa-arrow-left"></i>
          Ver todos os produtos
        </a>
      </div>
    <?php endif; ?>
  </div>
</main>

<!-- ✅ RODAPÉ MIGRADO DA INDEX.PHP -->
<footer>
  <div class="container">
    <div class="footer-content">
      <div class="footer-column">
        <p class="creditos-site">
          <h2> Criado por </h2>
          <a href="https://www.instagram.com/luantech.oficial" target="_blank">Luan Raio</a> 
          <a href="https://raioamorim99.github.io/Luan-Raio/" target="_blank">Portfólio</a>
        </p>
      </div>

      <div class="footer-column">
        <h3>Links Rápidos</h3>
        <a href="index.php" class="home-link">Home</a>
        <a href="vitrine.php">Produtos</a>
        <a href="sobre.php">Sobre Nós</a>
      </div>

      <div class="footer-column">
        <h3>Informações</h3>
        <a href="https://wa.me/5541999999999?text=Ol%C3%A1%2C+gostaria+de+saber+mais+sobre+os+produtos+da+AJBLOOM%21">Trocas de produtos  </a>
        <a href="politica_ajbloom/termos.php">Termos de Serviço</a>
      </div>

      <div class="footer-column">
        <h3>Pagamentos</h3>
        <div class="payment-icons">
          <img src="https://img.icons8.com/color/48/visa.png" alt="Visa" loading="lazy" />
          <img src="https://img.icons8.com/color/48/mastercard.png" alt="Mastercard" loading="lazy" />
          <img src="https://img.icons8.com/color/48/pix.png" alt="Pix" loading="lazy" />
        </div>
        <div class="footer-social">
          <a href="https://www.instagram.com/ajbloom_/"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-tiktok"></i></a>
        </div>
      </div>

      <div class="footer-column">
        <h3>Newsletter</h3>
        <p>Assine para receber novidades e ofertas exclusivas!</p>
        <form class="footer-newsletter">
          <input type="email" placeholder="Seu e-mail" required>
          <button type="submit" class="btn">Assinar</button>
        </form>
      </div>
      
      <div class="footer-bottom">
        <p>© 2025 AJBLOOM.shop – Todos os direitos reservados.</p>
      </div>
    </div>
  </div>
</footer>

<!-- ✅ BOTÃO WHATSAPP -->
<a href="https://wa.me/5541999999999?text=Ol%C3%A1%2C+gostaria+de+saber+mais+sobre+os+produtos+da+AJBLOOM%21" class="whatsapp-btn" target="_blank" aria-label="Fale conosco pelo WhatsApp">
  <img src="https://img.icons8.com/ios-filled/50/ffffff/whatsapp--v1.png" alt="WhatsApp" loading="lazy" />
</a>

<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.6.1/nouislider.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  console.log('🌸 Vitrine AJBLOOM carregada com sucesso!');

  // ✅ CORRIGIDO: Menu hambúrguer com overlay apenas na parte direita
  const menuToggle = document.getElementById('vitrine-menu-toggle');
  const sideMenu = document.getElementById('vitrine-side-menu');
  const overlay = document.getElementById('vitrine-overlay');
  const closeMenuBtn = document.getElementById('vitrine-close-menu');

  function abrirMenu() {
    console.log('🍔 Abrindo menu lateral da vitrine');
    sideMenu.classList.add('open');
    menuToggle.classList.add('open');
    overlay.classList.add('active');
    sideMenu.setAttribute('aria-hidden', 'false');
    menuToggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    
    // ✅ CORRIGIDO: Garantir que o menu esteja acima do overlay
    sideMenu.style.zIndex = '3000';
    overlay.style.zIndex = '2000';
  }

  function fecharMenu() {
    console.log('❌ Fechando menu lateral da vitrine');
    sideMenu.classList.remove('open');
    menuToggle.classList.remove('open');
    overlay.classList.remove('active');
    sideMenu.setAttribute('aria-hidden', 'true');
    menuToggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  // Event listeners do menu
  if (menuToggle) {
    menuToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      console.log('🍔 Menu toggle clicado');
      if (sideMenu.classList.contains('open')) {
        fecharMenu();
      } else {
        abrirMenu();
      }
    });
    console.log('✅ Event listener do menu hambúrguer da vitrine configurado');
  } else {
    console.log('❌ Menu toggle da vitrine não encontrado');
  }

  if (closeMenuBtn) {
    closeMenuBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      fecharMenu();
    });
    console.log('✅ Event listener do botão fechar menu da vitrine configurado');
  }

  if (overlay) {
    overlay.addEventListener('click', fecharMenu);
    console.log('✅ Event listener do overlay da vitrine configurado');
  }

  // ✅ CORRIGIDO: Fechar menu ao clicar em links
  sideMenu?.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', (e) => {
      // ✅ Não prevenir o comportamento padrão, apenas fechar o menu
      fecharMenu();
    });
  });

  // Fechar menu com ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && sideMenu?.classList.contains('open')) {
      fecharMenu();
    }
  });

  // ✅ Inicialização do slider de preço
  const priceSlider = document.getElementById('price-slider');
  const priceMin = document.getElementById('preco_min');
  const priceMax = document.getElementById('preco_max');
  const priceMinDisplay = document.getElementById('price-min');
  const priceMaxDisplay = document.getElementById('price-max');

  if (priceSlider) {
    noUiSlider.create(priceSlider, {
      start: [<?php echo $preco_min; ?>, <?php echo $preco_max; ?>],
      connect: true,
      range: {
        'min': 0,
        'max': 1000
      },
      step: 10,
      format: {
        to: function(value) {
          return Math.round(value);
        },
        from: function(value) {
          return Number(value);
        }
      }
    });

    // Atualiza os valores quando o slider é movido
    priceSlider.noUiSlider.on('update', function(values, handle) {
      const minValue = parseInt(values[0]);
      const maxValue = parseInt(values[1]);
      
      priceMin.value = minValue;
      priceMax.value = maxValue;
      
      priceMinDisplay.textContent = 'R$ ' + minValue.toFixed(2).replace('.', ',');
      priceMaxDisplay.textContent = 'R$ ' + maxValue.toFixed(2).replace('.', ',');
    });
  }

  // ✅ Debug para verificar carregamento de imagens
  const imagens = document.querySelectorAll('.produto-img');
  console.log('📸 Total de imagens na vitrine:', imagens.length);
  
  imagens.forEach((img, index) => {
    console.log(`Imagem ${index + 1}:`, img.src);
  });

  console.log('✅ Header com centralização vertical perfeita');
  console.log('✅ Menu hambúrguer com overlay correto funcionando');
  console.log('✅ Layout otimizado para vitrine');
});
</script>

</body>
</html>
