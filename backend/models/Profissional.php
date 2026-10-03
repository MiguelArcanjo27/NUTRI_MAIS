<?php
require_once __DIR__ . '/../config/database.php';

class Profissional
{
    public static function criar(
        string $nome,
        string $email,
        string $senha,
        string $especialidade,
        ?string $registro
    ): int {
        $pdo = getConnection();
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'INSERT INTO profissionais (nome, email, senha_hash, especialidade, registro_profissional)
             VALUES (:nome, :email, :senha, :especialidade, :registro)'
        );
        $stmt->execute([
            'nome' => $nome,
            'email' => $email,
            'senha' => $hash,
            'especialidade' => $especialidade,
            'registro' => $registro,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function listarTodos(): array
    {
        $pdo = getConnection();
        $stmt = $pdo->query(
            'SELECT id, nome, especialidade, registro_profissional, criado_em
             FROM profissionais ORDER BY nome ASC'
        );

        return $stmt->fetchAll();
    }

    public static function buscarPorEmail(string $email): ?array
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare('SELECT * FROM profissionais WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $profissional = $stmt->fetch();

        return $profissional ?: null;
    }
}
