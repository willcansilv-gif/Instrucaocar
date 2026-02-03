<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;

class AuthController
{
    public function showLogin(): void
    {
        require __DIR__ . '/../views/login.php';
    }

    public function login(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        if (!$email || $password === '') {
            $_SESSION['error'] = 'Credenciais inválidas.';
            header('Location: /login');
            return;
        }
        $auth = new AuthService();
        if ($auth->attempt($email, $password)) {
            header('Location: /dashboard');
            return;
        }
        $_SESSION['error'] = 'Usuário ou senha incorretos.';
        header('Location: /login');
    }
}
