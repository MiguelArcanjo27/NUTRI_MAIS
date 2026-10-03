<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../models/Profissional.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true) ?? [];
    $nome = trim($dados['nome'] ?? '');
    $email = trim($dados['email'] ?? '');
    $senha = $dados['senha'] ?? '';
    $especialidade = $dados['especialidade'] ?? '';
    $registro = trim($dados['registro'] ?? '') ?: null;

    if (!$nome || !$email || strlen($senha) < 6 || !$especialidade) {
        responder(['erro' => 'Preencha nome, e-mail, especialidade e uma senha com ao menos 6 caracteres'], 422);
    }

    try {
        $id = Profissional::criar($nome, $email, $senha, $especialidade, $registro);
    } catch (PDOException $e) {
        responder(['erro' => 'Não foi possível cadastrar. E-mail já em uso?'], 409);
    }

    responder(['id' => $id], 201);
}

responder(['profissionais' => Profissional::listarTodos()]);
