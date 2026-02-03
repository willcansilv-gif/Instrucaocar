<?php
$error = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = trim($_POST['host'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $user = trim($_POST['user'] ?? '');
    $pass = $_POST['pass'] ?? '';

    if ($host && $name && $user) {
        try {
            $dsn = sprintf('mysql:host=%s;charset=utf8mb4', $host);
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$name}`");
            $schema = file_get_contents(__DIR__ . '/../sql/schema.sql');
            $pdo->exec($schema);
            $_SESSION['install_db'] = compact('host', 'name', 'user', 'pass');
            $success = true;
        } catch (Throwable $exception) {
            $error = 'Falha ao conectar ou criar banco: ' . $exception->getMessage();
        }
    } else {
        $error = 'Preencha todos os campos obrigatórios.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Instalação - Banco de Dados</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Instalação</h1>
        <p>Configuração do banco de dados</p>
    </div>
</header>
<main class="container">
    <div class="card">
        <?php if ($error): ?>
            <div class="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <p>Banco configurado com sucesso.</p>
            <a class="button" href="/install/index.php?step=admin">Continuar</a>
        <?php else: ?>
            <form method="post">
                <label>Host</label>
                <input name="host" required value="<?= htmlspecialchars($_POST['host'] ?? '127.0.0.1', ENT_QUOTES, 'UTF-8') ?>">
                <label>Nome do banco</label>
                <input name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <label>Usuário</label>
                <input name="user" required value="<?= htmlspecialchars($_POST['user'] ?? 'root', ENT_QUOTES, 'UTF-8') ?>">
                <label>Senha</label>
                <input type="password" name="pass" value="">
                <button class="button" type="submit">Testar e criar</button>
            </form>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
