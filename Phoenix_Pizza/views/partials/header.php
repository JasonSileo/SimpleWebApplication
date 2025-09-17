<?php
// views/partials/header.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Phoenix Pizza</title>
  <!-- Bootstrap CSS (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(PP_BASE_URL . 'styles.css'); ?>">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="<?php echo htmlspecialchars(PP_BASE_URL . 'index.php?route=home'); ?>">Phoenix Pizza</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample" aria-controls="navbarsExample" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsExample">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link<?php echo ($_GET['route']??'home')==='home'?' active':''; ?>" href="<?php echo htmlspecialchars(PP_BASE_URL . 'index.php?route=home'); ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link<?php echo ($_GET['route']??'')==='menu'?' active':''; ?>" href="<?php echo htmlspecialchars(PP_BASE_URL . 'index.php?route=menu'); ?>">Menu</a></li>
        <li class="nav-item"><a class="nav-link<?php echo ($_GET['route']??'')==='cart'?' active':''; ?>" href="<?php echo htmlspecialchars(PP_BASE_URL . 'index.php?route=cart'); ?>">Cart</a></li>
        <li class="nav-item"><a class="nav-link<?php echo ($_GET['route']??'')==='checkout'?' active':''; ?>" href="<?php echo htmlspecialchars(PP_BASE_URL . 'index.php?route=checkout'); ?>">Checkout</a></li>
      </ul>
    </div>
  </div>
</nav>
<main class="container my-4">
