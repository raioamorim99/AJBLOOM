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
    <title>Termos de Serviço - Ajbloom</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #6d4c41;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
        }

        h2 {
            color: #8d6e63;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        p, li {
            margin-bottom: 15px;
        }

        .back-button-container {
            text-align: center;
            margin-top: 30px;
        }

        .back-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #90b7eb;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .back-button:hover {
            background-color: #ffffffff;
            transform: translateY(-2px);S
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .whatsapp-button {
            display: inline-block;
            background-color: #25D366;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .whatsapp-button:hover {
            background-color: #128C7E;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Termos de Serviço – Ajbloom</h1>
        
        <p>Bem-vindo(a) à Ajbloom. Ao acessar ou utilizar o nosso site e realizar compras em nossa loja, você concorda com os termos e condições descritos abaixo. Recomendamos a leitura atenta deste documento antes de concluir qualquer pedido.</p>

        <h2>1. Identificação da Loja</h2>
        <p>Razão Social: Ajbloom shop online</p>
        <p>Nome Fantasia: Ajbloom</p>
        <p>E-mail para contato: <a href="mailto:ajbloomshop@gmail.com">ajbloomshop@gmail.com</a></p>
        <p>WhatsApp para atendimento: <a href="https://wa.me/5511999999999" class="whatsapp-button" id="whatsappBtn">(11) 99999-9999</a></p>

        <h2>2. Aceitação dos Termos</h2>
        <p>Ao acessar, navegar ou realizar uma compra no site da Ajbloom, o usuário declara ter lido, compreendido e aceitado todos os termos aqui descritos. Caso não concorde com qualquer condição, recomendamos que não utilize os serviços da loja.</p>

        <h2>3. Produtos e Informações</h2>
        <p>Todos os produtos disponibilizados no site da Ajbloom estão descritos com o máximo de clareza e precisão possível, incluindo informações sobre tamanhos, cores, materiais e preços.</p>
        <p><strong>Atenção:</strong> As cores dos produtos podem variar de acordo com a tela do seu dispositivo.</p>

        <h2>4. Preços e Pagamento</h2>
        <p>Os preços apresentados no site estão em reais (R$) e são válidos apenas para compras realizadas online. Reservamo-nos o direito de alterar preços e condições a qualquer momento, sem aviso prévio.</p>
        <p>Aceitamos os seguintes métodos de pagamento: dinheiro, pix, cartão de débito, cartão de crédito em até 3x (com acréscimo de taxa da maquininha).</p>

        <h2>5. Política de Trocas</h2>
        <p>A Ajbloom realiza trocas em até 7 dias corridos após o recebimento do produto, desde que:</p>
        <ul>
            <li>A peça esteja em perfeito estado, sem sinais de uso, com etiqueta afixada e na embalagem original.</li>
            <li>O cliente entre em contato pelo e-mail ou WhatsApp informando o número do pedido e o motivo da troca.</li>
            <li>Não realizamos devoluções com reembolso, apenas trocas por outro tamanho ou produto, conforme disponibilidade em estoque.</li>
            <li>Produtos que não atenderem às condições acima ou que forem enviados fora do prazo não serão aceitos.</li>
        </ul>

        <h2>6. Propriedade Intelectual</h2>
        <p>Todos os conteúdos presentes no site da Ajbloom — incluindo logotipo, nome, imagens de produtos, descrições, textos e layout — são protegidos por direitos autorais. É proibida qualquer reprodução, distribuição ou uso sem autorização prévia e por escrito.</p>

        <h2>7. Privacidade e Segurança</h2>
        <p>Seus dados pessoais são utilizados exclusivamente para o processamento dos pedidos e comunicação com a loja. Garantimos o sigilo das informações, que não serão compartilhadas com terceiros, exceto quando necessário para concluir sua compra.</p>
        <p>Para mais informações, consulte nossa Política de Privacidade.</p>

        <h2>8. Responsabilidade do Usuário</h2>
        <p>Ao utilizar nosso site, o cliente compromete-se a fornecer informações verdadeiras, completas e atualizadas. Também se compromete a não utilizar a loja para fins ilegais, ofensivos ou que infrinjam os direitos da Ajbloom ou de terceiros.</p>

        <h2>9. Modificações dos Termos</h2>
        <p>A Ajbloom reserva-se o direito de alterar estes Termos de Serviço a qualquer momento, sendo responsabilidade do cliente consultá-los regularmente. Alterações entrarão em vigor imediatamente após sua publicação no site.</p>

        <h2>10. Atendimento ao Cliente</h2>
        <p>Em caso de dúvidas, sugestões ou solicitações, entre em contato com a nossa equipe:</p>
        <p>📧 E-mail: <a href="mailto:ajbloomshop@gmail.com">ajbloomshop@gmail.com</a></p>
        <p>📱 WhatsApp: <a href="https://wa.me/5511999999999" class="whatsapp-button" id="whatsappBtn2">(11) 99999-9999</a></p>
        <p>⏰ Atendimento: de segunda a sábado, das 9h às 18h, exceto feriados</p>

        <div class="back-button-container">
            <button class="back-button" id="backButton">Voltar à Loja</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Botão de voltar à loja
            document.getElementById('backButton').addEventListener('click', function() {
                window.location.href = '../index.php'; // Redireciona para a página inicial
            });

            // WhatsApp button handlers
            document.getElementById('whatsappBtn').addEventListener('click', function(e) {
                e.preventDefault();
                window.open('https://wa.me/5511999999999', '_blank');
            });

            document.getElementById('whatsappBtn2').addEventListener('click', function(e) {
                e.preventDefault();
                window.open('https://wa.me/5511999999999', '_blank');
            });
        });
    </script>
</body>
</html>
