<?php
require_once __DIR__ . '/../config/database.php';

class Dieta
{
    public static function criar(
        int $usuarioId,
        string $nomeRefeicao,
        string $alimentos,
        ?int $calorias,
        string $data
    ): int {
        $pdo = getConnection();

        $stmt = $pdo->prepare(
            'INSERT INTO dietas (usuario_id, nome_refeicao, alimentos, calorias, data_refeicao)
             VALUES (:usuario_id, :nome_refeicao, :alimentos, :calorias, :data)'
        );
        $stmt->execute([
            'usuario_id' => $usuarioId,
            'nome_refeicao' => $nomeRefeicao,
            'alimentos' => $alimentos,
            'calorias' => $calorias,
            'data' => $data,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function listarPorUsuario(int $usuarioId): array
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            'SELECT * FROM dietas WHERE usuario_id = :usuario_id
             ORDER BY data_refeicao DESC, criado_em DESC'
        );
        $stmt->execute(['usuario_id' => $usuarioId]);

        return $stmt->fetchAll();
    }
}
