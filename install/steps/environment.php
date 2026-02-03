<?php
$requirements = [
    'php' => [
        'label' => 'PHP 8.1+',
        'ok' => version_compare(PHP_VERSION, '8.1.0', '>='),
    ],
    'extensions' => [
        'pdo' => extension_loaded('pdo'),
        'pdo_mysql' => extension_loaded('pdo_mysql'),
        'openssl' => extension_loaded('openssl'),
        'mbstring' => extension_loaded('mbstring'),
        'json' => extension_loaded('json'),
    ],
    'permissions' => [
        'storage' => is_writable(__DIR__ . '/../../storage'),
        'logs' => is_writable(__DIR__ . '/../../logs'),
    ],
];
$ready = $requirements['php']['ok']
    && !in_array(false, $requirements['extensions'], true)
    && !in_array(false, $requirements['permissions'], true);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Instalação - Ambiente</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Instalação</h1>
        <p>Verificação do ambiente</p>
    </div>
</header>
<main class="container">
    <div class="card">
        <h2>Checklist</h2>
        <ul>
            <li>PHP 8.1+: <?= $requirements['php']['ok'] ? 'OK' : 'Falhou' ?></li>
            <li>PDO: <?= $requirements['extensions']['pdo'] ? 'OK' : 'Falhou' ?></li>
            <li>PDO MySQL: <?= $requirements['extensions']['pdo_mysql'] ? 'OK' : 'Falhou' ?></li>
            <li>OpenSSL: <?= $requirements['extensions']['openssl'] ? 'OK' : 'Falhou' ?></li>
            <li>Mbstring: <?= $requirements['extensions']['mbstring'] ? 'OK' : 'Falhou' ?></li>
            <li>JSON: <?= $requirements['extensions']['json'] ? 'OK' : 'Falhou' ?></li>
            <li>Permissão /storage: <?= $requirements['permissions']['storage'] ? 'OK' : 'Falhou' ?></li>
            <li>Permissão /logs: <?= $requirements['permissions']['logs'] ? 'OK' : 'Falhou' ?></li>
        </ul>
        <?php if ($ready): ?>
            <a class="button" href="/install/index.php?step=database">Continuar</a>
        <?php else: ?>
            <p>Corrija os itens acima para continuar.</p>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
