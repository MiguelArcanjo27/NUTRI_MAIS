<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../models/Usuario.php';

if (empty($_SESSION['usuario_id'])) {
    responder(['usuario' => null]);
}

$usuario = Usuario::buscarPorId((int) $_SESSION['usuario_id']);
responder(['usuario' => $usuario]);
