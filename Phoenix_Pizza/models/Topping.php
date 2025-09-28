<?php
class Topping {
    public static function all() {
        $stmt = DB::conn()->query("SELECT * FROM toppings WHERE is_active = 1");
        return $stmt->fetchAll();
    }

    public static function findMany($ids) {
        if (empty($ids)) return [];

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT * FROM toppings WHERE id IN ($placeholders) AND is_active = 1";
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll();
    }
}
