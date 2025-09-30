<?php include 'templates/header.php'; ?>

<div class="container mt-5">
    <h2>Order Confirmation</h2>

    <div class="alert alert-success">
        Thank you, <?= htmlspecialchars($order['name']) ?>! Your order has been received.
    </div>

    <p><strong>Delivery Address:</strong><br><?= nl2br(htmlspecialchars($order['address'])) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
    <p><strong>Order Time:</strong> <?= htmlspecialchars($order['time']) ?></p>

    <h5 class="mt-4">Your Order:</h5>
    <ul class="list-group">
        <?php
        $cart = $order['cart'];
        $subtotal = 0;
        foreach ($cart as $item):
            $lineTotal = $item['unit_price'] * $item['qty'];
            $subtotal += $lineTotal;
        ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                    <strong><?= htmlspecialchars($item['name']) ?> (<?= ucfirst($item['size']) ?>)</strong><br>
                    <small class="text-muted"><?= implode(', ', $item['toppings']) ?></small><br>
                    Qty: <?= $item['qty'] ?>
                </div>
                <span>$<?= number_format($lineTotal, 2) ?></span>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php
    $tax = round($subtotal * 0.08, 2);
    $total = $subtotal + $tax;
    ?>

    <div class="text-end mt-3">
        <p><strong>Subtotal:</strong> $<?= number_format($subtotal, 2) ?></p>
        <p><strong>Tax:</strong> $<?= number_format($tax, 2) ?></p>
        <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>
    </div>

    <div class="mt-4">
        <a href="?route=menu" class="btn btn-primary">Order Again</a>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
