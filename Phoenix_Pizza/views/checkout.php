<?php include 'templates/header.php'; ?>

<div class="container mt-5">
    <h2>Checkout</h2>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-warning">Your cart is empty. <a href="?route=menu">Go to menu</a>.</div>
    <?php else: ?>
        <h5 class="mt-4">Order Summary</h5>
        <ul class="list-group mb-4">
            <?php
                $cart = $_SESSION['cart'];
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

        <div class="mb-4 text-end">
            <p><strong>Subtotal:</strong> $<?= number_format($subtotal, 2) ?></p>
            <p><strong>Tax (8%):</strong> $<?= number_format($tax, 2) ?></p>
            <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>
        </div>

        <h5>Customer Info</h5>
        <form action="?route=checkout_submit" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" name="phone" id="phone" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Delivery Address</label>
                <textarea class="form-control" name="address" id="address" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-success">Place Order</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>
