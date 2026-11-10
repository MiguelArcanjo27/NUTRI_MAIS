<?php
require_once __DIR__ . '/../../config/auth.php';

exigirRole(['admin']);

// Usa as colunas que a sua tabela profissionais já tem.
// Os apelidos (tipo, registro) mantendo compatível com o PainelAdmin.vue
$sql = 'SELECT id,
               nome,
               email,
               especialidade AS tipo,
               registro_profissional AS registro,
               aprovado,
               (usuario_id IS NOT NULL) AS tem_login
        FROM profissionais
        ORDER BY aprovado ASC, nome ASC';

responder(['profissionais' => $pdo->query($sql)->fetchAll()]);