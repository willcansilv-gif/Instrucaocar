<?php
$error = null;
$success = false;
$db = $_SESSION['install_db'] ?? null;
if (!$db) {
    header('Location: /install/index.php?step=database');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $env = $_POST['env'] ?? 'production';
    $timezone = trim($_POST['timezone'] ?? 'America/Sao_Paulo');
    $baseUrl = trim($_POST['base_url'] ?? '');

    if ($name && $baseUrl) {
        try {
            $appConfig = "<?php\ndeclare(strict_types=1);\n\nreturn [\n    'app' => [\n        'name' => '" . addslashes($name) . "',\n        'env' => '" . addslashes($env) . "',\n        'timezone' => '" . addslashes($timezone) . "',\n        'base_url' => '" . addslashes($baseUrl) . "',\n    ],\n];\n";
            file_put_contents(__DIR__ . '/../../config/app.php', $appConfig);

            $dbConfig = "<?php\ndeclare(strict_types=1);\n\nreturn [\n    'database' => [\n        'host' => '" . addslashes($db['host']) . "',\n        'name' => '" . addslashes($db['name']) . "',\n        'user' => '" . addslashes($db['user']) . "',\n        'pass' => '" . addslashes($db['pass']) . "',\n    ],\n];\n";
            file_put_contents(__DIR__ . '/../../config/database.php', $dbConfig);

            file_put_contents(__DIR__ . '/../../storage/installed.lock', date('c'));
            $success = true;
        } catch (Throwable $exception) {
            $error = 'Falha ao finalizar: ' . $exception->getMessage();
        }
    } else {
        $error = 'Informe o nome do sistema e a URL base.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Instalação - Finalização</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Instalação</h1>
        <p>Configuração inicial</p>
    </div>
</header>
<main class="container">
    <div class="card">
        <?php if ($error): ?>
            <div class="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <p>Instalação concluída com sucesso.</p>
            <a class="button" href="/">Ir para o sistema</a>
        <?php else: ?>
            <form method="post">
                <label>Nome do sistema</label>
                <input name="name" required value="<?= htmlspecialchars($_POST['name'] ?? 'Infraestrutura Veicular', ENT_QUOTES, 'UTF-8') ?>">
                <label>Ambiente</label>
                <input name="env" required value="<?= htmlspecialchars($_POST['env'] ?? 'production', ENT_QUOTES, 'UTF-8') ?>">
                <label>Timezone</label>
                <input name="timezone" required value="<?= htmlspecialchars($_POST['timezone'] ?? 'America/Sao_Paulo', ENT_QUOTES, 'UTF-8') ?>">
                <label>URL base</label>
                <input name="base_url" required value="<?= htmlspecialchars($_POST['base_url'] ?? 'http://localhost', ENT_QUOTES, 'UTF-8') ?>">
                <button class="button" type="submit">Finalizar</button>
            </form>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
