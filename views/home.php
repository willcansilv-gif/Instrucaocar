<?php
use App\Helpers\Config;
$appName = Config::get('app.name', 'Infraestrutura Veicular');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></h1>
        <p>Infraestrutura de confiança veicular com histórico imutável e score automático.</p>
    </div>
</header>
<main class="container">
    <div class="card">
        <h2>Portal central</h2>
        <p>Faça login para acessar painéis de usuário, mecânica ou administração.</p>
        <a class="button" href="/login">Entrar</a>
    </div>
</main>
</body>
</html>
