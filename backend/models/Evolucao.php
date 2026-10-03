<?php
require_once __DIR__ . '/../config/database.php';

class Evolucao
{
    public static function criar(
        int $usuarioId,
        string $data,
        ?float $peso,
        ?float $percentualGordura,
        ?string $observacao
    ): int {
        $pdo = getConnection();

        $stmt = $pdo->prepare(
            'INSERT INTO evolucao (usuario_id, data_registro, peso, percentual_gordura, observacao)
             VALUES (:usuario_id, :data, :peso, :percentual, :observacao)'
        );
        $stmt->execute([
            'usuario_id' => $usuarioId,
            'data' => $data,
            'peso' => $peso,
            'percentual' => $percentualGordura,
            'observacao' => $observacao,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function listarPorUsuario(int $usuarioId): array
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            'SELECT * FROM evolucao WHERE usuario_id = :usuario_id
             ORDER BY data_registro ASC'
        );
        $stmt->execute(['usuario_id' => $usuarioId]);

        return $stmt->fetchAll();
    }
}
