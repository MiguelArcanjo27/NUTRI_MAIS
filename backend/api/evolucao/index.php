<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../models/Evolucao.php';

$usuarioId = exigirLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true) ?? [];
    $data = $dados['data_registro'] ?? date('Y-m-d');
    $peso = isset($dados['peso']) && $dados['peso'] !== '' ? (float) $dados['peso'] : null;
    $percentual = isset($dados['percentual_gordura']) && $dados['percentual_gordura'] !== ''
        ? (float) $dados['percentual_gordura']
        : null;
    $observacao = trim($dados['observacao'] ?? '') ?: null;

    if ($peso === null) {
        responder(['erro' => 'Informe ao menos o peso para registrar a evolução'], 422);
    }

    $id = Evolucao::criar($usuarioId, $data, $peso, $percentual, $observacao);
    responder(['id' => $id], 201);
}

responder(['registros' => Evolucao::listarPorUsuario($usuarioId)]);
