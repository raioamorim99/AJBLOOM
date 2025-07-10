<?php
session_start();
include("../backend/conexao.php");

$busca = isset($_GET["busca"]) ? '%' . $_GET["busca"] . '%' : '%';
$categoria = isset($_GET["categoria"]) ? $_GET["categoria"] : '';

$sql = "SELECT id, nome, preco, preco_antigo, categoria, imagem FROM produtos WHERE nome LIKE ?";
$params = [$busca];
$types = "s";

if (!empty($categoria)) {
    $sql .= " AND categoria = ?";
    $params[] = $categoria;
    $types .= "s";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Vitrine - AJBLOOM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/index.css">
  <style>
    body {
      background-color: #f0f4f6;
      font-family: sans-serif;
      margin: 0;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      padding: 2rem;
    }

    .filtros {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .filtros input, .filtros select {
      padding: 0.6rem 1rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }

    .produtos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 2rem;
    }

    .produto-card {
      background: #fff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      transition: transform 0.2s;
    }

    .produto-card:hover {
      transform: scale(1.02);
    }

    .produto-img {
      width: 100%;
      height: 260px;
      object-fit: cover;
    }

    .produto-info {
      padding: 1rem;
    }

    .produto-info h3 {
      font-size: 1.2rem;
      margin-bottom: 0.5rem;
    }

    .preco {
      font-size: 1.1rem;
      font-weight: bold;
    }

    .preco .antigo {
      text-decoration: line-through;
      color: #999;
      margin-right: 0.5rem;
    }

    .selo {
      position: absolute;
      background: #e74c3c;
      color: white;
      font-size: 0.75rem;
      padding: 0.3rem 0.6rem;
      border-radius: 0 0 8px 0;
      top: 0;
      left: 0;
    }

    .produto-card a {
      color: inherit;
      text-decoration: none;
    }

    @media (max-width: 600px) {
      .produto-img {
        height: 200px;
      }
    }
  </style>
</head>
<body>


<header style="background: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
  <a href="index.php" style="font-size: 1.5rem; font-weight: bold; text-decoration: none; color: black;">AJBLOOM</a>
</header>


<main class="container">
  <form method="GET" class="filtros">
    <input type="text" name="busca" placeholder="Buscar produto..." value="<?php echo htmlspecialchars($_GET['busca'] ?? '') ?>">
    <select name="categoria">
      <option value="">Todas as categorias</option>
      <option value="camisetas" <?php if($categoria=="camisetas") echo "selected"; ?>>Camisetas</option>
      <option value="vestidos" <?php if($categoria=="vestidos") echo "selected"; ?>>Vestidos</option>
      <option value="acessorios" <?php if($categoria=="acessorios") echo "selected"; ?>>Acessórios</option>
    </select>
    <button type="submit">Filtrar</button>
  </form>

  <div class="produtos-grid">
    <?php while($produto = $resultado->fetch_assoc()): ?>
      <div class="produto-card">
        <a href="produto.php?id=<?php echo $produto['id']; ?>">
          <?php if (!empty($produto['preco_antigo'])): ?>
            <span class="selo">Promoção</span>
          <?php endif; ?>
          <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" class="produto-img">
          <div class="produto-info">
            <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
            <p class="preco">
              <?php if (!empty($produto['preco_antigo'])): ?>
                <span class="antigo">R$ <?php echo number_format($produto['preco_antigo'], 2, ',', '.'); ?></span>
              <?php endif; ?>
              R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
            </p>
          </div>
        </a>
      </div>
    <?php endwhile; ?>
  </div>
</main>

</body>
</html>
