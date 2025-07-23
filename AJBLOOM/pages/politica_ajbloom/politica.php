<?php
// Configurações da loja
$nome_loja = "ajblom";
$razao_social = "ajblom Comércio Eletrônico Ltda.";
$cnpj = "12.345.678/0001-90";
$email_contato = "contato@ajblom.com.br";
$email_privacidade = "privacidade@ajblom.com.br";
$dpo_email = "dpo@ajblom.com.br";
$endereco = "Rua das Flores, 123 - São Paulo, SP - CEP: 01234-567";
$data_atualizacao = "22 de julho de 2025";
$site_url = "www.ajblom.com.br";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade - <?php echo ucfirst($nome_loja); ?></title>
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
            border-bottom: 3px solid #6f42c1;
        }

        .logo {
            font-size: 2.5em;
            font-weight: bold;
            color: #6f42c1;
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
            color: #6f42c1;
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
            background-color: #e8d5ff;
            padding: 15px;
            border-left: 4px solid #6f42c1;
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

        .lgpd-box {
            background-color: #f0f8ff;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            border: 2px solid #6f42c1;
        }

        .lgpd-box h3 {
            color: #6f42c1;
            margin-top: 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .data-table th {
            background-color: #6f42c1;
            color: white;
            font-weight: bold;
        }

        .data-table tr:hover {
            background-color: #f8f9fa;
        }

        .rights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .right-card {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #6f42c1;
        }

        .right-card h4 {
            color: #6f42c1;
            margin-bottom: 10px;
        }

        .contact-section {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin: 30px 0;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .contact-card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .contact-card h4 {
            color: #6f42c1;
            margin-bottom: 10px;
        }

        .btn {
            background-color: #6f42c1;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
            margin: 5px;
        }

        .btn:hover {
            background-color: #5a2d91;
            color: white;
            text-decoration: none;
        }

        .cookie-banner {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }

        .cookie-banner h3 {
            color: #fff;
            margin-top: 0;
        }

        .timeline {
            position: relative;
            margin: 20px 0;
        }

        .timeline-item {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #6f42c1;
        }

        .timeline-item h4 {
            color: #6f42c1;
            margin-bottom: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            color: #666;
        }

        .voltar-btn {
            background-color: #25d366;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 25px;
            display: inline-block;
            margin: 10px 0;
            font-weight: bold;
        }

        .voltar-btn:hover {
            background-color: #128c7e;
            color: white;
            text-decoration: none;
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

            .rights-grid {
                grid-template-columns: 1fr;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .data-table {
                font-size: 14px;
            }
        }

        .section {
            margin-bottom: 25px;
        }

        strong {
            color: #2c3e50;
        }

        .icon {
            font-size: 1.2em;
            margin-right: 8px;
        }

        .badge {
            background-color: #6f42c1;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
        }

       
        
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo"><?php echo $nome_loja; ?></div>
            <h1>Política de Privacidade</h1>
            <p class="subtitle">Transparência e proteção dos seus dados pessoais</p>
            <p class="last-update">Última atualização: <?php echo $data_atualizacao; ?></p>
        </div>
<a href="../index.php" class="voltar-btn" target="_blank">
              voltar a loja 
            </a>
        <div class="lgpd-box">
            <h3>🛡️ Conformidade com a LGPD</h3>
            <p>Esta política está em total conformidade com a <strong>Lei Geral de Proteção de Dados (Lei nº 13.709/2018)</strong> e garante a transparência no tratamento dos seus dados pessoais.</p>
        </div>

        <div class="section">
            <h2>1. Informações Gerais</h2>
            <p>A <strong><?php echo $razao_social; ?></strong>, inscrita no CNPJ sob o nº <strong><?php echo $cnpj; ?></strong>, com sede em <?php echo $endereco; ?>, é a controladora dos dados pessoais coletados através do site <strong><?php echo $site_url; ?></strong>.</p>
            
            <div class="info-box">
                <h3>📋 Definições Importantes</h3>
                <ul>
                    <li><strong>Dados Pessoais:</strong> Informações que identificam ou podem identificar uma pessoa</li>
                    <li><strong>Controlador:</strong> Quem toma as decisões sobre o tratamento dos dados</li>
                    <li><strong>Operador:</strong> Quem realiza o tratamento em nome do controlador</li>
                    <li><strong>Titular:</strong> Pessoa a quem se referem os dados pessoais</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2>2. Dados Coletados</h2>
            <p>Coletamos diferentes tipos de dados para oferecer nossos serviços:</p>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tipo de Dado</th>
                        <th>Exemplos</th>
                        <th>Finalidade</th>
                        <th>Base Legal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dados de Identificação</td>
                        <td>Nome, CPF, RG</td>
                        <td>Identificação do cliente</td>
                        <td>Execução de contrato</td>
                    </tr>
                    <tr>
                        <td>Dados de Contato</td>
                        <td>E-mail, telefone, endereço</td>
                        <td>Comunicação e entrega</td>
                        <td>Execução de contrato</td>
                    </tr>
                    <tr>
                        <td>Dados de Pagamento</td>
                        <td>Dados do cartão, PIX</td>
                        <td>Processamento de pagamentos</td>
                        <td>Execução de contrato</td>
                    </tr>
                    <tr>
                        <td>Dados de Navegação</td>
                        <td>IP, cookies, histórico</td>
                        <td>Melhorar experiência</td>
                        <td>Legítimo interesse</td>
                    </tr>
                    <tr>
                        <td>Dados de Marketing</td>
                        <td>Preferências, interesses</td>
                        <td>Ofertas personalizadas</td>
                        <td>Consentimento</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>3. Como Coletamos os Dados</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <h4>🛒 Durante o Cadastro</h4>
                    <p>Quando você cria uma conta em nosso site, coletamos dados básicos como nome, e-mail e telefone.</p>
                </div>
                
                <div class="timeline-item">
                    <h4>💳 Durante a Compra</h4>
                    <p>No processo de compra, coletamos dados de entrega, pagamento e informações fiscais necessárias.</p>
                </div>
                
                <div class="timeline-item">
                    <h4>🌐 Durante a Navegação</h4>
                    <p>Automaticamente coletamos dados de navegação através de cookies e tecnologias similares.</p>
                </div>
                
                <div class="timeline-item">
                    <h4>📞 Durante o Atendimento</h4>
                    <p>Quando você entra em contato conosco, registramos as interações para melhor atendimento.</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>4. Finalidades do Tratamento</h2>
            <p>Utilizamos seus dados pessoais para as seguintes finalidades:</p>
            
            <ul>
                <li>✅ <strong>Execução de contratos:</strong> Processar pedidos, entregas e pagamentos</li>
                <li>✅ <strong>Atendimento ao cliente:</strong> Suporte, trocas e devoluções</li>
                <li>✅ <strong>Comunicação:</strong> Confirmações, atualizações e notificações</li>
                <li>✅ <strong>Marketing:</strong> Ofertas e promoções (com seu consentimento)</li>
                <li>✅ <strong>Segurança:</strong> Prevenção de fraudes e proteção da conta</li>
                <li>✅ <strong>Melhorias:</strong> Análise e otimização dos nossos serviços</li>
                <li>✅ <strong>Cumprimento legal:</strong> Obrigações fiscais e regulatórias</li>
            </ul>
        </div>

        <div class="section">
            <h2>5. Compartilhamento de Dados</h2>
            <p>Seus dados podem ser compartilhados nas seguintes situações:</p>
            
            <h3>5.1 Parceiros Essenciais</h3>
            <ul>
                <li><strong>Processadores de Pagamento:</strong> Para processar transações financeiras</li>
                <li><strong>Transportadoras:</strong> Para entrega dos produtos</li>
                <li><strong>Provedores de Tecnologia:</strong> Para funcionamento do site e sistemas</li>
            </ul>
            
            <h3>5.2 Autoridades Competentes</h3>
            <p>Quando exigido por lei ou ordem judicial.</p>
            
            <div class="warning">
                <strong>Importante:</strong> Nunca vendemos seus dados pessoais para terceiros!
            </div>
        </div>

        <div class="section">
            <h2>6. Cookies e Tecnologias de Rastreamento</h2>
            <div class="cookie-banner">
                <h3>🍪 Uso de Cookies</h3>
                <p>Utilizamos cookies para melhorar sua experiência de navegação:</p>
                <ul>
                    <li><strong>Cookies Essenciais:</strong> Necessários para o funcionamento do site</li>
                    <li><strong>Cookies de Performance:</strong> Para análise e melhorias</li>
                    <li><strong>Cookies de Marketing:</strong> Para personalização (com seu consentimento)</li>
                </ul>
                <p>Você pode gerenciar suas preferências de cookies a qualquer momento.</p>
            </div>
        </div>

        <div class="section">
            <h2>7. Seus Direitos (LGPD)</h2>
            <p>Como titular dos dados, você possui os seguintes direitos:</p>
            
            <div class="rights-grid">
                <div class="right-card">
                    <h4>📋 Confirmação e Acesso</h4>
                    <p>Confirmar se tratamos seus dados e ter acesso a eles.</p>
                </div>
                
                <div class="right-card">
                    <h4>✏️ Correção</h4>
                    <p>Corrigir dados incompletos, inexatos ou desatualizados.</p>
                </div>
                
                <div class="right-card">
                    <h4>🗑️ Eliminação</h4>
                    <p>Solicitar a exclusão de dados desnecessários ou excessivos.</p>
                </div>
                
                <div class="right-card">
                    <h4>📤 Portabilidade</h4>
                    <p>Receber seus dados em formato estruturado e interoperável.</p>
                </div>
                
                <div class="right-card">
                    <h4>❌ Oposição</h4>
                    <p>Opor-se ao tratamento realizado com base no legítimo interesse.</p>
                </div>
                
                <div class="right-card">
                    <h4>🔒 Revogação</h4>
                    <p>Revogar o consentimento a qualquer momento.</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>8. Segurança dos Dados</h2>
            <p>Implementamos medidas técnicas e organizacionais para proteger seus dados:</p>
            
            <ul>
                <li>🔐 <strong>Criptografia:</strong> Dados sensíveis são criptografados</li>
                <li>🛡️ <strong>Firewall:</strong> Proteção contra acessos não autorizados</li>
                <li>🔑 <strong>Controle de Acesso:</strong> Apenas pessoal autorizado acessa os dados</li>
                <li>📊 <strong>Monitoramento:</strong> Sistemas de detecção de atividades suspeitas</li>
                <li>🎓 <strong>Treinamento:</strong> Equipe treinada em proteção de dados</li>
                <li>📋 <strong>Auditorias:</strong> Revisões regulares de segurança</li>
            </ul>
        </div>

        <div class="section">
            <h2>9. Retenção de Dados</h2>
            <p>Mantemos seus dados pelo tempo necessário para:</p>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tipo de Dado</th>
                        <th>Período de Retenção</th>
                        <th>Justificativa</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dados de cadastro</td>
                        <td>Enquanto a conta estiver ativa</td>
                        <td>Prestação do serviço</td>
                    </tr>
                    <tr>
                        <td>Dados de compras</td>
                        <td>5 anos após a compra</td>
                        <td>Obrigações fiscais</td>
                    </tr>
                    <tr>
                        <td>Dados de marketing</td>
                        <td>Até revogação do consentimento</td>
                        <td>Consentimento do titular</td>
                    </tr>
                    <tr>
                        <td>Logs de acesso</td>
                        <td>6 meses</td>
                        <td>Segurança e investigação</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>10. Transferência Internacional</h2>
            <p>Alguns de nossos fornecedores podem estar localizados fora do Brasil. Quando isso ocorre:</p>
            
            <ul>
                <li>✅ Garantimos que o país possui nível adequado de proteção</li>
                <li>✅ Utilizamos cláusulas contratuais padrão</li>
                <li>✅ Implementamos salvaguardas adicionais</li>
                <li>✅ Obtemos seu consentimento quando necessário</li>
            </ul>
        </div>

        <div class="section">
            <h2>11. Menores de Idade</h2>
            <div class="warning">
                <strong>Atenção:</strong> Nossos serviços são destinados a maiores de 18 anos. Não coletamos intencionalmente dados de menores de idade sem o consentimento dos pais ou responsáveis.
            </div>
        </div>

        <div class="section">
            <h2>12. Alterações nesta Política</h2>
            <p>Esta política pode ser atualizada periodicamente. Quando isso acontecer:</p>
            
            <ul>
                <li>📧 Notificaremos por e-mail sobre mudanças significativas</li>
                <li>📅 A data de atualização será alterada no topo da página</li>
                <li>⏰ Você terá tempo para revisar as alterações</li>
                <li>❌ Poderá optar por não aceitar as mudanças</li>
            </ul>
        </div>

        <div class="contact-section">
            <h2>13. Contato e Exercício de Direitos</h2>
            <p>Para exercer seus direitos ou esclarecer dúvidas sobre esta política:</p>
            
            <div class="contact-grid">
                <div class="contact-card">
                    <h4>📧 E-mail Geral</h4>
                    <p><?php echo $email_contato; ?></p>
                    <a href="mailto:<?php echo $email_contato; ?>" class="btn">Enviar E-mail</a>
                </div>
                
                <div class="contact-card">
                    <h4>🔒 Privacidade</h4>
                    <p><?php echo $email_privacidade; ?></p>
                    <a href="mailto:<?php echo $email_privacidade; ?>" class="btn">Contatar Privacidade</a>
                </div>
                
                <div class="contact-card">
                    <h4>👤 Encarregado (DPO)</h4>
                    <p><?php echo $dpo_email; ?></p>
                    <a href="mailto:<?php echo $dpo_email; ?>" class="btn">Falar com DPO</a>
                </div>
            </div>
            
            <div class="highlight">
                <strong>Prazo de Resposta:</strong> Responderemos suas solicitações em até 15 dias, conforme previsto na LGPD.
            </div>
        </div>

        <div class="section">
            <h2>14. Autoridade de Controle</h2>
            <p>Você também pode entrar em contato com a Autoridade Nacional de Proteção de Dados (ANPD):</p>
            
            <div class="info-box">
                <h3>🏛️ ANPD - Autoridade Nacional de Proteção de Dados</h3>
                <p><strong>Site:</strong> www.gov.br/anpd</p>
                <p><strong>E-mail:</strong> comunicacao@anpd.gov.br</p>
                <p>A ANPD é o órgão responsável por fiscalizar o cumprimento da LGPD no Brasil.</p>
            </div>
        </div>

        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> <?php echo $razao_social; ?>. Todos os direitos reservados.</p>
            <p>Política atualizada em <?php echo $data_atualizacao; ?> - <span class="badge">LGPD Compliant</span></p>
            <p>CNPJ: <?php echo $cnpj; ?> | <?php echo $endereco; ?></p>
        </div>
    </div>

    <script>
        // Simulação de banner de cookies
        function showCookieBanner() {
            const banner = document.createElement('div');
            banner.style.cssText = `
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: #2c3e50;
                color: white;
                padding: 20px;
                text-align: center;
                z-index: 1000;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
            `;
            
            banner.innerHTML = `
                <p>🍪 Utilizamos cookies para melhorar sua experiência. Ao continuar navegando, você concorda com nossa política de cookies.</p>
                <button onclick="acceptCookies()" style="background: #6f42c1; color: white; border: none; padding: 10px 20px; border-radius: 5px; margin: 0 10px; cursor: pointer;">Aceitar</button>
                <button onclick="manageCookies()" style="background: transparent; color: white; border: 1px solid white; padding: 10px 20px; border-radius: 5px; margin: 0 10px; cursor: pointer;">Gerenciar</button>
            `;
            
            document.body.appendChild(banner);
        }

        function acceptCookies() {
            localStorage.setItem('cookiesAccepted', 'true');
            document.querySelector('div[style*="position: fixed"]').remove();
        }

        function manageCookies() {
            alert('Aqui você poderia gerenciar suas preferências de cookies.');
        }

        // Mostrar banner se não foi aceito ainda
        if (!localStorage.getItem('cookiesAccepted')) {
            setTimeout(showCookieBanner, 2000);
        }

        // Smooth scroll para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>