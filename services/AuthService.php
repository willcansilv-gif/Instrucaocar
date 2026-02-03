<?php
declare(strict_types=1);

namespace App\Services;

class AuthService
{
    public function attempt(string $email, string $password): bool
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT id, nome, senha_hash, perfil FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user['senha_hash'])) {
            return false;
        }
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nome' => $user['nome'],
            'perfil' => $user['perfil'],
        ];
        return true;
    }
}
