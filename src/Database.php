<?php
// Conexão com o banco (padrão Singleton, usando PDO)
class Database
{
    private static ?PDO $pdo = null;

    public static function conectar(): PDO
    {
        if (self::$pdo === null) {
            $dsn  = getenv('DB_DSN') ?: sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                getenv('DB_HOST') ?: 'db',
                getenv('DB_NAME') ?: 'petcontrol'
            );
            $user = getenv('DB_USER') ?: 'petuser';
            $pass = getenv('DB_PASS') ?: 'petpass';

            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            if (self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
                self::$pdo->exec('PRAGMA foreign_keys = ON');
            }
        }
        return self::$pdo;
    }
}
