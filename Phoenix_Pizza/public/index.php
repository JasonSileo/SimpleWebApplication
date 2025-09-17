<?php
// public/index.php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';

$route = $_GET['route'] ?? 'home';
$view = match ($route) {
    'home' => __DIR__ . '/../views/home.php',
    'menu' => __DIR__ . '/../views/menu.php',
    'custom' => __DIR__ . '/../views/custom.php', 
    'cart' => __DIR__ . '/../views/cart.php',
    'checkout' => __DIR__ . '/../views/checkout.php',
    default => __DIR__ . '/../views/home.php',
};

// Basic layout wrapper with Bootstrap
include __DIR__ . '/../views/partials/header.php';
include $view;
include __DIR__ . '/../views/partials/footer.php';
