<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../models/Usuario.php';

$dados = json_decode(file_get_contents('php://input'), true) ?? [];
$nome = trim($dados['nome'] ?? '');
$email = trim($dados['email'] ?? '');
$senha = $dados['senha'] ?? '';

if (!$nome || !$email || strlen($senha) < 6) {
    responder(['erro' => 'Preencha nome, e-mail e uma senha com ao menos 6 caracteres'], 422);
}

try {
    $id = Usuario::criar($nome, $email, $senha);
} catch (PDOException $e) {
    responder(['erro' => 'Não foi possível cadastrar. E-mail já em uso?'], 409);
}

$_SESSION['usuario_id'] = $id;
$_SESSION['usuario_nome'] = $nome;

responder(['usuario' => ['id' => $id, 'nome' => $nome, 'email' => $email]], 201);
