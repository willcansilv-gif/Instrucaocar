<?php
declare(strict_types=1);

session_start();

$installedLock = __DIR__ . '/../storage/installed.lock';
if (file_exists($installedLock)) {
    http_response_code(403);
    echo 'Instalação já concluída.';
    exit;
}

$step = $_GET['step'] ?? 'environment';
$allowed = ['environment', 'database', 'admin', 'finalize'];
if (!in_array($step, $allowed, true)) {
    $step = 'environment';
}

require __DIR__ . '/steps/' . $step . '.php';
