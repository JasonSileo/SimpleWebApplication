<?php
// admin/seed.php
// Simple admin seed/import to refresh menu/toppings for testing.
require_once __DIR__ . '/../src/db.php';

use PhoenixPizza\DB\Database;

header('Content-Type: text/plain');

try {
    $pdo = Database::getConnection();
    $sqlPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'seed.sql';
    if (!file_exists($sqlPath)) {
        throw new RuntimeException("seed.sql not found at project root");
    }
    $sql = file_get_contents($sqlPath);

    // Basic guard: replace the problematic email with escaped version if present
    $sql = str_replace("demo@Phoenix's Pizza.test", "demo@phoenix_pizza.test", $sql);

    $pdo->exec($sql);
    echo "Seed completed.\n";
} catch (\Throwable $e) {
    http_response_code(500);
    echo "Seed failed: " . $e->getMessage() . "\n";
}
