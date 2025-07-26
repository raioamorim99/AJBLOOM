<?php session_start(); 
$base = '/';?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conheça nossa historia AJBloom</title>
   <link rel="stylesheet" href="../assets/css/sobre.css?v=2">

</head>
<body>
    <!-- CABEÇALHO -->
    <header>
        <div class="header-container container">
            <a href="index.php" class="logo">
                <img src="../assets/imagens/logo/logo.png" alt="AJBloom Logo">
            </a>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            
            <nav class="main-nav" id="mainNav">
                <a href="index.php">Home</a>
                <a href="vitrine.php">Produtos</a>
                <a href="sobre.html" class="active">Sobre Nós</a>
            </nav>
        </div>
    </header>

    <!-- BANNER INSTITUCIONAL -->
    <section class="about-banner">
        <div class="container">
            <div class="banner-content">
                <h1>Sua beleza floresce com a AJBloom</h1>
                <p>Descubra nossa história e os valores que nos tornam referência em cosméticos naturais</p>
                <a href="#nossa-historia" class="btn">Conheça nossa história</a>
            </div>
        </div>
    </section>

    <!-- SEÇÃO NOSSA HISTÓRIA -->
    <section class="about-section" id="nossa-historia">
        <div class="container">
            <div class="section-title">
                <h2>Nossa História</h2>
                <p>Como uma pequena startup se transformou em referência no mercado de cosméticos naturais</p>
            </div>
            
            <div class="about-content">
                <div class="about-text">
                    <h3>Do sonho à realidade</h3>
                    <p>Fundada em 2015 por duas amigas apaixonadas por bem-estar e sustentabilidade, a AJBloom surgiu da vontade de criar produtos de beleza que respeitassem tanto a pele quanto o meio ambiente.</p>
                    <p>Começamos em uma pequena cozinha, testando fórmulas naturais e orgânicas, até desenvolver nossa primeira linha de produtos que hoje são sucesso nacional.</p>
                    <p>Ao longo dos anos, mantivemos nosso compromisso com ingredientes puros, práticas sustentáveis e resultados comprovados, conquistando a confiança de milhares de clientes.</p>
                </div>
                
                <div class="about-image">
                    <img src="../assets/imagens/ajbloom-branco.JPG" alt="Fundadoras da AJBloom em seu laboratório">
                </div>
            </div>
            
            <div class="about-content">
                <div class="about-image">
                    <img src="../assets/imagens/teste.jpg" alt="Linha de produtos AJBloom">
                </div>
                
                <div class="about-text">
                    <h3>Inovação com propósito</h3>
                    <p>Nossa equipe de pesquisadores trabalha constantemente para desenvolver fórmulas inovadoras que combinam os benefícios da natureza com a ciência moderna.</p>
                    <p>Todos os nossos produtos são cruelty-free, veganos e produzidos com ingredientes orgânicos certificados. Acreditamos que beleza e sustentabilidade devem caminhar juntas.</p>
                    <p>Nossas embalagens são desenvolvidas com materiais reciclados e recicláveis, reduzindo nosso impacto ambiental em todas as etapas do processo.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SEÇÃO VALORES -->
    <section class="values-section">
        <div class="container">
            <div class="section-title">
                <h2>Nossos Valores</h2>
                <p>Princípios que guiam cada decisão da nossa empresa</p>
            </div>
            
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h4>Sustentabilidade</h4>
                    <p>Compromisso com práticas ecológicas em toda nossa cadeia produtiva, da matéria-prima à embalagem.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Qualidade</h4>
                    <p>Utilizamos apenas ingredientes puros e orgânicos, com rigorosos padrões de controle de qualidade.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h4>Transparência</h4>
                    <p>Divulgamos todos os ingredientes de nossos produtos e mantemos comunicação aberta com nossos clientes.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h4>Inovação</h4>
                    <p>Pesquisa contínua para desenvolver fórmulas eficazes que combinam ciência e natureza.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SEÇÃO PERGUNTAS FREQUENTES -->
    <section class="faq-section">
        <div class="container">
            <div class="section-title">
                <h2>Dúvidas Frequentes</h2>
                <p>Encontre respostas para as perguntas mais comuns sobre a AJBloom</p>
            </div>
            
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Os produtos AJBloom são testados em animais?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            <p>Não, todos os nossos produtos são cruelty-free. Não realizamos testes em animais em nenhuma etapa de produção e somos certificados pela Leaping Bunny, o padrão ouro em certificação cruelty-free.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Vocês oferecem produtos veganos?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            <p>Sim, nossa linha completa é 100% vegana. Não utilizamos nenhum ingrediente de origem animal e nossos produtos são certificados como veganos pela Vegan Society.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Como posso saber se um produto é adequado para meu tipo de pele?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            <p>Todos os nossos produtos contêm descrições detalhadas para diferentes tipos de pele. Além disso, oferecemos uma ferramenta online de diagnóstico de pele em nosso site e nossa equipe de especialistas está disponível para orientação via chat ou e-mail.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Vocês realizam entregas internacionais?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            <p>Atualmente entregamos para todo o território nacional brasileiro. Estamos trabalhando para expandir nossas operações para outros países da América do Sul em breve.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Qual é a política de devolução?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            <p>Aceitamos devoluções dentro de 30 dias após a compra por qualquer motivo. Produtos usados ou parcialmente usados também são aceitos desde que pelo menos 70% do produto permaneça.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- RODAPÉ -->
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
                </div>

                <div class="footer-column">
                    <h3>Informações</h3>
                    <a href="trocas.html">Trocas e Devoluções</a>
                    <a href="termos.html">Termos de Serviço</a>
                    
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

    <script src="../assets/js/sobre.js"></script>
</body>
</html>
