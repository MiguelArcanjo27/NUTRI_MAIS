<?php
/**
 * Headers compartilhados por todos os endpoints da API.
 * Libera acesso do front-end Vue (rodando em outra porta, ex: 5173)
 * e garante sessão + respostas em JSON.
 */

// Ajuste a origem conforme o endereço do seu front-end em desenvolvimento
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// Requisição de "pre-flight" do navegador — apenas confirma e encerra
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

session_start();

function responder(array $dados, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($dados);
    exit;
}

function exigirLogin(): int
{
    if (empty($_SESSION['usuario_id'])) {
        responder(['erro' => 'Não autenticado'], 401);
    }

    return (int) $_SESSION['usuario_id'];
}
