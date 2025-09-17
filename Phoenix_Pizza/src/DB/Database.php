<?php
// src/db.php
// PDO connector with exceptions + prepared statements by default.
// Reads .env from project root (same directory as index.php/.env).

namespace PhoenixPizza\DB;

use PDO;
use PDOException;

class Database {
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        // Locate .env (one level up from /src when installed in project root)
        $root = dirname(__DIR__, 1);
        $envPath = $root . DIRECTORY_SEPARATOR . '.env';
        if (!file_exists($envPath)) {
            // try project root if placed differently
            $alt = dirname($root) . DIRECTORY_SEPARATOR . '.env';
            if (file_exists($alt)) {
                $envPath = $alt;
            }
        }

        $env = [];
        if (file_exists($envPath)) {
            foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if (str_starts_with(trim($line), '#')) continue;
                [$k, $v] = array_map('trim', explode('=', $line, 2));
                $env[$k] = $v;
            }
        }

        $host = $env['DB_HOST'] ?? '127.0.0.1';
        $port = $env['DB_PORT'] ?? '3306';
        $db   = $env['DB_NAME'] ?? 'phoenix_pizza_db';
        $user = $env['DB_USER'] ?? 'root';
        $pass = $env['DB_PASS'] ?? '';

        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

        try {
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            die('DB connection failed: ' . htmlspecialchars($e->getMessage()));
        }

        return self::$pdo;
    }
}
