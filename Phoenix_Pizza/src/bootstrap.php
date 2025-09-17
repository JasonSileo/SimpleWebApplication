<?php
$envFile = __DIR__ . "/../.env";
$env = file_exists($envFile) ? parse_ini_file($envFile) : [];

$dsn = sprintf(
  "mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4",
  $env["DB_HOST"] ?? "127.0.0.1",
  $env["DB_PORT"] ?? "3306",
  $env["DB_NAME"] ?? "phoenix_pizza_db"
);
try {
  $pdo = new PDO($dsn, $env["DB_USER"] ?? "root", $env["DB_PASS"] ?? "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ]);
} catch (Throwable $e) {
  http_response_code(500);
  echo "<pre>DB connection failed: " . htmlspecialchars($e->getMessage()) . "</pre>";
  exit;
}
