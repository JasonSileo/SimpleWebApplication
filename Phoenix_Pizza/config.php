<?php
// config.php
// Global config: BASE_URL, secure sessions, and PSR-4-ish autoload for PhoenixPizza\*

declare(strict_types=1);

// ---- Base URL ----
// Adjust if your project folder name differs (e.g., '/Phoenix_Pizza/public')
// Try to auto-detect, but allow override via .env BASE_URL if present.
$__docroot = rtrim(str_replace('\\', '/', dirname(__FILE__)), '/');
$__envPath = $__docroot . '/.env';

$__env = [];
if (file_exists($__envPath)) {
    foreach (file($__envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2));
        $__env[$k] = $v;
    }
}

$BASE_URL = $__env['BASE_URL'] ?? ( // e.g. http://localhost/Phoenix_Pizza/public
    (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http') . '://' .
    ($_SERVER['HTTP_HOST'] ?? 'localhost') .
    rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/public/index.php'), '/\\')
);

// Normalize
if (!str_ends_with($BASE_URL, '/')) $BASE_URL .= '/';

// ---- Secure session start ----
if (session_status() !== PHP_SESSION_ACTIVE) {
    // Harden cookie settings
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? 80) == 443);
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'] ?? '',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('pp_sess');
    session_start();
}

// ---- Autoloader ----
spl_autoload_register(function ($class) {
    // Only handle our namespace
    $prefix = 'PhoenixPizza\\';
    $base_dir = __DIR__ . '/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Make BASE_URL available everywhere
define('PP_BASE_URL', $BASE_URL);
