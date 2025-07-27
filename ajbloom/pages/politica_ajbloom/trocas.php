<?php
// Configurações da loja
$nome_loja = "ajblom";
$email_contato = "trocas@ajblom.com.br";
$whatsapp = "(11) 99999-9999";
$endereco_troca = "Rua das Flores, 123 - São Paulo, SP - CEP: 01234-567";
$data_atualizacao = "22 de julho de 2025";
$prazo_arrependimento = 7;
$prazo_defeito = 30;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trocas e Devoluções - <?php echo ucfirst($nome_loja); ?></title>
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
            max-width: 900px;
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
            border-bottom: 3px solid #28a745;
        }

        .logo {
            font-size: 2.5em;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        h1 {
            color: #2c3e50;
            font-size: 2.2em;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .last-update {
            color: #666;
            font-style: italic;
            font-size: 0.9em;
        }

        h2 {
            color: #28a745;
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
            background-color: #d4edda;
            padding: 15px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
            border-radius: 5px;
        }

        .warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
            border-radius: 5px;
        }

        .danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-left: 4px solid #dc3545;
            margin: 20px 0;
            border-radius: 5px;
        }

        .info-box {
            background-color: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            border-left: 4px solid #007bff;
        }

        .info-box h3 {
            color: #007bff;
            margin-top: 0;
        }

        .steps {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .step-number {
            background-color: #28a745;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .step-content h4 {
            color: #28a745;
            margin-bottom: 5px;
        }

        .contact-form {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin: 30px 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #495057;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .btn {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #218838;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }

        .table tr:hover {
            background-color: #f8f9fa;
        }

        .faq {
            margin-top: 30px;
        }

        .faq-item {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .faq-question {
            background-color: #f8f9fa;
            padding: 15px;
            font-weight: bold;
            color: #495057;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
        }

        .faq-answer {
            padding: 15px;
            background-color: white;
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

            .step {
                flex-direction: column;
                text-align: center;
            }

            .step-number {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .table {
                font-size: 14px;
            }
        }

        .section {
            margin-bottom: 25px;
        }

        strong {
            color: #2c3e50;
        }

        .whatsapp-btn {
            background-color: #25d366;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 25px;
            display: inline-block;
            margin: 10px 0;
            font-weight: bold;
        }

        .whatsapp-btn:hover {
            background-color: #128c7e;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo"><?php echo $nome_loja; ?></div>
            <h1>Política de Trocas e Devoluções</h1>
            <p class="subtitle">Sua satisfação é nossa prioridade</p>
            <p class="last-update">Última atualização: <?php echo $data_atualizacao; ?></p>
        </div>
        
         <a href="../index.php" class="whatsapp-btn" target="_blank">
              voltar a loja 
            </a>

        <div class="highlight">
            <strong>Garantia de Satisfação:</strong> Trabalhamos para que você fique 100% satisfeito com sua compra. Se não estiver, estamos aqui para ajudar!
        </div>

        <div class="section">
            <h2>1. Direito de Arrependimento</h2>
            <p>Conforme o Código de Defesa do Consumidor (Art. 49), você tem <strong><?php echo $prazo_arrependimento; ?> dias corridos</strong> para desistir da compra, contados a partir do recebimento do produto.</p>
            
            <div class="info-box">
                <h3>📅 Prazo de <?php echo $prazo_arrependimento; ?> dias</h3>
                <p>Este prazo é válido para compras realizadas pela internet, sem necessidade de justificativa.</p>
            </div>
        </div>

        <div class="section">
            <h2>2. Condições para Troca e Devolução</h2>
            <p>Para que possamos processar sua troca ou devolução, o produto deve atender às seguintes condições:</p>
            
            <ul>
                <li>✅ Estar em perfeito estado de conservação</li>
                <li>✅ Não ter sido usado ou danificado</li>
                <li>✅ Estar na embalagem original</li>
                <li>✅ Acompanhar todos os acessórios e manuais</li>
                <li>✅ Ter a nota fiscal ou comprovante de compra</li>
                <li>✅ Estar dentro do prazo estabelecido</li>
            </ul>
        </div>

        <div class="section">
            <h2>3. Prazos de Garantia</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipo de Problema</th>
                        <th>Prazo</th>
                        <th>Observações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Arrependimento</td>
                        <td><?php echo $prazo_arrependimento; ?> dias</td>
                        <td>A partir do recebimento</td>
                    </tr>
                    <tr>
                        <td>Defeito de fabricação</td>
                        <td><?php echo $prazo_defeito; ?> dias</td>
                        <td>Produtos não duráveis</td>
                    </tr>
                    <tr>
                        <td>Defeito de fabricação</td>
                        <td>90 dias</td>
                        <td>Produtos duráveis</td>
                    </tr>
                    <tr>
                        <td>Vício oculto</td>
                        <td>Até 1 ano</td>
                        <td>Conforme garantia do fabricante</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>4. Como Solicitar Troca ou Devolução</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Entre em Contato</h4>
                        <p>Entre em contato conosco através do e-mail, WhatsApp ou formulário abaixo informando o motivo da troca/devolução.</p>
                    </div>
                </div>
                
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Aguarde Autorização</h4>
                        <p>Nossa equipe analisará sua solicitação e enviará as instruções para envio do produto em até 24 horas.</p>
                    </div>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Envie o Produto</h4>
                        <p>Embale o produto com cuidado e envie para o endereço que forneceremos. Guarde o código de rastreamento.</p>
                    </div>
                </div>
                
                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h4>Análise e Processamento</h4>
                        <p>Após recebermos o produto, faremos a análise em até 3 dias úteis e processaremos a troca ou reembolso.</p>
                    </div>
                </div>
                
                <div class="step">
                    <div class="step-number">5</div>
                    <div class="step-content">
                        <h4>Finalização</h4>
                        <p>Para trocas: enviaremos o novo produto. Para devoluções: o reembolso será processado em até 5 dias úteis.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>5. Produtos que NÃO podem ser trocados</h2>
            <div class="danger">
                <strong>Atenção:</strong> Os seguintes produtos não podem ser trocados por questões de higiene e segurança:
            </div>
            
            <ul>
                <li>❌ Produtos de higiene pessoal (após abertura da embalagem)</li>
                <li>❌ Produtos íntimos</li>
                <li>❌ Produtos personalizados ou sob encomenda</li>
                <li>❌ Produtos perecíveis</li>
                <li>❌ Produtos com lacre de segurança violado</li>
                <li>❌ Produtos danificados por mau uso</li>
            </ul>
        </div>

        <div class="section">
            <h2>6. Custos de Frete</h2>
            <h3>6.1 Arrependimento</h3>
            <p>Em caso de arrependimento, o frete de devolução fica por conta do cliente.</p>
            
            <h3>6.2 Defeito ou Erro Nosso</h3>
            <p>Se o produto apresentar defeito ou se houve erro no envio, arcamos com todos os custos de frete.</p>
            
            <div class="warning">
                <strong>Dica:</strong> Sempre solicite um código de rastreamento ao enviar o produto de volta.
            </div>
        </div>

        <div class="section">
            <h2>7. Reembolsos</h2>
            <p>Os reembolsos são processados da seguinte forma:</p>
            
            <ul>
                <li><strong>Cartão de Crédito:</strong> Estorno em até 2 faturas</li>
                <li><strong>Cartão de Débito:</strong> Até 5 dias úteis</li>
                <li><strong>PIX:</strong> Até 2 dias úteis</li>
                <li><strong>Boleto:</strong> Depósito em conta em até 5 dias úteis</li>
            </ul>
        </div>

        <div class="section">
            <h2>8. Formulário de Solicitação</h2>
            <div class="contact-form">
                <h3>Solicite sua Troca ou Devolução</h3>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label for="nome">Nome Completo *</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">E-mail *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefone">Telefone/WhatsApp *</label>
                        <input type="tel" id="telefone" name="telefone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="pedido">Número do Pedido *</label>
                        <input type="text" id="pedido" name="pedido" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo">Tipo de Solicitação *</label>
                        <select id="tipo" name="tipo" required>
                            <option value="">Selecione...</option>
                            <option value="troca">Troca</option>
                            <option value="devolucao">Devolução</option>
                            <option value="defeito">Produto com Defeito</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="motivo">Motivo da Solicitação *</label>
                        <textarea id="motivo" name="motivo" placeholder="Descreva o motivo da troca/devolução..." required></textarea>
                    </div>
                    
                    <button type="submit" class="btn">Enviar Solicitação</button>
                </form>
            </div>
        </div>

        <div class="section">
            <h2>9. Perguntas Frequentes</h2>
            <div class="faq">
                <div class="faq-item">
                    <div class="faq-question">❓ Posso trocar um produto por outro de valor diferente?</div>
                    <div class="faq-answer">
                        <p>Sim! Se o novo produto for mais caro, você paga a diferença. Se for mais barato, devolvemos a diferença.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">❓ Como acompanho o status da minha solicitação?</div>
                    <div class="faq-answer">
                        <p>Após enviar a solicitação, você receberá um número de protocolo por e-mail para acompanhar o andamento.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">❓ Posso cancelar um pedido antes do envio?</div>
                    <div class="faq-answer">
                        <p>Sim! Entre em contato conosco o mais rápido possível. Se o pedido ainda não foi enviado, o cancelamento é gratuito.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">❓ E se o produto chegou danificado?</div>
                    <div class="faq-answer">
                        <p>Entre em contato imediatamente! Enviaremos um novo produto sem custo adicional e coletaremos o danificado.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-box">
            <h3>📞 Canais de Atendimento</h3>
            <p><strong>E-mail:</strong> <?php echo $email_contato; ?></p>
            <p><strong>WhatsApp:</strong> <?php echo $whatsapp; ?></p>
            <p><strong>Horário:</strong> Segunda a Sexta, das 9h às 18h</p>
            <p><strong>Endereço para Devolução:</strong><br><?php echo $endereco_troca; ?></p>
            
            <a href="https://wa.me/5511999999999" class="whatsapp-btn" target="_blank">
                💬 Falar no WhatsApp
            </a>
        </div>

        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> <?php echo ucfirst($nome_loja); ?>. Todos os direitos reservados.</p>
            <p>Política atualizada em <?php echo $data_atualizacao; ?> - Válida em todo território nacional</p>
        </div>
    </div>

    <script>
        // Funcionalidade para o FAQ
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', function() {
                const answer = this.nextElementSibling;
                const isVisible = answer.style.display === 'block';
                
                // Fecha todas as respostas
                document.querySelectorAll('.faq-answer').forEach(ans => {
                    ans.style.display = 'none';
                });
                
                // Abre a resposta clicada se não estava visível
                if (!isVisible) {
                    answer.style.display = 'block';
                }
            });
        });

        // Validação do formulário
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Solicitação enviada com sucesso! Entraremos em contato em até 24 horas.');
        });
    </script>
</body>
</html>