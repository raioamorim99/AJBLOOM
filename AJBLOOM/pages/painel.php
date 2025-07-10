<?php
// Inclui a conexão com o banco
include '../backend/conexao.php';

// ID fixo do cliente para teste
$cliente_id = 2;

// Busca dados do cliente
$cliente_query = "SELECT * FROM clientes WHERE id = $cliente_id";
$cliente_result = mysqli_query($conn, $cliente_query);
$cliente = mysqli_fetch_assoc($cliente_result);

// Busca endereço
$endereco_query = "SELECT * FROM enderecos WHERE cliente_id = $cliente_id";
$endereco_result = mysqli_query($conn, $endereco_query);
$endereco = mysqli_fetch_assoc($endereco_result);

// Busca favoritos
$favoritos_query = "SELECT * FROM favoritos WHERE cliente_id = $cliente_id";
$favoritos_result = mysqli_query($conn, $favoritos_query);

// Busca último pedido
$pedido_query = "SELECT * FROM pedidos WHERE cliente_id = $cliente_id ORDER BY id DESC LIMIT 1";
$pedido_result = mysqli_query($conn, $pedido_query);
$pedido = mysqli_fetch_assoc($pedido_result);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Cliente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f7f7f7;
        }
        h1 {
            color: #444;
        }
        .card {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px #ddd;
        }
    </style>
</head>
<body>

<h1>Olá, <?= $cliente['nome'] ?>!</h1>

<div class="card">
    <h3>Seus dados:</h3>
    <p><strong>Email:</strong> <?= $cliente['email'] ?></p>
    <p><strong>Telefone:</strong> <?= $cliente['telefone'] ?></p>
    <p><strong>CPF:</strong> <?= $cliente['cpf'] ?></p>
</div>

<div class="card">
    <h3>Seu endereço:</h3>
    <p><?= $endereco['rua'] ?> – <?= $endereco['cidade'] ?>/<?= $endereco['estado'] ?> – <?= $endereco['cep'] ?></p>
</div>

<div class="card">
    <h3>Seus produtos favoritos:</h3>
    <ul>
        <?php while ($fav = mysqli_fetch_assoc($favoritos_result)) : ?>
            <li><?= $fav['produto_nome'] ?> – R$<?= number_format($fav['preco'], 2, ',', '.') ?></li>
        <?php endwhile; ?>
    </ul>
</div>

<div class="card">
    <h3>Último pedido:</h3>
    <?php if ($pedido): ?>
        <p><strong>Número:</strong> <?= $pedido['numero_pedido'] ?></p>
        <p><strong>Data de entrega:</strong> <?= date('d/m/Y', strtotime($pedido['data_entrega'])) ?></p>
    <?php else: ?>
        <p>Você ainda não fez pedidos.</p>
    <?php endif; ?>
</div>

</body>
</html>
