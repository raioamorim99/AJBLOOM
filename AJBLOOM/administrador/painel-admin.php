<?php
session_start();
include '../backend/conexao.php';

// Verificação básica de admin (você pode melhorar isso)
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // Para teste, vamos permitir acesso direto
    // header("Location: login-admin.php");
    // exit();
}

// Buscar todos os produtos
$sql = "SELECT * FROM produtos ORDER BY id DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - AJBLOOM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .admin-header h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .admin-nav {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .admin-nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .admin-nav a:hover,
        .admin-nav a.active {
            background: rgba(255,255,255,0.2);
        }

        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: white;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .produtos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .produto-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .produto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .produto-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f8f9fa;
        }

        .produto-info {
            padding: 1.5rem;
        }

        .produto-info h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .produto-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #666;
        }

        .produto-preco {
            font-size: 1.1rem;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 1rem;
        }

        .produto-preco .antigo {
            text-decoration: line-through;
            color: #999;
            margin-right: 0.5rem;
        }

        .produto-badges {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-lancamento {
            background: #d4edda;
            color: #155724;
        }

        .badge-vendido {
            background: #fff3cd;
            color: #856404;
        }

        .produto-actions {
            display: flex;
            gap: 0.5rem;
        }

        .search-bar {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .search-bar input {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .stat-card .label {
            color: #666;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 0.5rem;
            }
            
            .actions-bar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .search-bar {
                width: 100%;
            }
            
            .search-bar input {
                width: 100%;
            }
            
            .produtos-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <h1><i class="fas fa-crown"></i> Painel Administrativo - AJBLOOM</h1>
        <p>Gerencie seus produtos, pedidos e configurações</p>
        <nav class="admin-nav">
            <a href="#" class="active"><i class="fas fa-box"></i> Produtos</a>
            <a href="#"><i class="fas fa-shopping-cart"></i> Pedidos</a>
            <a href="#"><i class="fas fa-users"></i> Clientes</a>
            <a href="#"><i class="fas fa-chart-bar"></i> Relatórios</a>
            <a href="#"><i class="fas fa-cog"></i> Configurações</a>
        </nav>
    </header>

    <div class="container">
        <!-- Estatísticas -->
        <div class="stats-cards">
            <?php
            $total_produtos = $conn->query("SELECT COUNT(*) as total FROM produtos")->fetch_assoc()['total'];
            $produtos_lancamento = $conn->query("SELECT COUNT(*) as total FROM produtos WHERE lancamento = 1")->fetch_assoc()['total'];
            $produtos_vendidos = $conn->query("SELECT COUNT(*) as total FROM produtos WHERE mais_vendido = 1")->fetch_assoc()['total'];
            ?>
            <div class="stat-card">
                <div class="number"><?= $total_produtos ?></div>
                <div class="label">Total de Produtos</div>
            </div>
            <div class="stat-card">
                <div class="number"><?= $produtos_lancamento ?></div>
                <div class="label">Lançamentos</div>
            </div>
            <div class="stat-card">
                <div class="number"><?= $produtos_vendidos ?></div>
                <div class="label">Mais Vendidos</div>
            </div>
            <div class="stat-card">
                <div class="number">R$ 12.450</div>
                <div class="label">Vendas do Mês</div>
            </div>
        </div>

        <!-- Barra de Ações -->
        <div class="actions-bar">
            <div class="search-bar">
                <input type="text" id="search-produtos" placeholder="Buscar produtos...">
                <select id="filter-categoria">
                    <option value="">Todas as categorias</option>
                    <option value="camisetas">Camisetas</option>
                    <option value="vestidos">Vestidos</option>
                    <option value="acessorios">Acessórios</option>
                </select>
            </div>
            <div>
                <a href="adicionar-produto.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Produto
                </a>
                <a href="importar-produtos.php" class="btn btn-success">
                    <i class="fas fa-upload"></i> Importar
                </a>
            </div>
        </div>

        <!-- Grid de Produtos -->
        <div class="produtos-grid" id="produtos-grid">
            <?php while($produto = $resultado->fetch_assoc()): ?>
            <div class="produto-card" data-categoria="<?= $produto['categoria'] ?>">
                <img src="<?= $produto['imagem'] ?: '/placeholder.svg?height=200&width=300&text=Sem+Imagem' ?>" 
                     alt="<?= htmlspecialchars($produto['nome']) ?>" 
                     class="produto-img">
                
                <div class="produto-info">
                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                    
                    <div class="produto-meta">
                        <span><i class="fas fa-tag"></i> <?= ucfirst($produto['categoria']) ?></span>
                        <span><i class="fas fa-calendar"></i> ID: <?= $produto['id'] ?></span>
                    </div>
                    
                    <div class="produto-preco">
                        <?php if($produto['preco_antigo']): ?>
                            <span class="antigo">R$ <?= number_format($produto['preco_antigo'], 2, ',', '.') ?></span>
                        <?php endif; ?>
                        R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                    </div>
                    
                    <div class="produto-badges">
                        <?php if($produto['lancamento']): ?>
                            <span class="badge badge-lancamento">Lançamento</span>
                        <?php endif; ?>
                        <?php if($produto['mais_vendido']): ?>
                            <span class="badge badge-vendido">Mais Vendido</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="produto-actions">
                        <a href="editar-produto.php?id=<?= $produto['id'] ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <button onclick="duplicarProduto(<?= $produto['id'] ?>)" class="btn btn-success btn-sm">
                            <i class="fas fa-copy"></i> Duplicar
                        </button>
                        <button onclick="excluirProduto(<?= $produto['id'] ?>)" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Excluir
                        </button>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        // Busca em tempo real
        document.getElementById('search-produtos').addEventListener('input', function(e) {
            const termo = e.target.value.toLowerCase();
            const produtos = document.querySelectorAll('.produto-card');
            
            produtos.forEach(produto => {
                const nome = produto.querySelector('h3').textContent.toLowerCase();
                if (nome.includes(termo)) {
                    produto.style.display = 'block';
                } else {
                    produto.style.display = 'none';
                }
            });
        });

        // Filtro por categoria
        document.getElementById('filter-categoria').addEventListener('change', function(e) {
            const categoria = e.target.value;
            const produtos = document.querySelectorAll('.produto-card');
            
            produtos.forEach(produto => {
                const produtoCategoria = produto.dataset.categoria;
                if (!categoria || produtoCategoria === categoria) {
                    produto.style.display = 'block';
                } else {
                    produto.style.display = 'none';
                }
            });
        });

        // Funções de ação
        function duplicarProduto(id) {
            if (confirm('Deseja duplicar este produto?')) {
                window.location.href = `duplicar-produto.php?id=${id}`;
            }
        }

        function excluirProduto(id) {
            if (confirm('Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita.')) {
                window.location.href = `excluir-produto.php?id=${id}`;
            }
        }

        // Atalhos de teclado
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                window.location.href = 'adicionar-produto.php';
            }
        });
    </script>
</body>
</html>
