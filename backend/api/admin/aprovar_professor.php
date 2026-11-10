<?php
require_once __DIR__ . '/../../config/auth.php';

exigirRole(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(['erro' => 'Método não permitido'], 405);
}

$dados = corpoJson();
$id = (int) ($dados['id'] ?? 0);
$aprovado = !empty($dados['aprovado']) ? 1 : 0;

$stmt = $pdo->prepare('UPDATE profissionais SET aprovado = ? WHERE id = ?');
$stmt->execute([$aprovado, $id]);

responder(['ok' => true]);