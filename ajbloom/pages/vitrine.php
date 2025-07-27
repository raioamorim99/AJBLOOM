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
  <link rel="stylesheet" href="../assets/css/index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.6.1/nouislider.min.css">
  <style>
  /* Estilo base do cabeçalho */
header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 80px;
  padding: 0 40px;
  background-color: white; /* COR NORMAL */
  display: flex;
  justify-content: space-between;
  align-items: center;
  z-index: 1000;
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}


.logo {
  max-height: 240px; /* ⬅️ garante que a logo fique proporcional */
}

.nav {
  display: flex;
  gap: 1rem;
}

/* Botões invisíveis no início */
.menu-btn {
  color: transparent;
  border: 1px solid transparent;
  padding: 0.5rem 1rem;
  font-weight: 300;
  background: none;
  transition: color 0.3s, border 0.3s;
}

/* Quando passa o mouse nos botões (antes do scroll) */
.menu-btn:hover {
  color: #000;
  border-color: #000;
}

/* Quando o usuário rola a página */
.header.scrolled {
  background-color: transparent;
}

.header.scrolled .menu-btn {
  color: #000;
  border-color: #000;
}
.nav {
  display: flex;
  gap: 1.5rem;
  align-items: center;
}


/* Submenu container */
.menu-item {
  position: relative;
}

/* Dropdown escondido */
.dropdown {
  position: absolute;
  top: 110%;
  left: 0;
  background: #fff;
  border: 1px solid #ddd;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  padding: 0.5rem 0;
  list-style: none;
  display: none;
  min-width: 200px;
  z-index: 1000;
}

/* Itens do submenu */
.dropdown li a {
  display: block;
  padding: 0.6rem 1rem;
  color: #333;
  text-decoration: none;
  transition: background 0.2s;
}

.dropdown li a:hover {
  background: #f0f0f0;
}

/* Mostrar dropdown no hover */
.menu-item:hover .dropdown {
  display: block;
}

/* Quando header estiver em modo ativo */
.header.scrolled .menu-btn {
  color: #000;
  border-color: #000;
}


    body {
      background-color: #f0f4f6;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      color: #333;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      padding: 230px 2rem 4rem;
    }

    .filtros-container {
      background: white;
      padding: 1.5rem;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      margin-bottom: 2rem;
    }

    .filtros-row {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      align-items: flex-end;
    }

    .filtro-group {
      flex: 1;
      min-width: 200px;
    }

    .filtro-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: #555;
    }

    .filtros input, .filtros select, .filtros button {
      padding: 0.6rem 1rem;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      width: 100%;
      background: white;
    }

    .filtros button {
      background-color: #2c3e50;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.2s;
    }

    .filtros button:hover {
      background-color: #1a252f;
    }

    .checkbox-group {
      display: flex;
      gap: 1rem;
      margin-top: 1rem;
    }

    .checkbox-label {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.9rem;
      cursor: pointer;
    }

    .price-range-values {
      display: flex;
      justify-content: space-between;
      margin-top: 0.5rem;
      font-size: 0.9rem;
    }

    /* Estilo do slider */
    #price-slider {
      margin: 1rem 0 1.5rem;
    }

    .noUi-target {
      background: #ddd;
      border: none;
      box-shadow: none;
      height: 4px;
    }

    .noUi-connect {
      background: #2c3e50;
    }

    .noUi-handle {
      width: 18px;
      height: 18px;
      top: -7px;
      right: -9px;
      border-radius: 50%;
      background: #2c3e50;
      border: none;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
      cursor: pointer;
    }

    .noUi-handle:before, .noUi-handle:after {
      display: none;
    }

    /* Produtos grid */
    .produtos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 2rem;
    }

    .produto-card {
      background: #fff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
      position: relative;
    }

    .produto-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .produto-img {
      width: 100%;
      height: 260px;
      object-fit: cover;
    }

    .produto-info {
      padding: 1.2rem;
    }

    .produto-info h3 {
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
      color: #2c3e50;
    }

    .preco {
      font-size: 1.1rem;
      font-weight: bold;
      color: #e74c3c;
    }

    .preco .antigo {
      text-decoration: line-through;
      color: #999;
      margin-right: 0.5rem;
      font-size: 0.9rem;
    }

    /* Tags */
    .tag {
      position: absolute;
      color: white;
      font-size: 0.7rem;
      padding: 0.3rem 0.8rem;
      border-radius: 0 0 8px 0;
      font-weight: bold;
      z-index: 1;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .tag-promocao {
      background: #e74c3c;
      top: 0;
      left: 0;
    }

    .tag-lancamento {
      background: #27ae60;
      top: 30px;
      left: 0;
    }

    .tag-mais-vendido {
      background: #f39c12;
      top: 60px;
      left: 0;
    }

    .category-highlight {
      text-align: center;
      margin: 1.5rem 0;
      font-size: 2rem;
      color: #2c3e50;
      font-weight: 700;
      text-transform: capitalize;
    }

    @media (max-width: 768px) {
      .filtros-row {
        flex-direction: column;
        gap: 1rem;
      }
      
      .produto-img {
        height: 220px;
      }
    }

    @media (max-width: 480px) {
      .container {
        padding: 200px 1rem 2rem;
      }
      
      .produto-img {
        height: 180px;
      }
    }
  </style>
</head>
<body>

<header id="header" class="header">
  <img src="../assets/imagens/logo/logo.png" alt="AJBLOOM" class="logo" />
  <nav class="nav">
    <a href="index.php" class="menu-btn">Home</a>
    <a href="sobre.php" class="menu-btn">Sobre nós</a>

    <div class="menu-item">
      <button class="menu-btn dropdown-toggle">Loja AJBLOOM</button>
      <ul class="dropdown">
        <li><a href="politica_ajbloom/politica.php">Política de Privacidade</a></li>
        <li><a href="#suporte">Ajuda / Suporte</a></li>
        <li><a href="#politica_ajbloom/trocas.php">Trocas e Devoluções</a></li>
        <li><a href="politica_ajbloom/termos.php">Termos de Serviço</a></li>
      </ul>
    </div>
  </nav>
</header>



<main class="container">
  <form method="GET" class="filtros-container">
    <div class="filtros-row">
      <div class="filtro-group">
        <label for="busca">Buscar produto</label>
        <input type="text" name="busca" placeholder="Digite o nome do produto..." value="<?php echo htmlspecialchars($_GET['busca'] ?? '') ?>">
      </div>
      
      <div class="filtro-group">
        <label for="categoria">Categoria</label>
        <select name="categoria">
          <option value="">Todas as categorias</option>
          <option value="camisetas" <?php if($categoria=="camisetas") echo "selected"; ?>>Camisetas</option>
          <option value="vestidos" <?php if($categoria=="vestidos") echo "selected"; ?>>Vestidos</option>
          <option value="acessorios" <?php if($categoria=="acessorios") echo "selected"; ?>>Acessórios</option>
        </select>
      </div>
      
      <div class="filtro-group">
        <label for="price-slider">Faixa de preço</label>
        <div id="price-slider"></div>
        <div class="price-range-values">
          <span id="price-min">R$ <?php echo number_format($preco_min, 2, ',', '.'); ?></span>
          <span id="price-max">R$ <?php echo number_format($preco_max, 2, ',', '.'); ?></span>
        </div>
        <input type="hidden" name="preco_min" id="preco_min" value="<?php echo $preco_min; ?>">
        <input type="hidden" name="preco_max" id="preco_max" value="<?php echo $preco_max; ?>">
      </div>
      
      <div class="filtro-group">
        <button type="submit">Filtrar</button>
      </div>
    </div>
    
    <div class="checkbox-group">
      <label class="checkbox-label">
        <input type="checkbox" name="lancamento" <?php if(isset($_GET['lancamento'])) echo 'checked'; ?>>
        Mostrar lançamentos
      </label>
      
      <label class="checkbox-label">
        <input type="checkbox" name="mais_vendido" <?php if(isset($_GET['mais_vendido'])) echo 'checked'; ?>>
        Mais vendidos
      </label>
    </div>
  </form>

  <h2 class="category-highlight"><?php echo htmlspecialchars($titulo_categoria); ?></h2>

  <div class="produtos-grid">
    <?php while($produto = $resultado->fetch_assoc()): ?>
      <div class="produto-card">
        <a href="produto.php?id=<?php echo $produto['id']; ?>">
          <?php if (!empty($produto['preco_antigo'])): ?>
            <div class="tag tag-promocao">Promoção</div>
          <?php endif; ?>
          <?php if ($produto['lancamento']): ?>
            <div class="tag tag-lancamento">Novidade</div>
          <?php endif; ?>
          <?php if ($produto['mais_vendido']): ?>
            <div class="tag tag-mais-vendido">Mais Vendido</div>
          <?php endif; ?>
          <img src="<?php echo corrigirCaminhoImagem($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" class="produto-img" onerror="this.src='/placeholder.svg?height=260&width=260&text=Imagem+não+encontrada'; console.log('Erro ao carregar imagem:', this.src);">
          <div class="produto-info">
            <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
            <p class="preco">
              <?php if (!empty($produto['preco_antigo'])): ?>
                <span class="antigo">R$ <?php echo number_format($produto['preco_antigo'], 2, ',', '.'); ?></span>
              <?php endif; ?>
              R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
            </p>
          </div>
        </a>
      </div>
    <?php endwhile; ?>
  </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.6.1/nouislider.min.js"></script>
<script>
  // Inicialização do slider de preço
  const priceSlider = document.getElementById('price-slider');
  const priceMin = document.getElementById('preco_min');
  const priceMax = document.getElementById('preco_max');
  const priceMinDisplay = document.getElementById('price-min');
  const priceMaxDisplay = document.getElementById('price-max');

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
  
 
  const header = document.getElementById('header');
  const buttons = document.querySelectorAll('.menu-btn');

  // Ao passar o mouse em qualquer parte da tela
  document.addEventListener('mousemove', () => {
    if (!header.classList.contains('scrolled')) {
      header.classList.add('scrolled');
    }
  });

  // Hover individual nos botões (se quiser manter)
  buttons.forEach(button => {
    button.addEventListener('mouseenter', () => {
      button.style.color = '#000';
      button.style.borderColor = '#000';
    });

    button.addEventListener('mouseleave', () => {
      if (!header.classList.contains('scrolled')) {
        button.style.color = 'transparent';
        button.style.borderColor = 'transparent';
      }
    });
  });

  document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.querySelector(".submenu-toggle");
    const submenu = toggle.querySelector(".submenu");

    toggle.addEventListener("click", function (e) {
      e.preventDefault();
      submenu.classList.toggle("show");
    });

    // Fechar submenu ao clicar fora
    document.addEventListener("click", function (e) {
      if (!toggle.contains(e.target)) {
        submenu.classList.remove("show");
      }
    });
  });

  // ✅ NOVO: Debug para verificar carregamento de imagens
  console.log('🌸 Vitrine carregada com sucesso!');
  
  // Verificar se as imagens estão carregando
  document.addEventListener('DOMContentLoaded', function() {
    const imagens = document.querySelectorAll('.produto-img');
    console.log('📸 Total de imagens na vitrine:', imagens.length);
    
    imagens.forEach((img, index) => {
      console.log(`Imagem ${index + 1}:`, img.src);
    });
  });
</script>


</body>
</html>
