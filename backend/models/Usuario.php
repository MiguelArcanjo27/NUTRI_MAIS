<?php
require_once __DIR__ . '/../config/database.php';

class Usuario
{
    public static function criar(string $nome, string $email, string $senha): int
    {
        $pdo = getConnection();
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'INSERT INTO usuarios (nome, email, senha_hash) VALUES (:nome, :email, :senha)'
        );
        $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $hash]);

        return (int) $pdo->lastInsertId();
    }

    public static function buscarPorEmail(string $email): ?array
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }

    public static function buscarPorId(int $id): ?array
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare('SELECT id, nome, email FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }

    public static function autenticar(string $email, string $senha): ?array
    {
        $usuario = self::buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            return $usuario;
        }

        return null;
    }
}
