<?php
// src/PriceCalculator.php
namespace PhoenixPizza;

use PhoenixPizza\DB\Database;
use PDO;

class PriceCalculator {
    // If you don't have sizes table yet, define server-side deltas here.
    // Keys must match 'size' values you pass from the UI (e.g., 'small','medium','large')
    private array $sizeDeltas = [
        'small'  => 0.00,   // base price applies to small
        'medium' => 2.00,   // +$2.00
        'large'  => 4.00,   // +$4.00
        'xl'     => 6.00    // +$6.00
    ];

    public function __construct(private ?PDO $pdo = null) {
        $this->pdo = $pdo ?: Database::getConnection();
    }

    public function calcItemTotal(int $productId, string $size, array $toppingIds, int $qty): array {
        if ($qty < 1) $qty = 1;
        $base = $this->getProductBase($productId);
        $delta = $this->sizeDeltas[strtolower($size)] ?? 0.00;
        $toppingTotal = $this->sumToppings($toppingIds);

        $unit = round($base + $delta + $toppingTotal, 2);
        $line = round($unit * $qty, 2);
        return [
            'unit_price' => $unit,
            'line_total' => $line,
            'base' => $base,
            'size_delta' => $delta,
            'toppings_total' => $toppingTotal
        ];
    }

    private function getProductBase(int $productId): float {
        $stmt = $this->pdo->prepare("SELECT price FROM products WHERE id = ? AND is_active = 1");
        $stmt->execute([$productId]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new \RuntimeException("Product not found or inactive");
        }
        return (float)$row['price'];
    }

    private function sumToppings(array $toppingIds): float {
        if (empty($toppingIds)) return 0.0;
        // only allow toppings that exist and are active
        $in  = str_repeat('?,', count($toppingIds) - 1) . '?';
        $sql = "SELECT COALESCE(SUM(extra_price),0) as s FROM toppings WHERE is_active=1 AND id IN ($in)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($toppingIds);
        $row = $stmt->fetch();
        return (float)($row['s'] ?? 0);
    }
}
