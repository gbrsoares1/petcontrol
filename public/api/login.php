<?php
require_once __DIR__ . '/../../src/Auth.php';

header('Content-Type: application/json; charset=utf-8');

try {
    if (Auth::login($_POST['email'] ?? '', $_POST['senha'] ?? '')) {
        echo json_encode(['ok' => true]);
    } else {
        http_response_code(401);
        echo json_encode(['ok' => false, 'erro' => 'E-mail ou senha inválidos.']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'erro' => 'Erro ao conectar ao banco de dados.']);
}
