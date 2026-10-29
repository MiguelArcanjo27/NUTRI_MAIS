<?php
require_once __DIR__ . '/../config/database.php';

class Treino
{
    public static function criar(
        int $usuarioId,
        ?int $profissionalId,
        string $nomeTreino,
        string $tipo,
        ?int $duracaoMin,
        string $data,
        ?string $observacoes
    ): int {
        $pdo = getConnection();

        $stmt = $pdo->prepare(
            'INSERT INTO treinos (usuario_id, profissional_id, nome_treino, tipo, duracao_min, data_treino, observacoes)
             VALUES (:usuario_id, :profissional_id, :nome_treino, :tipo, :duracao, :data, :observacoes)'
        );
        $stmt->execute([
            'usuario_id' => $usuarioId,
            'profissional_id' => $profissionalId,
            'nome_treino' => $nomeTreino,
            'tipo' => $tipo,
            'duracao' => $duracaoMin,
            'data' => $data,
            'observacoes' => $observacoes,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function excluir(int $id, int $usuarioId): bool
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare('DELETE FROM treinos WHERE id = :id AND usuario_id = :usuario_id');
        $stmt->execute(['id' => $id, 'usuario_id' => $usuarioId]);

        return $stmt->rowCount() > 0;
    }

    public static function listarPorUsuario(int $usuarioId): array
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            'SELECT t.*, p.nome AS profissional_nome
             FROM treinos t
             LEFT JOIN profissionais p ON p.id = t.profissional_id
             WHERE t.usuario_id = :usuario_id
             ORDER BY t.data_treino DESC, t.criado_em DESC'
        );
        $stmt->execute(['usuario_id' => $usuarioId]);

        return $stmt->fetchAll();
    }
}
