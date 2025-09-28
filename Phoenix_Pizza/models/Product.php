<?php
class Product {
    public static function all() {
        $stmt = DB::conn()->query("SELECT * FROM products WHERE is_active = 1");
        return $stmt->fetchAll();
    }

    public static function find($id) {
        $stmt = DB::conn()->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(); // returns assoc array or false
    }
}
