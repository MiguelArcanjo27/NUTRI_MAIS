<?php
require_once __DIR__ . '/../../config/cors.php';

session_destroy();
responder(['ok' => true]);
