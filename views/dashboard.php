<?php
use App\Helpers\Config;
$appName = Config::get('app.name', 'Infraestrutura Veicular');
$user = $_SESSION['user'] ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Dashboard</h1>
        <p>Bem-vindo, <?= htmlspecialchars($user['nome'] ?? 'Usuário', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</header>
<main class="container">
    <div class="card">
        <h2>Visão Geral</h2>
        <p>Perfil: <?= htmlspecialchars($user['perfil'] ?? 'desconhecido', ENT_QUOTES, 'UTF-8') ?></p>
        <p>Este painel será expandido com módulos de histórico, score, auditoria e antifraude.</p>
    </div>
</main>
</body>
</html>
