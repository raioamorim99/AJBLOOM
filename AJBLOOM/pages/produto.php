<?php
session_start();
include("../backend/conexao.php");

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("ID inválido.");
}

$id = intval($_GET["id"]);

$stmt = $conn->prepare("SELECT nome, preco, preco_antigo, categoria, imagem, descricao FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nome, $preco, $preco_antigo, $categoria, $imagem, $descricao);
$stmt->fetch();
$stmt->close();
$conn->close();

if (!$nome) {
    $nome = "Produto não encontrado";
    $descricao = "Este produto não está mais disponível.";
    $preco = $preco_antigo = $imagem = $categoria = null;
}

$base = '/';?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($nome); ?> - AJBLOOM</title>
  <meta name="description" content="Veja detalhes de <?php echo htmlspecialchars($nome); ?> da AJBLOOM.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/index.css">
  <style>
    body {
      background-color: #f0f4f6;
      font-family: sans-serif;
      margin: 0;
      padding-top: 100px
    }

    .produto-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 2rem;
    }

    .produto-card {
      background: white;
      border-radius: 16px;
      max-width: 600px;
      width: 100%;
      padding: 2rem;
      box-shadow: 0 0 12px rgba(0,0,0,0.05);
    }

    .produto-img {
      width: 100%;
      border-radius: 12px;
      margin-bottom: 1rem;
    }

    .produto-info h2 {
      font-size: 1.8rem;
      margin-bottom: 0.5rem;
    }

    .produto-info .categoria {
      font-weight: bold;
      color: #666;
      text-transform: capitalize;
      margin-bottom: 1rem;
    }

    .produto-info .preco {
      font-size: 1.4rem;
      margin-bottom: 1rem;
    }

    .produto-info .preco .antigo {
      color: #999;
      text-decoration: line-through;
      margin-right: 0.5rem;
    }

    .descricao {
      margin-bottom: 1.5rem;
      line-height: 1.5;
    }

    .btn-whats {
      display: inline-block;
      background-color: #25d366;
      color: white;
      padding: 12px 24px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }

    .btn-whats:hover {
      background-color: #1da851;
    }
    
header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 80px;
  padding: 0 40px;
  background-color: white; /* COR NORMAL */
  display: flex;
  justify-content: space-between;
  align-items: center;
  z-index: 1000;
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

header.scrolled {
  background-color: transparent;
  backdrop-filter: blur(6px); /* opcional, para suavizar */
}

.logo-container img {
  height: 240px;
}

.btn-transparente {
  background: transparent;
  padding: 8px 16px;
  color: transparent; /* invisível inicialmente */
  border: 1px solid transparent;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

.btn-transparente:hover {
  color: black;              /* visível no hover */
  border-color: black;
}

header.scrolled .btn-transparente {
  color: black;              /* visível ao rolar */
  border-color: black;
}

.voltar-btn {
  margin-right: 20px;
  background: transparent;
  color: transparent;
  border: 1px solid transparent;
  padding: 8px 16px;
  border-radius: 4px;
  font-weight: bold;
  text-decoration: none;
  transition: all 0.3s ease;
}

.voltar-btn:hover {
  color: black;
  border-color: black;
}

header.scrolled .voltar-btn {
  color: black;
  border-color: black;
}

</style>

</head>
<body>

  <header>
  <div class="logo-container">
    <img src="../assets/imagens/logo/logo.png" alt="AJBLOOM Logo">
  </div>

  <div>
    <a href="index.php" class="voltar-btn">← Voltar à loja</a>
    <a href="vitrine.php" class="btn-transparente">Ver mais produtos</a>
  </div>
</header>



<main class="produto-container">
  <div class="produto-card">
    <?php if ($imagem): ?>
      <img src="<?php echo $imagem; ?>" alt="Imagem do produto <?php echo htmlspecialchars($nome); ?>" class="produto-img">
      <div class="produto-info">
        <h2><?php echo htmlspecialchars($nome); ?></h2>
        <p class="categoria">Categoria: <?php echo htmlspecialchars($categoria); ?></p>

        <p class="preco">
          <?php if ($preco_antigo): ?>
            <span class="antigo">R$ <?php echo number_format($preco_antigo, 2, ',', '.'); ?></span>
          <?php endif; ?>
          <span class="atual">R$ <?php echo number_format($preco, 2, ',', '.'); ?></span>
        </p>

        <div class="descricao"><?php echo $descricao; ?></div>

        <a href="https://wa.me/5541999999999?text=<?php echo urlencode('Olá! Gostei do produto ' . $nome . '. Ainda está disponível?'); ?>" class="btn-whats" target="_blank">
          Fazer pedido no WhatsApp
        </a>
      </div>
    <?php else: ?>
      <p><?php echo $descricao; ?></p>
    <?php endif; ?>
  </div>
</main>


<script>
  window.addEventListener('scroll', function () {
    const header = document.querySelector('header');
    if (window.scrollY > 50) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  });
</script>

</body>
</html>
