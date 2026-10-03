<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../models/Treino.php';

$usuarioId = exigirLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true) ?? [];
    $nomeTreino = trim($dados['nome_treino'] ?? '');
    $tipo = $dados['tipo'] ?? '';
    $duracao = isset($dados['duracao_min']) && $dados['duracao_min'] !== '' ? (int) $dados['duracao_min'] : null;
    $data = $dados['data_treino'] ?? date('Y-m-d');
    $observacoes = trim($dados['observacoes'] ?? '') ?: null;
    $profissionalId = isset($dados['profissional_id']) && $dados['profissional_id'] !== ''
        ? (int) $dados['profissional_id']
        : null;

    if (!$nomeTreino || !$tipo) {
        responder(['erro' => 'Preencha ao menos o nome e o tipo do treino'], 422);
    }

    $id = Treino::criar($usuarioId, $profissionalId, $nomeTreino, $tipo, $duracao, $data, $observacoes);
    responder(['id' => $id], 201);
}

responder(['treinos' => Treino::listarPorUsuario($usuarioId)]);
