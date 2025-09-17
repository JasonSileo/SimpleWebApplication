<?php
// src/dao/ProductDAO.php
namespace PhoenixPizza\DAO;

use PhoenixPizza\DB\Database;
use PDO;

class ProductDAO {
    public function __construct(private ?PDO $pdo = null) {
        $this->pdo = $pdo ?: Database::getConnection();
    }

    public function listActive(): array {
        $stmt = $this->pdo->query("SELECT id, name, description, price FROM products WHERE is_active=1 ORDER BY name");
        return $stmt->fetchAll();
    }

    public function getAllowedToppings(int $productId): array {
        $sql = "SELECT t.id, t.name, t.extra_price
                FROM product_topping pt
                JOIN toppings t ON t.id = pt.topping_id
                WHERE pt.product_id = ? AND t.is_active = 1
                ORDER BY t.name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }
}
