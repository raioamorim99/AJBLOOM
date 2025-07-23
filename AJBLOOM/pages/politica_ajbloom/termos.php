<?php
// Configurações da loja
$nome_loja = "ajbloom";
$email_contato = "contato@ajblom.com.br";
$telefone = "(11) 99999-9999";
$endereco = "Rua das Flores, 123 - São Paulo, SP";
$data_atualizacao = "22 de julho de 2025";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos de Serviço - <?php echo ucfirst($nome_loja); ?></title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
    

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #007bff;
        }

        .logo {
            font-size: 2.5em;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        h1 {
            color: #2c3e50;
            font-size: 2.2em;
            margin-bottom: 10px;
        }

        .last-update {
            color: #666;
            font-style: italic;
            font-size: 0.9em;
        }

        h2 {
            color: #007bff;
            font-size: 1.5em;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e9ecef;
        }

        h3 {
            color: #495057;
            font-size: 1.2em;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 15px;
            text-align: justify;
        }

        ul, ol {
            margin-left: 20px;
            margin-bottom: 15px;
        }

        li {
            margin-bottom: 8px;
        }

        .highlight {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
            border-radius: 5px;
        }

        .contact-info {
            background-color: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            border-left: 4px solid #007bff;
        }

        .contact-info h3 {
            color: #007bff;
            margin-top: 0;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            color: #666;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 15px;
                border-radius: 5px;
            }

            .logo {
                font-size: 2em;
            }

            h1 {
                font-size: 1.8em;
            }

            h2 {
                font-size: 1.3em;
            }
        }

        .section {
            margin-bottom: 25px;
        }

        strong {
            color: #2c3e50;
        }

        .warning {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
            margin: 20px 0;
        }
        .back-to-store {
            background-color: #25d366;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 25px;
            display: inline-block;
            margin: 10px 0;
            font-weight: bold;
        }
        .back-to-store:hover {
            background-color: #000000ff;
            color: white;
            text-decoration: none;
        }
        
        
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo"><?php echo $nome_loja; ?></div>
            <h1>Termos de Serviço</h1>
            <p class="last-update">Última atualização: <?php echo $data_atualizacao; ?></p>
        </div>

         <div class="back-to-store">
        <a href="../index.php" class="back-to-store-btn">
            Voltar à Loja
        </a>
    </div>

        <div class="section">
            <h2>1. Aceitação dos Termos</h2>
            <p>Ao acessar e utilizar o site da <strong><?php echo ucfirst($nome_loja); ?></strong>, você concorda em cumprir e estar vinculado aos seguintes termos e condições de uso. Se você não concordar com qualquer parte destes termos, não deve usar nossos serviços.</p>
        </div>


   

        <div class="section">
            <h2>2. Definições</h2>
            <ul>
                <li><strong>"Empresa"</strong>: refere-se à <?php echo ucfirst($nome_loja); ?></li>
                <li><strong>"Usuário"</strong>: qualquer pessoa que acesse ou use nossos serviços</li>
                <li><strong>"Produtos"</strong>: todos os itens disponíveis para venda em nossa loja</li>
                <li><strong>"Serviços"</strong>: todos os serviços oferecidos pela nossa empresa</li>
            </ul>
        </div>

        <div class="section">
            <h2>3. Uso do Site</h2>
            <h3>3.1 Condições de Uso</h3>
            <p>Você pode usar nosso site para:</p>
            <ul>
                <li>Visualizar e comprar produtos</li>
                <li>Criar uma conta de usuário</li>
                <li>Acessar informações sobre produtos e serviços</li>
                <li>Entrar em contato conosco</li>
            </ul>

            <h3>3.2 Restrições</h3>
            <p>É proibido:</p>
            <ul>
                <li>Usar o site para fins ilegais ou não autorizados</li>
                <li>Tentar hackear ou comprometer a segurança do site</li>
                <li>Reproduzir, duplicar ou copiar conteúdo sem autorização</li>
                <li>Transmitir vírus ou códigos maliciosos</li>
            </ul>
        </div>

        <div class="section">
            <h2>4. Produtos e Preços</h2>
            <h3>4.1 Disponibilidade</h3>
            <p>Todos os produtos estão sujeitos à disponibilidade. Reservamo-nos o direito de descontinuar qualquer produto a qualquer momento.</p>

            <h3>4.2 Preços</h3>
            <p>Os preços dos produtos são apresentados em Reais (R$) e podem ser alterados sem aviso prévio. Os preços incluem impostos aplicáveis, quando especificado.</p>

            <div class="highlight">
                <strong>Importante:</strong> Os preços válidos são aqueles exibidos no momento da finalização da compra.
            </div>
        </div>

        <div class="section">
            <h2>5. Pedidos e Pagamentos</h2>
            <h3>5.1 Processamento de Pedidos</h3>
            <p>Todos os pedidos estão sujeitos à nossa aceitação. Reservamo-nos o direito de recusar ou cancelar pedidos por qualquer motivo.</p>

            <h3>5.2 Formas de Pagamento</h3>
            <p>Aceitamos as seguintes formas de pagamento:</p>
            <ul>
                <li>Cartões de crédito (Visa, Mastercard, American Express)</li>
                <li>Cartões de débito</li>
                <li>PIX</li>
                <li>Boleto bancário</li>
            </ul>
        </div>

        <div class="section">
            <h2>6. Entrega</h2>
            <h3>6.1 Prazos</h3>
            <p>Os prazos de entrega são estimados e podem variar conforme a localização e disponibilidade do produto. Não nos responsabilizamos por atrasos causados por terceiros.</p>

            <h3>6.2 Frete</h3>
            <p>Os custos de frete são calculados no momento da compra e podem variar conforme:</p>
            <ul>
                <li>Localização de entrega</li>
                <li>Peso e dimensões do produto</li>
                <li>Modalidade de entrega escolhida</li>
            </ul>
        </div>

        <div class="section">
            <h2>7. Política de Troca e Devolução</h2>
            <h3>7.1 Direito de Arrependimento</h3>
            <p>Conforme o Código de Defesa do Consumidor, você tem até 7 dias corridos para desistir da compra, contados a partir do recebimento do produto.</p>

            <h3>7.2 Condições para Troca</h3>
            <p>Para trocas e devoluções, o produto deve estar:</p>
            <ul>
                <li>Em perfeito estado de conservação</li>
                <li>Na embalagem original</li>
                <li>Acompanhado da nota fiscal</li>
                <li>Sem sinais de uso</li>
            </ul>
        </div>

        <div class="section">
            <h2>8. Privacidade e Proteção de Dados</h2>
            <p>Respeitamos sua privacidade e protegemos seus dados pessoais conforme a Lei Geral de Proteção de Dados (LGPD). Para mais informações, consulte nossa Política de Privacidade.</p>

            <div class="warning">
                <strong>Atenção:</strong> Nunca compartilhamos seus dados pessoais com terceiros sem sua autorização expressa.
            </div>
        </div>

        <div class="section">
            <h2>9. Propriedade Intelectual</h2>
            <p>Todo o conteúdo do site, incluindo textos, imagens, logos, marcas e design, são propriedade da <strong><?php echo ucfirst($nome_loja); ?></strong> e estão protegidos por direitos autorais.</p>
        </div>

        <div class="section">
            <h2>10. Limitação de Responsabilidade</h2>
            <p>A <strong><?php echo ucfirst($nome_loja); ?></strong> não se responsabiliza por:</p>
            <ul>
                <li>Danos indiretos ou consequenciais</li>
                <li>Perda de lucros ou oportunidades</li>
                <li>Interrupções no serviço por motivos técnicos</li>
                <li>Ações de terceiros</li>
            </ul>
        </div>

        <div class="section">
            <h2>11. Modificações dos Termos</h2>
            <p>Reservamo-nos o direito de modificar estes termos a qualquer momento. As alterações entrarão em vigor imediatamente após sua publicação no site.</p>
        </div>

        <div class="section">
            <h2>12. Lei Aplicável</h2>
            <p>Estes termos são regidos pelas leis brasileiras. Qualquer disputa será resolvida no foro da comarca de São Paulo/SP.</p>
        </div>

        <div class="contact-info">
            <h3>Informações de Contato</h3>
            <p><strong>Empresa:</strong> <?php echo ucfirst($nome_loja); ?></p>
            <p><strong>E-mail:</strong> <?php echo $email_contato; ?></p>
            <p><strong>Telefone:</strong> <?php echo $telefone; ?></p>
            <p><strong>Endereço:</strong> <?php echo $endereco; ?></p>
        </div>

        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> <?php echo ucfirst($nome_loja); ?>. Todos os direitos reservados.</p>
            <p>Este documento foi gerado automaticamente e é válido a partir de <?php echo $data_atualizacao; ?>.</p>
        </div>
    </div>
</body>
</html>