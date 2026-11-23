<?php
require_once __DIR__ . '/Database.php';

// Login / logout / verificação de sessão
class Auth
{
    public static function iniciarSessao(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(string $email, string $senha): bool
    {
        self::iniciarSessao();
        $stmt = Database::conectar()->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([trim($email)]);
        $u = $stmt->fetch();

        if ($u && password_verify($senha, $u['senha'])) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = ['id' => $u['id'], 'nome' => $u['nome']];
            return true;
        }
        return false;
    }

    public static function logout(): void
    {
        self::iniciarSessao();
        $_SESSION = [];
        session_destroy();
    }

    public static function logado(): bool
    {
        self::iniciarSessao();
        return isset($_SESSION['usuario']);
    }

    public static function usuario(): ?array
    {
        self::iniciarSessao();
        return $_SESSION['usuario'] ?? null;
    }

    // Protege as páginas HTML
    public static function exigirPagina(): void
    {
        if (!self::logado()) {
            header('Location: index.php');
            exit;
        }
    }

    // Protege os endpoints JSON
    public static function exigirApi(): void
    {
        if (!self::logado()) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'erro' => 'Não autenticado']);
            exit;
        }
    }
}
