<?php session_start(); ?>

<?php
if (isset($_SESSION["mensagem_sucesso"])) {
  echo '<div class="mensagem-sucesso">' . $_SESSION["mensagem_sucesso"] . '</div>';
  unset($_SESSION["mensagem_sucesso"]);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="A Ajbloom é uma loja de moda feminina que oferece estilo, autenticidade e personalidade. Explore nossas novidades.">
  <title>Ajbloom</title>
  <!-- Font Awesome para os ícones de login/cadastro -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Raleway&family=Montserrat:wght@400;600&family=Dancing+Script&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/index.css" />
</head>
<body>

  <!-- Letreiro Promocional Fixo -->
  <div class="ajb-letreiro-topo">
    <div class="ajb-letreiro-texto">
  Explore a coleção Ajbloom | moda que floresce com você! 
 </div>
  </div>

<header class="header">
  

  <!-- Botão de menu Hamburguer --> 
<button id="menu-toggle" aria-label="Abrir menu">☰</button>

  <div class="topo">
    
  <!-- Barra de pesquisa à esquerda -->
  <div class="ajb-header-search">
    <input
      type="text"
      class="search-input"
      placeholder="Buscar produto..."
      aria-label="Buscar produto"
    />
  </div>

  <!-- Logotipo central -->

<picture class="site-logo">
  <img src="../assets/imagens/logo/logo.png" alt="AJbloom" class="site-logo__img" />
</picture> 


  <!-- Menu desktop à direita -->
  <nav class="desktop-menu" aria-label="Menu principal">
    <ul class="menu-desktop">
      <li><a href="index.php" class="home-link">Home</a></li>
      <li><a href="#destaques">Catálogo</a></li>
      <li><a href="sobre.html">Sobre nós</a></li>
    </ul>
  </nav>
</div>


<div class="user-actions">
    <?php if (isset($_SESSION["usuario_id"])): ?>
      <span class="user-link">
        <i ></i> Olá, <?php echo htmlspecialchars($_SESSION["usuario_nome"]); ?>
      </span>
      <a href="../backend/logout.php" class="user-link">
        <i class="fas fa-sign-out-alt"></i> Sair
      </a>
    <?php else: ?>
      <a href="form-login.php" class="user-link">
        <i class="fas fa-sign-in-alt"></i> Entrar
      </a>
      <a href="form-cadastro.php" class="user-link">
        <i class="fas fa-user-plus"></i> Cadastrar
      </a>
    <?php endif; ?>
  </div>

  <!-- Menu lateral -->
  <nav id="side-menu" class="menu-lateral" aria-hidden="true" aria-labelledby="menu-toggle" tabindex="-1">
    <button id="close-menu" aria-label="Fechar menu">×</button>
   <ul class="menu-lista">
  <li><a href="index.html" class="home-link">Home</a></li>
  <li><a href="#destaques">Catálogo</a></li>
  <li><a href="sobre.html">Sobre nós</a></li>
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
      <source media="(max-width: 768px)" srcset="../assets/imagens/banners/bem-vinda-mobile.jpg">
      <img src="../assets/imagens/banners/banner-bem-vinda.jpg" alt="Bem-vinda à AJBLOOM" />
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
      <source media="(max-width: 768px)" srcset="../assets/imagens/banners/vista-se_de_ajbloom-mobile.jpg">
      <img src="../assets/imagens/banners/vista-se_de_ajbloom.jpg" alt="Banner 1" loading="lazy" />
    </picture>
  </div>

  <!-- ✅ SLIDE 3 -->
  <div class="slide">
    <picture>
      <source media="(max-width: 768px)" srcset="../assets/imagens/banners/estilo_que_acompanha-mobile.jpg">
      <img src="../assets/imagens/banners/estilo_que_acompanha.jpg" alt="Banner 2" loading="lazy" />
    </picture>
  </div>

  <!-- ✅ SLIDE 4 -->
  <div class="slide">
    <picture>
      <source media="(max-width: 768px)" srcset="../assets/imagens/banners/voce_ganhou-mobile.jpg">
      <img src="../assets/imagens/banners/voce_ganhou.jpg" alt="Banner 3" loading="lazy" />
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



<!-- filtro -->
<section id="destaques" class="destaques container animate-on-scroll">
  <div class="section-header">
    <select id="filtro-categoria">
      <option value="tudo">Todas Categorias</option>
      <option value="camisetas">Camisetas</option>
      <option value="vestidos">Vestidos</option>
      <option value="acessorios">Acessórios</option>
    </select>
  </div>
  <div id="produtos" class="produtos-grid"></div>
  <button id="ver-mais" class="botao-ver-mais" style="margin-top: 20px;">Ver mais</button>
</section>



<!-- VIVA O ESTILO AJBLOOM  -->
<section class="ajb-estilo animate-on-scroll">
  <div class="container">
    <h2 class="titulo-estilo">Viva o Estilo AJBLOOM</h2>
    <p class="subtitulo-estilo">Muito mais que roupas. Uma forma de se expressar.</p>

    <div class="estilo-grid">
      <div class="estilo-card estilo1">
        <div class="estilo-conteudo">
          <h3>Estilo & Atitude</h3>
          <p>Peças que acompanham sua energia em qualquer ocasião.</p>
          <a href="#sobre" class="btn-estilo">Saiba mais</a>
        </div>
      </div>
      <div class="estilo-card estilo2">
        <div class="estilo-conteudo">
          <h3>Lançamentos</h3>
          <p>Novidades toda sexta. Não perca as peças queridinhas da semana!</p>
          <a href="#destaques" class="btn-estilo">Ver lançamentos</a>
        </div>
      </div>
      <div class="estilo-card estilo3">
        <div class="estilo-conteudo">
          <h3>Promoções Exclusivas</h3>
          <p>Descontos incríveis em produtos selecionados. Aproveite agora.</p>
          <a href="#produtos" class="btn-estilo">Ver ofertas</a>
        </div>
      </div>
    </div>

    <div class="cta-estilo">
      <p>✨ Conheça nossa nova coleção inspirada na leveza do verão.</p>
      <a href="#produtos" class="btn-explorar">Explorar agora</a>
    </div>
  </div>
</section>

<!-- Instagram Feed -->
<section class="ajb-insta-feed">
  <h3>Nos siga no Instagram</h3>
  <a href="https://www.instagram.com/Ajbloom_" target="_blank">@Ajbloom_</a> 

  <div class="insta-grid">
    <a href="https://www.instagram.com/Ajbloom_" target="_blank">
      <img src="../assets/imagens/parallax.png" alt="Insta 1" loading="lazy">
    </a>
    <a href="https://www.instagram.com/Ajbloom_" target="_blank">
      <img src="../assets/imagens/teste.jpg" alt="Insta 2" loading="lazy">
    </a>
    <a href="https://www.instagram.com/Ajbloom_" target="_blank">
      <img src="../assets/imagens/ajbloom-branco.JPG" alt="Insta 3" loading="lazy">
    </a>
    <a href="https://www.instagram.com/Ajbloom_" target="_blank">
      <img src="../assets/imagens/ajbloom-branco.JPG" alt="Insta 4" loading="lazy">
    </a>
    <a href="https://www.instagram.com/Ajbloom_" target="_blank">
      <img src="../assets/imagens/ajbloom-branco.JPG" alt="Insta 5" loading="lazy">
    </a>
    <a href="https://www.instagram.com/Ajbloom_" target="_blank">
      <img src="../assets/imagens/ajbloom-branco.JPG" alt="Insta 6" loading="lazy">
    </a>
  </div>
</section>




<section class="ajb-parallax-banner">
  <div class="ajb-parallax-content">
    <h2>Sinta. Vista. Floresça.</h2>
    <p>A sua essência merece estar presente em cada detalhe.</p>
  </div>
</section>

 <section class="ajb-lookbook" id="lookbook">
  <!-- Contêiner das pétalas flutuantes -->
  <div class="petalas-container" id="petalas-lookbook"></div>

  <h3 class="lookbook-titulo">LOOKBOOK Ajbloom</h3>

  <div class="lookbook-scroll">
    <div class="lookbook-item" style="background-image: url('../assets/imagens/teste.jpg');" loading="lazy"/>
      <span>Coleção Floral</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/teste.jpg');" loading="lazy"/>
      <span>Casual Elegante</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/teste.jpg');" loading="lazy"/>
      <span>Summer Vibes</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/teste.jpg');" loading="lazy"/>
      <span>Street Bloom</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/teste.jpg');"  loading="lazy"/>
      <span>Minimal Bloom</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/teste.jpg');" loading="lazy"/>
      <span>Denim Garden</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/teste.jpg');" loading="lazy"/>
      <span>Fresh Elegance</span>
    </div>
    <div class="lookbook-item" style="background-image: url('../assets/imagens/teste.jpg');" loading="lazy"/>
      <span>AJ Girl</span>
    </div>
  </div>
</section>


<section class="ajb-essencia-section animate-on-scroll">
  <div class="ajb-essencia-grid">
    <div class="essencia-texto">
      <h2>Não é só roupa. É a sua história.</h2>
      <p>Cada peça AJBLOOM é pensada para mulheres autênticas, fortes e leves. Use com orgulho o que te representa.</p>
      <a href="sobre.html" class="btn-explorar">Conheça nossa jornada</a>
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
    <img src="https://img.icons8.com/color/48/delivery.png" alt="Entrega" loading="lazy"/>
    <p>Entrega rápida</p>
  </div>
  <div class="ajb-valor">
    <img src="https://img.icons8.com/color/48/checked.png" alt="Qualidade" loading="lazy"/>
    <p>Qualidade garantida</p>
  </div>
  <div class="ajb-valor">
    <img src="https://img.icons8.com/color/48/customer-support.png" alt="Suporte" loading="lazy"/>
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
        <img src="../assets/imagens/ajbloom-branco.JPG" alt="Qualidade" loading="lazy" />
        <h4>Qualidade</h4>
      </div>
      <div class="info-card">
        <img src="../assets/imagens/ajbloom-branco.JPG" alt="Confiança" loading="lazy" />
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
                        <img src="../assets/imagens/luan_raio.jpg" alt="Logo LR" class="logo-lr-img" loading="lazy" />
                        Criado por :)  
                        <a href="https://www.instagram.com/luantech.oficial" target="_blank">Luan Raio</a> | 
                        <a href="https://luanraio.dev" target="_blank">Portfólio</a>
                    </p>
                    <p>📍 Curitiba - PR</p>
                    <p>✉️ Ajbloomshop@gmail.com</p>
                </div>

                <div class="footer-column">
                    <h3>Links Rápidos</h3>
                    <a href="index.html">Home</a>
                    <a href="produtos.html">Produtos</a>
                    <a href="sobre.html">Sobre Nós</a>
                    <a href="blog.html">Blog</a>
                    <a href="contato.html">Contato</a>
                </div>

                <div class="footer-column">
                    <h3>Informações</h3>
                    <a href="politica.html">Política de Privacidade</a>
                    <a href="trocas.html">Trocas e Devoluções</a>
                    <a href="termos.html">Termos de Serviço</a>
                    <a href="faq.html">FAQ</a>
                    <a href="trabalhe.html">Trabalhe Conosco</a>
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
    </footer>
    
<a href="https://wa.me/5541999999999?text=Ol%C3%A1%2C+gostaria+de+saber+mais+sobre+os+produtos+da+AJBLOOM%21" class="whatsapp-btn" target="_blank" aria-label="Fale conosco pelo WhatsApp">
  <img src="https://img.icons8.com/ios-filled/50/ffffff/whatsapp--v1.png" alt="WhatsApp" loading="lazy" />
</a>

<!-- modal -->

<div id="look-modal" class="look-modal">
  <div class="look-backdrop" onclick="fecharLookModal()"></div>
  <div class="look-conteudo">
    <button class="look-fechar" onclick="fecharLookModal()">×</button>
    <img id="look-img" src="" alt="Look AJBLOOM" />
    <h2 id="look-nome"></h2>
    <p id="look-desc"></p>
    <a id="look-btn" class="btn-explorar" href="#">Ver coleção</a>
  </div>
</div>

  <script src="../assets/js/index.js?v=3"></script>

</body>
</html>