<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../models/Dieta.php';

$usuarioId = exigirLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true) ?? [];
    $nomeRefeicao = trim($dados['nome_refeicao'] ?? '');
    $alimentos = trim($dados['alimentos'] ?? '');
    $calorias = isset($dados['calorias']) && $dados['calorias'] !== '' ? (int) $dados['calorias'] : null;
    $data = $dados['data_refeicao'] ?? date('Y-m-d');

    if (!$nomeRefeicao || !$alimentos) {
        responder(['erro' => 'Preencha ao menos o nome da refeição e os alimentos'], 422);
    }

    $id = Dieta::criar($usuarioId, $nomeRefeicao, $alimentos, $calorias, $data);
    responder(['id' => $id], 201);
}

// GET: lista as dietas do usuário logado
responder(['dietas' => Dieta::listarPorUsuario($usuarioId)]);
