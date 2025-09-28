<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle ?? 'Phoenix Pizza') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">
</head>
<body>
<header>
    <h1>Phoenix Pizza</h1>
    <nav>
        <a href="?route=menu/index">Menu</a> |
        <a href="?route=cart/index">Cart</a>
    </nav>
</header>
<main>
