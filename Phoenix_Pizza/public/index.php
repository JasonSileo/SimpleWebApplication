<?php
session_start();

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'PhoenixPizza\\';
    $base_dir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$route = $_GET['route'] ?? 'home';

switch ($route) {
    case 'menu':
        $controller = new \PhoenixPizza\controllers\MenuController();
        $controller->index();
        break;

    case 'cart':
        $controller = new \PhoenixPizza\controllers\CartController();
        $controller->index();
        break;

    case 'checkout':
        $controller = new \PhoenixPizza\controllers\CheckoutController();
        $controller->index();
        break;

    case 'cart_add':
        $controller = new \PhoenixPizza\controllers\CartController();
        $controller->add();
        break;

    case 'cart_remove':
        $controller = new \PhoenixPizza\controllers\CartController();
        $controller->remove();
        break;

    case 'cart_update':
        $controller = new \PhoenixPizza\controllers\CartController();
        $controller->update();
        break;

    case 'home':
    default:
        include __DIR__ . '/../views/home.php';
        break;
}
