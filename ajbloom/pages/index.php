<?php 
session_start(); 
include '../backend/conexao.php';

// ✅ Buscar 10 produtos iniciais para renderização no servidor
$sql_inicial = "SELECT id, nome, preco, preco_antigo, categoria, imagem, descricao, lancamento, mais_vendido 
                FROM produtos 
                ORDER BY id DESC 
                LIMIT 10";
$resultado_inicial = $conn->query($sql_inicial);
$produtos_iniciais = [];
while ($produto = $resultado_inicial->fetch_assoc()) {
    $produtos_iniciais[] = $produto;
}

$base = '/';

// Função para corrigir caminho da imagem
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
    
    // Se já começa com assets/, adiciona apenas ../
    if (strpos($imagem, 'assets/') === 0) {
        return '../' . $imagem;
    }
    
    // Se não tem assets/, assume que está na pasta de produtos
    if (!strpos($imagem, 'assets/')) {
        return '../assets/imagens/produtos/' . basename($imagem);
    }
    
    // Caso padrão
    return '../' . $imagem;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="A Ajbloom é uma loja de moda feminina que oferece estilo, autenticidade e personalidade. Explore nossas novidades.">
  <title>Ajbloom</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Raleway&family=Montserrat:wght@400;600&family=Dancing+Script&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/index.css?v=5.0" />
</head>
<body>

  <!-- Letreiro Promocional Fixo -->
  <div class="ajb-letreiro-topo">
    <div class="ajb-letreiro-texto">
      Explore a coleção Ajbloom | moda que floresce com você! 
    </div>
  </div>

<header class="header">
  <!-- Menu hambúrguer (mobile) -->
  <button id="menu-toggle" aria-label="Abrir menu">
    <span class="bar"></span>
    <span class="bar"></span>
    <span class="bar"></span>
  </button>

  <div class="topo">
    <!-- Barra de pesquisa (esquerda no desktop, direita no mobile) -->
    <div class="ajb-header-search">
      <input
        type="text"
        class="search-input"
        placeholder="Buscar produto..."
        aria-label="Buscar produto"
      />
    </div>

    <!-- Logotipo sempre centralizado -->
    <picture class="site-logo">
      <img src="../assets/imagens/logo/logo.png" alt="AJbloom" class="site-logo__img" />
    </picture> 

    <!-- Menu desktop (direita) -->
    <nav class="desktop-menu" aria-label="Menu principal">
      <ul class="menu-desktop">
        <li><a href="<?= $base ?>index.php" class="home-link">Home</a></li>
        <li><a href="#destaques">Catálogo</a></li>
        <li><a href="vitrine.php">Produtos</a></li>
        <li><a href="sobre.php">Sobre nós</a></li>
      </ul>
    </nav>
  </div>

  <!-- Menu lateral -->
  <nav id="side-menu" class="menu-lateral" aria-hidden="true" aria-labelledby="menu-toggle" tabindex="-1">
    <button id="close-menu" aria-label="Fechar menu">×</button>
    <ul class="menu-lista">
      <li><a href="index.php" class="home-link">Home</a></li>
      <li><a href="#destaques">Catálogo</a></li>
      <li><a href="vitrine.php">Produtos</a></li>
      <li><a href="sobre.php">Sobre nós</a></li>
    </ul>
  </nav>
</header>

<div id="overlay"></div>
<main class="site-content">

<section class="hero-slider animate-on-scroll active">
  <div class="slides">  
    <!-- ✅ SLIDE 1 -->
    <div class="slide">
      <picture>
        <source media="(max-width: 768px)" srcset="../assets/imagens/banners/mobile/bem-vinda_mobile.webp">
        <img src="../assets/imagens/banners/desktop/bem-vinda.webp" alt="Bem-vinda à AJBLOOM" />
      </picture>
      <div class="hero-text-overlay">
        <h1>Bem-vinda à AJBLOOM</h1>
        <p>Moda feita para mulheres que florescem todos os dias.</p>
        <a href="#destaques" class="btn-explorar">Ver coleção</a>
      </div>
    </div>

    <!-- ✅ SLIDE 2 -->
    <div class="slide">
      <picture>
        <source media="(max-width: 768px)" srcset="../assets/imagens/banners/mobile/vista-ajbloom_mobile.webp">
        <img src="../assets/imagens/banners/desktop/vista-ajbloom.webp" alt="Vista-se de AJBLOOM" loading="lazy" />
      </picture>
    </div>

    <!-- ✅ SLIDE 3 -->
    <div class="slide">
      <picture>
        <source media="(max-width: 768px)" srcset="../assets/imagens/banners/mobile/estilo-ajbloom_mobile.webp">
        <img src="../assets/imagens/banners/desktop/estilo-ajbloom.webp" alt="Estilo que acompanha" loading="lazy" />
      </picture>
    </div>

    <!-- ✅ SLIDE 4 -->
    <div class="slide">
      <picture>
        <source media="(max-width: 768px)" srcset="../assets/imagens/banners/mobile/ganhou_mobile.webp">
        <img src="../assets/imagens/banners/desktop/ganhou-ajbloom.webp" alt="Você ganhou" loading="lazy" />
      </picture>
    </div>
  </div>

  <div class="dots">
    <span class="dot active" data-index="0"></span>
    <span class="dot" data-index="1"></span>
    <span class="dot" data-index="2"></span>
    <span class="dot" data-index="3"></span>
  </div>
</section>

<!-- ✅ Seção de produtos com 10 produtos iniciais -->
<section id="destaques" class="destaques container animate-on-scroll">
  <div class="section-header">
    <select id="filtro-categoria">
      <option value="tudo">Todas Categorias</option>
      <option value="camisetas">Camisetas</option>
      <option value="vestidos">Vestidos</option>
      <option value="acessorios">Acessórios</option>
    </select>
  </div>
  
  <!-- Loading indicator -->
  <div id="loading-produtos" class="loading-produtos" style="display: none;">
    <div class="spinner"></div>
    <p>Carregando produtos...</p>
  </div>

  <!-- ✅ Grid de produtos - renderização inicial com 10 produtos -->
  <div id="produtos" class="produtos-grid">
    <?php foreach ($produtos_iniciais as $produto): ?>
      <div class="produto" data-categoria="<?= htmlspecialchars($produto['categoria']) ?>">
        <a href="produto.php?id=<?= $produto['id'] ?>">
          <img src="<?= corrigirCaminhoImagem($produto['imagem']) ?>" 
               alt="Imagem do produto <?= htmlspecialchars($produto['nome']) ?>" 
               loading="lazy"
               onerror="this.src='/placeholder.svg?height=400&width=400&text=Erro+Imagem'; this.style.background='#f8f9fa';" />
        </a>
        <div class="produto-info">
          <a href="produto.php?id=<?= $produto['id'] ?>">
            <h3><?= htmlspecialchars($produto['nome']) ?></h3>
          </a>
          <div class="produto-preco">
            <?php if ($produto['preco_antigo']): ?>
              <span class="preco-antigo">R$ <?= number_format($produto['preco_antigo'], 2, ',', '.') ?></span>
            <?php endif; ?>
            R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
          </div>
          <?php if ($produto['lancamento']): ?>
            <span class="badge badge-lancamento">Lançamento</span>
          <?php endif; ?>
          <?php if ($produto['mais_vendido']): ?>
            <span class="badge badge-vendido">Mais Vendido</span>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  
  <!-- ✅ Botão Ver Mais agora redireciona para vitrine.php -->
  <button id="ver-mais" class="botao-ver-mais" style="margin-top: 20px;">Ver todos os produtos</button>
</section>

<!-- ✅ MELHORADO: Seção Estilo AJBLOOM com imagens e botões funcionais -->
<section class="ajb-estilo animate-on-scroll">
  <div class="container">
    <h2 class="titulo-estilo">Viva o Estilo AJBLOOM</h2>
    <p class="subtitulo-estilo">Muito mais que roupas. Uma forma de se expressar.</p>

    <div class="estilo-grid">
      <div class="estilo-card estilo1">
        <div class="estilo-conteudo">
          <h3>Estilo & Atitude</h3>
          <p>Peças que acompanham sua energia em qualquer ocasião.</p>
          <a href="vitrine.php" class="btn-estilo">Explorar estilo</a>
        </div>
      </div>
      <div class="estilo-card estilo2">
        <div class="estilo-conteudo">
          <h3>Lançamentos</h3>
          <p>Novidades toda sexta. Não perca as peças queridinhas da semana!</p>
          <a href="vitrine.php?lancamento=1" class="btn-estilo">Ver lançamentos</a>
        </div>
      </div>
      <div class="estilo-card estilo3">
        <div class="estilo-conteudo">
          <h3>Coleção Especial</h3>
          <p>Peças únicas e exclusivas para momentos especiais da sua vida.</p>
          <a href="vitrine.php?categoria=vestidos" class="btn-estilo">Ver coleção</a>
        </div>
      </div>
    </div>

    <div class="cta-estilo">
      <p>✨ Descubra peças que combinam com sua personalidade única.</p>
      <a href="vitrine.php" class="btn-explorar">Explorar agora</a>
    </div>
  </div>
</section>

<!-- Instagram Feed -->
<section class="ajb-insta-feed">
  <h3>Nos siga no Instagram</h3>
  <a href="https://www.instagram.com/Ajbloom_" target="_blank">@Ajbloom_</a> 

  <div class="insta-grid">
    <a href="https://www.instagram.com/Ajbloom_" target="_blank">
      <img src="../assets/imagens/produtos/acessorios/cinto_dourado.webp" alt="Insta 1" loading="lazy">
    </a>
    <a href="https://www.instagram.com/Ajbloom_" target="_blank">
      <img src="../assets/imagens/produtos/vestidos/vestido_peonia.webp" alt="Insta 2" loading="lazy">
    </a>
    <a href="https://www.instagram.com/Ajbloom_" target="_blank">
      <img src="../assets/imagens/produtos/borys/body_lavanda.webp" alt="Insta 3" loading="lazy">
    </a>
    <a href="https://www.instagram.com/Ajbloom_" target="_blank">
      <img src="../assets/imagens/produtos/conjuntos/conjunto_margarida.webp" alt="Insta 4" loading="lazy">
    </a>
    <a href="https://www.instagram.com/Ajbloom_" target="_blank">
      <img src="../assets/imagens/produtos/vestidos/vestido_hortensia.webp" alt="Insta 5" loading="lazy">
    </a>
    <a href="https://www.instagram.com/Ajbloom_" target="_blank">
      <img src="../assets/imagens/produtos/vestidos/vestido_bambu.webp" alt="Insta 6" loading="lazy">
    </a>
  </div>
</section>

<!-- ✅ MELHORADO: Seção Showcase Banner com imagem de fundo -->
<section class="ajb-showcase-banner">
  <div class="showcase-container">
    <div class="showcase-content">
      <div class="showcase-text">
        <h2>Sua Essência Floresce Aqui</h2>
        <p>Cada peça AJBLOOM é criada para mulheres que não têm medo de ser autênticas. Vista-se com propósito, expresse sua personalidade e deixe sua beleza natural brilhar.</p>
        <div class="showcase-stats">
          <div class="stat-item">
            <span class="stat-number">5000+</span>
            <span class="stat-label">Clientes Felizes</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">100%</span>
            <span class="stat-label">Qualidade Garantida</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">24h</span>
            <span class="stat-label">Atendimento</span>
          </div>
        </div>
        <a href="vitrine.php" class="btn-showcase">Descobrir Coleção</a>
      </div>
      <div class="showcase-circle">
        <div class="circle-content">
          <h3>AJBLOOM</h3>
          <p>Moda que<br>Floresce</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="ajb-lookbook" id="lookbook">
  <div class="petalas-container" id="petalas-lookbook"></div>

  <h3 class="lookbook-titulo">LOOKBOOK Ajbloom</h3>

  <div class="lookbook-scroll">
    <div class="lookbook-item" style="background-image: url('../assets/imagens/produtos/conjuntos/conjunto_girassol.webp');" loading="lazy">
      <span>Coleção Floral</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/produtos/conjuntos/conjunto_margarida.webp');" loading="lazy">
      <span>Casual Elegante</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/produtos/conjuntos/conjunto_orquidea.webp');" loading="lazy">
      <span>Summer Vibes</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/produtos/saias/saia_lirio.webp');" loading="lazy">
      <span>Street Bloom</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/produtos/vestidos/vestido_bambu.webp');"  loading="lazy">
      <span>Minimal Bloom</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/produtos/vestidos/vestido_hortensia.webp');" loading="lazy">
      <span>Denim Garden</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/produtos/borys/body_lavanda-2.webp');" loading="lazy">
      <span>Fresh Elegance</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/produtos/borys/body_violeta-2.webp');" loading="lazy">
      <span>AJ Girl</span>
    </div>
  </div>
</section>

<section class="ajb-essencia-section animate-on-scroll">
  <div class="ajb-essencia-grid">
    <div class="essencia-texto">
      <h2>Não é só roupa. É a sua história.</h2>
      <p>Cada peça AJBLOOM é pensada para mulheres autênticas, fortes e leves. Use com orgulho o que te representa.</p>
      <a href="sobre.php" class="btn-explorar">Conheça nossa jornada</a>
    </div>
    <div class="essencia-img">
      <img src="../assets/imagens/logo/logo.png" alt="Imagem Essência AJBLOOM" loading="lazy" />
    </div>
  </div>
</section>

<section class="ajb-depoimentos animate-on-scroll">
  <h3>#SouAJBLOOM</h3>
  <div class="ajb-depoimentos-grid">
    <div class="ajb-depoimento">
      <p>"A Experiência AJBLOOM Pelos Olhos Delas"</p>
      <span>- Annjubs</span>
    </div>
  </div>
</section>

<section class="ajb-valores animate-on-scroll">
  <div class="ajb-valor">
    <img src="../assets/imagens/compromisso/entrega.webp" alt="Entrega" loading="lazy"/>
    <p>Entrega rápida</p>
  </div>
  <div class="ajb-valor">
    <img src="../assets/imagens/compromisso/sim.webp" alt="Qualidade" loading="lazy"/>
    <p>Qualidade garantida</p>
  </div>
  <div class="ajb-valor">
    <img src="../assets/imagens/compromisso/suporte.webp" alt="Suporte" loading="lazy"/>
    <p>Atendimento humano</p>
  </div>
</section>

<section class="ajb-compromisso animate-on-scroll">
  <div class="container compromisso-grid">
    <div class="compromisso-texto">
      <h2>Compromisso com você</h2>
      <p>Desde o início, nosso foco sempre foi mais do que vender: é entregar qualidade, confiança e respeito em cada detalhe. Acreditamos que cada peça de roupa deve refletir não apenas estilo, mas também a essência de quem a veste. Por isso, escolhemos cuidadosamente nossos fornecedores e parceiros, garantindo que cada produto atenda aos nossos altos padrões de qualidade e responsabilidade social.</p>
    </div>
    <div class="compromisso-info">
      <div class="info-card">
        <img src="../assets/imagens/compromisso/sim.webp" alt="Qualidade" loading="lazy" />
        <h4>Qualidade</h4>
      </div>
      <div class="info-card">
        <img src="../assets/imagens/compromisso/confianca.webp" alt="Confiança" loading="lazy" />
        <h4>Confiança</h4>
      </div>
      <div class="info-card">
        <img src="../assets/imagens/ajbloom-branco.JPG" alt="Respeito"  loading="lazy" />
        <h4>Respeito</h4>
      </div>
      <div class="counter-card">
        <div><span class="counter" data-target="5000">0</span>+</div>
        <p>Clientes felizes</p>
      </div>
    </div>
  </div>
</section>

</main>

<footer>
  <div class="container">
    <div class="footer-content">
      <div class="footer-column">
        <p class="creditos-site">
          <img src="../assets/imagens/luan/luan-foto.webp" alt="Logo LR" class="logo-lr-img" loading="lazy" />
          Criado por 
          <a href="https://www.instagram.com/luantech.oficial" target="_blank">Luan Raio</a> 
          <a href="https://raioamorim99.github.io/Luan-Raio/" target="_blank">Portfólio</a>
        </p>
        <p>📍 Curitiba - PR</p>
        <p>✉️ Ajbloomshop@gmail.com</p>
      </div>

      <div class="footer-column">
        <h3>Links Rápidos</h3>
        <a href="<?= $base ?>index.php" class="home-link">Home</a>
        <a href="vitrine.php">Produtos</a>
        <a href="sobre.php">Sobre Nós</a>
      </div>

      <div class="footer-column">
        <h3>Informações</h3>
        <a href="politica_ajbloom/trocas.php">Trocas e Devoluções</a>
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
    
<a href="https://wa.me/5541999999999?text=Ol%C3%A1%2C+gostaria+de+saber+mais+sobre+os+produtos+da+AJBLOOM%21" class="whatsapp-btn" target="_blank" aria-label="Fale conosco pelo WhatsApp">
  <img src="https://img.icons8.com/ios-filled/50/ffffff/whatsapp--v1.png" alt="WhatsApp" loading="lazy" />
</a>

<!-- modal -->
<div id="look-modal" class="look-modal">
  <div class="look-backdrop" onclick="fecharLookModal()"></div>
  <div class="look-conteudo">
    <button class="look-fechar" onclick="fecharLookModal()">×</button>
    <img id="look-img" src="/placeholder.svg" alt="Look AJBLOOM" />
    <h2 id="look-nome"></h2>
    <p id="look-desc"></p>
    <a id="look-btn" class="btn-explorar" href="#">Ver coleção</a>
  </div>
</div>

<script src="../assets/js/index.js?v=5.0"></script>

</body>
</html>
