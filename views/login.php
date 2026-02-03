<?php
use App\Helpers\Config;
$appName = Config::get('app.name', 'Infraestrutura Veicular');
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></h1>
    </div>
</header>
<main class="container">
    <div class="card">
        <h2>Entrar</h2>
        <?php if ($error): ?>
            <div class="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <form method="post" action="/login">
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" required>
            <label for="password">Senha</label>
            <input id="password" name="password" type="password" required>
            <button class="button" type="submit">Entrar</button>
        </form>
    </div>
</main>
</body>
</html>
