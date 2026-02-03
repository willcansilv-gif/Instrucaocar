<?php
$error = null;
$success = false;
$db = $_SESSION['install_db'] ?? null;
if (!$db) {
    header('Location: /install/index.php?step=database');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if ($nome && $email && strlen($senha) >= 8) {
        try {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $db['host'], $db['name']);
            $pdo = new PDO($dsn, $db['user'], $db['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha_hash, perfil, status) VALUES (:nome, :email, :senha, :perfil, :status)');
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'senha' => password_hash($senha, PASSWORD_BCRYPT),
                'perfil' => 'admin',
                'status' => 'ativo',
            ]);
            $_SESSION['install_admin'] = compact('nome', 'email');
            $success = true;
        } catch (Throwable $exception) {
            $error = 'Falha ao criar admin: ' . $exception->getMessage();
        }
    } else {
        $error = 'Preencha todos os campos e use senha com 8+ caracteres.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Instalação - Admin</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Instalação</h1>
        <p>Criação do administrador</p>
    </div>
</header>
<main class="container">
    <div class="card">
        <?php if ($error): ?>
            <div class="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <p>Administrador criado com sucesso.</p>
            <a class="button" href="/install/index.php?step=finalize">Continuar</a>
        <?php else: ?>
            <form method="post">
                <label>Nome</label>
                <input name="nome" required value="<?= htmlspecialchars($_POST['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <label>E-mail</label>
                <input name="email" type="email" required value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <label>Senha (mínimo 8 caracteres)</label>
                <input name="senha" type="password" required>
                <button class="button" type="submit">Criar administrador</button>
            </form>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
