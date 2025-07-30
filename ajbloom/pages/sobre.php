<?php session_start(); 
$base = '/';?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conheça nossa historia AJBloom</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&family=Montserrat:wght@400;600&family=Dancing+Script&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/index.css?v8.0" />
    <style>
        /* Ajustes específicos para a página sobre */
        body {
            padding-top: clamp(120px, 20vw, 180px); /* Espaço para header + letreiro */
        }

        /* BANNER INSTITUCIONAL */
        .about-banner {
            background: linear-gradient(rgba(185, 183, 219, 0.8), rgba(108, 99, 255, 0.8)), 
                        url('../assets/imagens/parallax.png') center/cover no-repeat;
            height: 70vh;
            display: flex;
            align-items: center;
            color: white;
            margin-bottom: 60px;
        }
        
        .banner-content {
            max-width: 900px;
            padding: 0 var(--container-padding);
        }
        
        .banner-content h1 {
            font-size: clamp(2rem, 6vw, 2.8rem);
            margin-bottom: 20px;
            line-height: 1.3;
            font-weight: 700;
        }
        
        .banner-content p {
            font-size: clamp(1rem, 3vw, 1.2rem);
            margin-bottom: 30px;
            opacity: 0.95;
        }

        /* SEÇÃO SOBRE */
        .about-section {
            padding: clamp(3rem, 8vw, 5rem) 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: clamp(2rem, 5vw, 3rem);
        }
        
        .section-title h2 {
            font-size: clamp(2rem, 5vw, 2.5rem);
            color: var(--cor-principal);
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .section-title p {
            color: var(--texto-claro, #777);
            max-width: 700px;
            margin: 0 auto;
            font-size: clamp(1rem, 3vw, 1.1rem);
        }
        
        .about-content {
            display: flex;
            gap: clamp(2rem, 5vw, 3rem);
            align-items: center;
            margin-bottom: clamp(3rem, 6vw, 4rem);
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 var(--container-padding);
        }
        
        .about-text {
            flex: 1;
        }
        
        .about-text h3 {
            font-size: clamp(1.5rem, 4vw, 1.8rem);
            margin-bottom: 20px;
            color: var(--texto);
            line-height: 1.4;
            font-weight: 600;
        }
        
        .about-text p {
            margin-bottom: 20px;
            color: var(--texto-claro, #777);
            font-size: clamp(1rem, 3vw, 1.05rem);
            line-height: 1.7;
        }
        
        .about-image {
            flex: 1;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(144, 183, 235, 0.15);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s ease;
        }
        
        .about-image:hover img {
            transform: scale(1.03);
        }

        /* SEÇÃO VALORES */
        .values-section {
            background-color: var(--fundo-geral, #eeeeee);
            padding: clamp(3rem, 8vw, 5rem) 0;
        }
        
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr));
            gap: clamp(1.5rem, 4vw, 2rem);
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--container-padding);
        }
        
        .value-card {
            background: white;
            padding: clamp(1.5rem, 4vw, 2rem);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .value-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .value-icon {
            font-size: clamp(2rem, 6vw, 2.5rem);
            color: var(--cor-principal);
            margin-bottom: clamp(1rem, 3vw, 1.5rem);
        }
        
        .value-card h4 {
            font-size: clamp(1.1rem, 3vw, 1.3rem);
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .value-card p {
            color: var(--texto-claro, #777);
            line-height: 1.6;
            font-size: clamp(0.9rem, 2.5vw, 1rem);
        }

        /* SEÇÃO FAQ */
        .faq-section {
            padding: clamp(3rem, 8vw, 5rem) 0;
            background-color: white;
        }
        
        .faq-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 var(--container-padding);
        }
        
        .faq-item {
            margin-bottom: 15px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e1e5e9;
        }
        
        .faq-question {
            padding: clamp(1rem, 3vw, 1.5rem) clamp(1.5rem, 4vw, 2rem);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            background-color: #fafafa;
            font-size: clamp(1rem, 3vw, 1.1rem);
        }
        
        .faq-question:hover {
            background-color: rgba(144, 183, 235, 0.1);
        }
        
        .faq-question i {
            margin-left: 15px;
            transition: transform 0.3s ease;
            color: var(--cor-principal);
            font-size: 1.2rem;
        }
        
        .faq-question.active i {
            transform: rotate(180deg);
        }
        
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .faq-answer-inner {
            padding: 0 clamp(1.5rem, 4vw, 2rem) clamp(1rem, 3vw, 1.5rem);
            color: var(--texto-claro, #777);
            line-height: 1.7;
            font-size: clamp(0.95rem, 2.5vw, 1rem);
        }
        
        .faq-item.active .faq-answer {
            max-height: 300px;
        }

        /* Botão padrão */
        .btn {
            display: inline-block;
            padding: clamp(0.75rem, 3vw, 1rem) clamp(1.5rem, 4vw, 2rem);
            background: linear-gradient(135deg, var(--cor-principal), #a8c5f0);
            color: white;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: clamp(0.9rem, 2.5vw, 1rem);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(144, 183, 235, 0.4);
        }

        /* RESPONSIVIDADE */
        @media (max-width: 992px) {
            .about-content {
                flex-direction: column;
            }
            
            .about-image {
                order: -1;
                max-width: 600px;
                margin: 0 auto;
            }
        }
        
        @media (max-width: 768px) {
            .about-banner {
                height: 50vh;
            }

            .values-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding-top: clamp(100px, 18vw, 140px);
            }
            
            .about-banner {
                height: 40vh;
            }
            
            .about-section, .values-section, .faq-section {
                padding: clamp(2rem, 6vw, 3rem) 0;
            }
        }
    </style>
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
           
            <!-- Logotipo sempre centralizado -->
            <picture class="site-logo">
                <img src="../assets/imagens/logo/logo.webp" alt="AJbloom" class="site-logo__img" />
            </picture> 

            <!-- Menu desktop (direita) -->
            <nav class="desktop-menu" aria-label="Menu principal">
                <ul class="menu-desktop">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="vitrine.php">Produtos</a></li>
                    <li><a href="sobre.php" class="active">Sobre nós</a></li>
                </ul>
            </nav>
        </div>

        <!-- Menu lateral -->
        <nav id="side-menu" class="menu-lateral" aria-hidden="true" aria-labelledby="menu-toggle" tabindex="-1">
            <button id="close-menu" aria-label="Fechar menu">×</button>
            <ul class="menu-lista">
                <li><a href="index.php">Home</a></li>
                <li><a href="vitrine.php">Produtos</a></li>
                <li><a href="sobre.php">Sobre nós</a></li>
            </ul>
        </nav>
    </header>

    <div id="overlay"></div>

    <!-- BANNER INSTITUCIONAL -->
    <section class="about-banner">
        <div class="container">
            <div class="banner-content">
                <h1>Sua beleza floresce com a AJbloom</h1>
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
                    <img src="../assets/imagens/anna.webp" alt="Fundadoras da AJBloom em seu laboratório">
                </div>
            </div>
            
            <div class="about-content">
                <div class="about-image">
                    <img src="../assets/imagens/anna.webp" alt="Linha de produtos AJBloom">
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
                        <span>Quais formas de pagamento ajbloom aceita? </span>
                            
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
                        <span>Como faço para solicitar uma troca?

                        </span>
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
                        <span>Em quanto tempo posso solicitar uma troca?</span>
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
                        <span>Qual horario de Atendimento?  </span>
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
                    <a href="https://wa.me/5541999999999?text=Ol%C3%A1%2C+gostaria+de+saber+mais+sobre+os+produtos+da+AJBLOOM%21">Trocas de produtos</a>
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

    <!-- Botão WhatsApp -->
    <a href="https://wa.me/5541999999999?text=Ol%C3%A1%2C+gostaria+de+saber+mais+sobre+os+produtos+da+AJBLOOM%21" class="whatsapp-btn" target="_blank" aria-label="Fale conosco pelo WhatsApp">
        <img src="https://img.icons8.com/ios-filled/50/ffffff/whatsapp--v1.png" alt="WhatsApp" loading="lazy" />
    </a>

    <script src="../assets/js/index.js?v=8.0"></script>
    <script>
        // FAQ Accordion
        document.addEventListener('DOMContentLoaded', function() {
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', function() {
                    const item = this.parentElement;
                    const isActive = item.classList.contains('active');
                    
                    // Fecha todos os itens
                    faqQuestions.forEach(q => {
                        q.parentElement.classList.remove('active');
                        q.classList.remove('active');
                    });
                    
                    // Abre o item clicado se não estava ativo
                    if (!isActive) {
                        item.classList.add('active');
                        this.classList.add('active');
                    }
                });
            });

            // Smooth scrolling para links internos
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    if (this.getAttribute('href') !== '#') {
                        e.preventDefault();
                        
                        const targetId = this.getAttribute('href');
                        const targetElement = document.querySelector(targetId);
                        
                        if (targetElement) {
                            const headerHeight = document.querySelector('header').offsetHeight + 40;
                            window.scrollTo({
                                top: targetElement.offsetTop - headerHeight,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            });

            console.log('✅ Página Sobre carregada com header e rodapé migrados do index.php');
        });
    </script>
</body>
</html>
