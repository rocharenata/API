<?php
header('Content-Type: application/json');
require 'config.php';

// Rota principal da API
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        // Obter todos os clientes ou um cliente específico
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($cliente);
        } else {
            $stmt = $pdo->query("SELECT * FROM clientes");
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($clientes);
        }
        break;

    case 'POST':
        // Adicionar um novo cliente
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, endereco, telefone, cep, estado, cidade, metodo_pagamento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$data['nome'], $data['email'], $data['endereco'], $data['telefone'], $data['cep'], $data['estado'], $data['cidade'], $data['metodo_pagamento']]);
        echo json_encode(["message" => "Cliente adicionado com sucesso!"]);
        break;

    case 'PUT':
        // Atualizar um cliente existente
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("UPDATE clientes SET nome = ?, email = ?, endereco = ?, telefone = ?, cep = ?, estado = ?, cidade = ?, metodo_pagamento = ? WHERE id = ?");
        $stmt->execute([$data['nome'], $data['email'], $data['endereco'], $data['telefone'], $data['cep'], $data['estado'], $data['cidade'], $data['metodo_pagamento'], $data['id']]);
        echo json_encode(["message" => "Cliente atualizado com sucesso!"]);
        break;

    case 'DELETE':
        // Deletar um cliente
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
        $stmt->execute([$data['id']]);
        echo json_encode(["message" => "Cliente deletado com sucesso!"]);
        break;

    default:
        echo json_encode(["error" => "Método não suportado."]);
        break;
}
?>
