<?php
session_start();
include("../backend/conexao.php");

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("ID inválido.");
}

$id = intval($_GET["id"]);

// ✅ MELHORADO: Buscar todas as imagens e informações do produto
$stmt = $conn->prepare("SELECT nome, preco, preco_antigo, categoria, imagem, imagem2, imagem3, imagem4, imagem5, imagem6, descricao, tamanhos, cores FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$produto = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$produto) {
    $produto = [
        'nome' => 'Produto não encontrado',
        'descricao' => 'Este produto não está mais disponível.',
        'preco' => null,
        'preco_antigo' => null,
        'imagem' => null,
        'categoria' => null,
        'tamanhos' => null,
        'cores' => null
    ];
}

// ✅ CORRIGIDO: Função para processar imagens do produto
function obterImagensProduto($produto) {
    $imagens = [];
    $campos_imagem = ['imagem', 'imagem2', 'imagem3', 'imagem4', 'imagem5', 'imagem6'];
    
    foreach ($campos_imagem as $campo) {
        if (!empty($produto[$campo])) {
            $imagem = $produto[$campo];
            
            // Se é URL externa, adiciona diretamente
            if (filter_var($imagem, FILTER_VALIDATE_URL)) {
                $imagens[] = $imagem;
            } else {
                // ✅ CORRIGIDO: Verificar se a imagem existe nos uploads do admin
                $imagem = ltrim($imagem, '/');
                
                // Primeiro, tentar o caminho dos uploads do admin
                $caminho_admin = '../administrador/uploads/' . basename($imagem);
                if (file_exists($caminho_admin)) {
                    $imagens[] = $caminho_admin;
                    continue;
                }
                
                // Se não encontrar, tentar o caminho assets
                if (strpos($imagem, 'assets/') === 0) {
                    $caminho_assets = '../' . $imagem;
                    if (file_exists($caminho_assets)) {
                        $imagens[] = $caminho_assets;
                        continue;
                    }
                }
                
                // Tentar pasta de produtos
                $caminho_produtos = '../assets/imagens/produtos/' . basename($imagem);
                if (file_exists($caminho_produtos)) {
                    $imagens[] = $caminho_produtos;
                    continue;
                }
                
                // Se não encontrar em lugar nenhum, usar o caminho original
                $imagens[] = '../' . $imagem;
            }
        }
    }
    
    // Se não tem imagens, retorna placeholder
    if (empty($imagens)) {
        $imagens[] = '/placeholder.svg?height=600&width=600&text=Sem+Imagem';
    }
    
    return $imagens;
}

// ✅ Processar tamanhos e cores
$tamanhos = !empty($produto['tamanhos']) ? explode(',', $produto['tamanhos']) : [];
$cores = !empty($produto['cores']) ? explode(',', $produto['cores']) : [];
$imagens = obterImagensProduto($produto);

$base = '/';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($produto['nome']); ?> - AJBLOOM</title>
  <meta name="description" content="Veja detalhes de <?php echo htmlspecialchars($produto['nome']); ?> da AJBLOOM.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/index.css">
  <style>
    body {
      background-color: #ffffff;
      font-family: 'Raleway', sans-serif;
      margin: 0;
      padding-top: 120px;
      color: #333;
    }

    /* ✅ CORRIGIDO: Header da página produto */
    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100px;
      padding: 0 2rem;
      background-color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 1000;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .logo-container img {
      height: 90px; /* ✅ AUMENTADO: logo maior */
      width: auto;
      opacity: 1 !important;
    }

    .nav-buttons {
      display: flex;
      gap: 0.75rem; /* ✅ REDUZIDO: gap menor */
      align-items: center;
    }

    .nav-btn {
      background: transparent;
      color: #333;
      border: 2px solid #333;
      padding: 0.5rem 1rem; /* ✅ REDUZIDO: padding menor */
      border-radius: 20px; /* ✅ REDUZIDO: border radius menor */
      font-weight: 600;
      text-decoration: none;
      font-size: 0.8rem; /* ✅ REDUZIDO: fonte menor */
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem; /* ✅ REDUZIDO: gap menor */
      white-space: nowrap; /* ✅ ADICIONADO: evita quebra de linha */
    }

    .nav-btn:hover {
      background: #333;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .nav-btn.primary {
      background: #90b7eb;
      border-color: #90b7eb;
      color: white;
    }

    .nav-btn.primary:hover {
      background: #7aa3d9;
      border-color: #7aa3d9;
    }

    /* ✅ CORRIGIDO: Responsividade mobile */
    @media (max-width: 768px) {
      header {
        padding: 0 1rem;
        height: 90px; /* ✅ REDUZIDO: altura menor no mobile */
      }

      .logo-container img {
        height: 70px; /* ✅ AJUSTADO: logo menor no mobile */
      }

      .nav-buttons {
        flex-direction: row; /* ✅ CORRIGIDO: lado a lado no mobile */
        gap: 0.5rem;
      }

      .nav-btn {
        padding: 0.4rem 0.8rem; /* ✅ REDUZIDO: padding ainda menor no mobile */
        font-size: 0.75rem; /* ✅ REDUZIDO: fonte menor no mobile */
      }

      body {
        padding-top: 100px; /* ✅ AJUSTADO: padding menor */
      }
    }

    @media (max-width: 480px) {
      .nav-btn {
        padding: 0.3rem 0.6rem;
        font-size: 0.7rem;
      }

      .nav-btn i {
        font-size: 0.8rem;
      }
    }

    /* ✅ MELHORADO: Container do produto */
    .produto-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 3rem;
      align-items: start;
    }

    /* ✅ NOVO: Galeria de imagens */
    .produto-galeria {
      position: sticky;
      top: 140px;
    }

    .imagem-principal {
      width: 100%;
      height: 500px;
      border-radius: 20px;
      overflow: hidden;
      margin-bottom: 1rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      position: relative;
      background: #f8f9fa;
    }

    .imagem-principal img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
      opacity: 1 !important;
      display: block !important;
    }

    .imagem-principal:hover img {
      transform: scale(1.05);
    }

    .miniaturas {
      display: flex;
      gap: 0.75rem;
      overflow-x: auto;
      padding: 0.5rem 0;
      scrollbar-width: thin;
      scrollbar-color: #90b7eb transparent;
    }

    .miniaturas::-webkit-scrollbar {
      height: 6px;
    }

    .miniaturas::-webkit-scrollbar-thumb {
      background: #90b7eb;
      border-radius: 3px;
    }

    .miniatura {
      width: 80px;
      height: 80px;
      border-radius: 12px;
      overflow: hidden;
      cursor: pointer;
      border: 3px solid transparent;
      transition: all 0.3s ease;
      flex-shrink: 0;
      background: #f8f9fa;
    }

    .miniatura.active {
      border-color: #90b7eb;
      transform: scale(1.1);
    }

    .miniatura img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 1 !important;
      display: block !important;
    }

    .miniatura:hover {
      border-color: #90b7eb;
      transform: scale(1.05);
    }

    /* ✅ MELHORADO: Informações do produto */
    .produto-info {
      padding: 1rem 0;
    }

    .produto-info h1 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: #333;
      font-weight: 700;
      line-height: 1.2;
    }

    .categoria-badge {
      display: inline-block;
      background: linear-gradient(135deg, #90b7eb, #a8c5f0);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 1.5rem;
    }

    .produto-preco {
      margin-bottom: 2rem;
    }

    .preco-atual {
      font-size: 2.5rem;
      font-weight: 700;
      color: #e74c3c;
      display: block;
    }

    .preco-antigo {
      font-size: 1.5rem;
      color: #999;
      text-decoration: line-through;
      margin-right: 1rem;
    }

    .desconto {
      background: #e74c3c;
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: 600;
      margin-left: 1rem;
    }

    /* ✅ NOVO: Seleção de tamanhos e cores */
    .opcoes-produto {
      margin-bottom: 2rem;
    }

    .opcao-grupo {
      margin-bottom: 1.5rem;
    }

    .opcao-titulo {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 0.75rem;
      color: #333;
    }

    .tamanhos, .cores {
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    .tamanho, .cor {
      padding: 0.75rem 1rem;
      border: 2px solid #ddd;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 600;
      min-width: 50px;
      text-align: center;
    }

    .tamanho:hover, .cor:hover {
      border-color: #90b7eb;
      background: rgba(144, 183, 235, 0.1);
    }

    .tamanho.selected, .cor.selected {
      background: #90b7eb;
      color: white;
      border-color: #90b7eb;
    }

    .cor {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.8rem;
      position: relative;
    }

    /* ✅ MELHORADO: Descrição do produto */
    .produto-descricao {
      background: #f8f9fa;
      padding: 2rem;
      border-radius: 15px;
      margin-bottom: 2rem;
      line-height: 1.7;
    }

    .produto-descricao h3 {
      margin-bottom: 1rem;
      color: #333;
      font-size: 1.3rem;
    }

    /* ✅ MELHORADO: Botão de compra */
    .btn-comprar {
      width: 100%;
      background: linear-gradient(135deg, #25d366, #128c7e);
      color: white;
      padding: 1.25rem 2rem;
      border: none;
      border-radius: 15px;
      font-size: 1.2rem;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
      text-decoration: none;
      margin-bottom: 1rem;
    }

    .btn-comprar:hover {
      background: linear-gradient(135deg, #128c7e, #25d366);
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
    }

    .btn-comprar i {
      font-size: 1.5rem;
    }

    /* ✅ Informações adicionais */
    .info-adicional {
      background: white;
      border: 1px solid #e1e5e9;
      border-radius: 15px;
      padding: 1.5rem;
      margin-top: 1rem;
    }

    .info-item {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1rem;
      font-size: 0.95rem;
    }

    .info-item:last-child {
      margin-bottom: 0;
    }

    .info-item i {
      color: #90b7eb;
      font-size: 1.2rem;
      width: 20px;
    }

    /* ✅ RESPONSIVIDADE */
    @media (max-width: 768px) {
      .produto-container {
        grid-template-columns: 1fr;
        gap: 2rem;
        padding: 1rem;
      }

      .produto-galeria {
        position: static;
      }

      .imagem-principal {
        height: 400px;
      }

      .produto-info h1 {
        font-size: 2rem;
      }

      .preco-atual {
        font-size: 2rem;
      }
    }

    @media (max-width: 480px) {
      .produto-container {
        padding: 0.5rem;
      }

      .imagem-principal {
        height: 300px;
      }

      .miniaturas {
        gap: 0.5rem;
      }

      .miniatura {
        width: 60px;
        height: 60px;
      }
    }

    /* ✅ Loading states */
    .loading {
      display: none;
      text-align: center;
      padding: 2rem;
    }

    .loading.active {
      display: block;
    }

    .spinner {
      width: 40px;
      height: 40px;
      border: 4px solid #f3f3f3;
      border-top: 4px solid #90b7eb;
      border-radius: 50%;
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

<!-- ✅ CORRIGIDO: Header da página produto -->
<header>
  <div class="logo-container">
    <img src="../assets/imagens/logo/logo.png" alt="AJBLOOM Logo">
  </div>

  <div class="nav-buttons">
    <a href="index.php" class="nav-btn">
      <i class="fas fa-arrow-left"></i>
      Voltar
    </a>
    <a href="vitrine.php" class="nav-btn primary">
      <i class="fas fa-th-large"></i>
      Produtos
    </a>
  </div>
</header>

<main class="produto-container">
  <!-- ✅ NOVA: Galeria de imagens -->
  <div class="produto-galeria">
    <div class="imagem-principal">
      <img id="imagem-principal" src="<?php echo htmlspecialchars($imagens[0]); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" onerror="this.src='/placeholder.svg?height=500&width=500&text=Imagem+não+encontrada';">
    </div>
    
    <?php if (count($imagens) > 1): ?>
    <div class="miniaturas">
      <?php foreach ($imagens as $index => $imagem): ?>
        <div class="miniatura <?php echo $index === 0 ? 'active' : ''; ?>" onclick="trocarImagem('<?php echo htmlspecialchars($imagem); ?>', this)">
          <img src="<?php echo htmlspecialchars($imagem); ?>" alt="Imagem <?php echo $index + 1; ?>" onerror="this.src='/placeholder.svg?height=80&width=80&text=Erro';">
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- ✅ MELHORADO: Informações do produto -->
  <div class="produto-info">
    <div class="categoria-badge">
      <?php echo htmlspecialchars(ucfirst($produto['categoria'] ?? 'Produto')); ?>
    </div>

    <h1><?php echo htmlspecialchars($produto['nome']); ?></h1>

    <div class="produto-preco">
      <?php if ($produto['preco_antigo']): ?>
        <span class="preco-antigo">R$ <?php echo number_format($produto['preco_antigo'], 2, ',', '.'); ?></span>
        <?php 
        $desconto = (($produto['preco_antigo'] - $produto['preco']) / $produto['preco_antigo']) * 100;
        ?>
        <span class="desconto"><?php echo round($desconto); ?>% OFF</span>
      <?php endif; ?>
      <span class="preco-atual">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
    </div>

    <!-- ✅ NOVO: Seleção de tamanhos e cores -->
    <div class="opcoes-produto">
      <?php if (!empty($tamanhos)): ?>
      <div class="opcao-grupo">
        <div class="opcao-titulo">Tamanho:</div>
        <div class="tamanhos">
          <?php foreach ($tamanhos as $tamanho): ?>
            <div class="tamanho" onclick="selecionarTamanho(this)"><?php echo trim($tamanho); ?></div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if (!empty($cores)): ?>
      <div class="opcao-grupo">
        <div class="opcao-titulo">Cor:</div>
        <div class="cores">
          <?php foreach ($cores as $cor): ?>
            <div class="cor" onclick="selecionarCor(this)" title="<?php echo trim($cor); ?>">
              <?php echo substr(trim($cor), 0, 3); ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- ✅ MELHORADO: Descrição do produto -->
    <?php if (!empty($produto['descricao'])): ?>
    <div class="produto-descricao">
      <h3>Descrição do Produto</h3>
      <div><?php echo $produto['descricao']; ?></div>
    </div>
    <?php endif; ?>

    <!-- ✅ MELHORADO: Botão de compra -->
    <a href="https://wa.me/5541999999999?text=<?php echo urlencode('Olá! Gostei do produto: ' . $produto['nome'] . '. Ainda está disponível?'); ?>" 
       class="btn-comprar" target="_blank" id="btn-comprar">
      <i class="fab fa-whatsapp"></i>
      Fazer pedido no WhatsApp
    </a>

    <!-- ✅ NOVO: Informações adicionais -->
    <div class="info-adicional">
      <div class="info-item">
        <i class="fas fa-truck"></i>
        <span>Frete grátis para compras acima de R$ 150</span>
      </div>
      <div class="info-item">
        <i class="fas fa-exchange-alt"></i>
        <span>Troca grátis em até 7 dias</span>
      </div>
      <div class="info-item">
        <i class="fas fa-shield-alt"></i>
        <span>Compra 100% segura e protegida</span>
      </div>
      <div class="info-item">
        <i class="fas fa-headset"></i>
        <span>Atendimento especializado</span>
      </div>
    </div>
  </div>
</main>

<!-- ✅ Loading indicator -->
<div class="loading" id="loading">
  <div class="spinner"></div>
  <p>Carregando...</p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
<script>
// ✅ CORRIGIDO: Função para trocar imagem principal sem interferir na opacidade
function trocarImagem(src, elemento) {
  const imagemPrincipal = document.getElementById('imagem-principal');
  const miniaturas = document.querySelectorAll('.miniatura');
  
  // Remover classe active de todas as miniaturas
  miniaturas.forEach(min => min.classList.remove('active'));
  
  // Adicionar classe active na miniatura clicada
  elemento.classList.add('active');
  
  // Trocar imagem principal com efeito suave
  imagemPrincipal.style.opacity = '0.7';
  
  setTimeout(() => {
    imagemPrincipal.src = src;
    imagemPrincipal.style.opacity = '1';
  }, 150);
}

// ✅ NOVO: Função para selecionar tamanho
function selecionarTamanho(elemento) {
  const tamanhos = document.querySelectorAll('.tamanho');
  tamanhos.forEach(t => t.classList.remove('selected'));
  elemento.classList.add('selected');
  
  atualizarMensagemWhatsApp();
}

// ✅ NOVO: Função para selecionar cor
function selecionarCor(elemento) {
  const cores = document.querySelectorAll('.cor');
  cores.forEach(c => c.classList.remove('selected'));
  elemento.classList.add('selected');
  
  atualizarMensagemWhatsApp();
}

// ✅ NOVO: Atualizar mensagem do WhatsApp com seleções
function atualizarMensagemWhatsApp() {
  const tamanhoSelecionado = document.querySelector('.tamanho.selected');
  const corSelecionada = document.querySelector('.cor.selected');
  const btnComprar = document.getElementById('btn-comprar');
  
  let mensagem = `Olá! Gostei do produto: <?php echo addslashes($produto['nome']); ?>`;
  
  if (tamanhoSelecionado) {
    mensagem += ` - Tamanho: ${tamanhoSelecionado.textContent}`;
  }
  
  if (corSelecionada) {
    mensagem += ` - Cor: ${corSelecionada.title}`;
  }
  
  mensagem += '. Ainda está disponível?';
  
  const urlWhatsApp = `https://wa.me/5541999999999?text=${encodeURIComponent(mensagem)}`;
  btnComprar.href = urlWhatsApp;
}

// ✅ Scroll suave para header
window.addEventListener('scroll', function () {
  const header = document.querySelector('header');
  if (window.scrollY > 50) {
    header.style.background = 'rgba(255, 255, 255, 0.95)';
    header.style.backdropFilter = 'blur(10px)';
  } else {
    header.style.background = 'white';
    header.style.backdropFilter = 'none';
  }
});

// ✅ Keyboard navigation para galeria
document.addEventListener('keydown', function(e) {
  const miniaturas = document.querySelectorAll('.miniatura');
  const ativa = document.querySelector('.miniatura.active');
  
  if (!ativa || miniaturas.length <= 1) return;
  
  let index = Array.from(miniaturas).indexOf(ativa);
  
  if (e.key === 'ArrowLeft' && index > 0) {
    miniaturas[index - 1].click();
  } else if (e.key === 'ArrowRight' && index < miniaturas.length - 1) {
    miniaturas[index + 1].click();
  }
});

// ✅ CORRIGIDO: Inicialização simples sem interferir nas imagens
document.addEventListener('DOMContentLoaded', function() {
  console.log('🌸 Página do produto carregada com sucesso!');
  console.log('📸 Galeria de imagens:', <?php echo count($imagens); ?>, 'imagens');
  console.log('📏 Tamanhos disponíveis:', <?php echo count($tamanhos); ?>);
  console.log('🎨 Cores disponíveis:', <?php echo count($cores); ?>);
  
  // Garantir que todas as imagens estejam visíveis
  const todasImagens = document.querySelectorAll('img');
  todasImagens.forEach(img => {
    img.style.opacity = '1';
    img.style.display = 'block';
  });
});
</script>

</body>
</html>
