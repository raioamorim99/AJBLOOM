<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

include '../conexao.php';

// Função para corrigir caminho da imagem
function corrigirCaminhoImagem($imagem) {
    if (empty($imagem)) {
        return '/placeholder.svg?height=400&width=400&text=Sem+Imagem';
    }
    
    // Se é URL externa, retorna como está
    if (filter_var($imagem, FILTER_VALIDATE_URL)) {
        return $imagem;
    }
    
    // Remove barras duplas e normaliza o caminho
    $imagem = ltrim($imagem, '/');
    
    // Se já começa com assets/, retorna como está
    if (strpos($imagem, 'assets/') === 0) {
        return $imagem;
    }
    
    // Se não tem assets/, assume que está na pasta de produtos
    if (!strpos($imagem, 'assets/')) {
        return 'assets/imagens/produtos/' . basename($imagem);
    }
    
    // Caso padrão
    return $imagem;
}

// Parâmetros da requisição
$busca = isset($_GET['busca']) ? '%' . $_GET['busca'] . '%' : '%';
$categoria = isset($_GET['categoria']) && $_GET['categoria'] !== 'tudo' && $_GET['categoria'] !== '' ? $_GET['categoria'] : '';
$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 6;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$preco_min = isset($_GET['preco_min']) ? floatval($_GET['preco_min']) : 0;
$preco_max = isset($_GET['preco_max']) ? floatval($_GET['preco_max']) : 1000;
$lancamento = isset($_GET['lancamento']) ? 1 : 0;
$mais_vendido = isset($_GET['mais_vendido']) ? 1 : 0;

try {
    // ✅ ATUALIZADO: Incluir novos campos na query
    $sql = "SELECT id, nome, preco, preco_antigo, categoria, imagem, imagem2, imagem3, imagem4, imagem5, imagem6, descricao, tamanhos, cores, lancamento, mais_vendido 
            FROM produtos 
            WHERE nome LIKE ? 
            AND preco BETWEEN ? AND ?";
    
    $params = [$busca, $preco_min, $preco_max];
    $types = "sdd";

    // Filtros adicionais
    if (!empty($categoria)) {
        $sql .= " AND categoria = ?";
        $params[] = $categoria;
        $types .= "s";
    }

    if ($lancamento) {
        $sql .= " AND lancamento = 1";
    }

    if ($mais_vendido) {
        $sql .= " AND mais_vendido = 1";
    }

    // Ordenação e paginação
    $sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";
    $params[] = $limite;
    $params[] = $offset;
    $types .= "ii";

    // Query para contar total
    $count_sql = str_replace("SELECT id, nome, preco, preco_antigo, categoria, imagem, imagem2, imagem3, imagem4, imagem5, imagem6, descricao, tamanhos, cores, lancamento, mais_vendido", "SELECT COUNT(*)", $sql);
    $count_sql = str_replace(" LIMIT ? OFFSET ?", "", $count_sql);
    $count_params = array_slice($params, 0, -2);
    $count_types = substr($types, 0, -2);

    // Executar query principal
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $produtos = [];
    while ($produto = $resultado->fetch_assoc()) {
        // Corrigir caminho da imagem principal
        $produto['imagem'] = corrigirCaminhoImagem($produto['imagem']);
        
        // ✅ NOVO: Processar múltiplas imagens
        $imagens = [];
        $campos_imagem = ['imagem', 'imagem2', 'imagem3', 'imagem4', 'imagem5', 'imagem6'];
        foreach ($campos_imagem as $campo) {
            if (!empty($produto[$campo])) {
                $imagens[] = corrigirCaminhoImagem($produto[$campo]);
            }
        }
        $produto['imagens'] = $imagens;
        
        // ✅ NOVO: Processar tamanhos e cores
        $produto['tamanhos_array'] = !empty($produto['tamanhos']) ? array_map('trim', explode(',', $produto['tamanhos'])) : [];
        $produto['cores_array'] = !empty($produto['cores']) ? array_map('trim', explode(',', $produto['cores'])) : [];
        
        $produtos[] = $produto;
    }

    // Executar query de contagem
    $count_stmt = $conn->prepare($count_sql);
    if (!empty($count_params)) {
        $count_stmt->bind_param($count_types, ...$count_params);
    }
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total = $count_result->fetch_row()[0];

    // Resposta JSON
    echo json_encode([
        'success' => true,
        'produtos' => $produtos,
        'total' => intval($total),
        'limite' => $limite,
        'offset' => $offset,
        'tem_mais' => ($offset + $limite) < $total
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao buscar produtos: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
