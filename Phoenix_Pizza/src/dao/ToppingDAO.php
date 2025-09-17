<?php
// src/dao/ToppingDAO.php
namespace PhoenixPizza\DAO;

use PhoenixPizza\DB\Database;
use PDO;

class ToppingDAO {
    public function __construct(private ?PDO $pdo = null) {
        $this->pdo = $pdo ?: Database::getConnection();
    }

    public function listActive(): array {
        $stmt = $this->pdo->query("SELECT id, name, extra_price FROM toppings WHERE is_active=1 ORDER BY name");
        return $stmt->fetchAll();
    }
}
