<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../models/Usuario.php';

$dados = json_decode(file_get_contents('php://input'), true) ?? [];
$email = trim($dados['email'] ?? '');
$senha = $dados['senha'] ?? '';

$usuario = Usuario::autenticar($email, $senha);

if (!$usuario) {
    responder(['erro' => 'E-mail ou senha inválidos'], 401);
}

$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];

responder([
    'usuario' => [
        'id' => $usuario['id'],
        'nome' => $usuario['nome'],
        'email' => $usuario['email'],
    ],
]);
