<?php
header('Content-Type: application/json');
$mysqli = new mysqli('localhost', 'GUi', 'gui08090', 'lojaderoupas');
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao conectar ao banco de dados']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Listar produtos
    $result = $mysqli->query("SELECT * FROM produtos");
    $produtos = [];
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
    echo json_encode($produtos);
    exit;
}

if ($method === 'POST') {
    // Adicionar produto
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $mysqli->prepare("INSERT INTO produtos (nome, preco, imagem, descricao) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $data['nome'], $data['preco'], $data['imagem'], $data['desc']);
    $stmt->execute();
    echo json_encode(['id' => $stmt->insert_id]);
    exit;
}

if ($method === 'PUT') {
    // Atualizar produto
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $mysqli->prepare("UPDATE produtos SET nome=?, preco=?, imagem=?, descricao=? WHERE id=?");
    $stmt->bind_param("sdssi", $data['nome'], $data['preco'], $data['imagem'], $data['desc'], $data['id']);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

if ($method === 'DELETE') {
    // Excluir produto
    parse_str(file_get_contents("php://input"), $data);
    $id = intval($data['id']);
    $stmt = $mysqli->prepare("DELETE FROM produtos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Método não permitido']);
?>