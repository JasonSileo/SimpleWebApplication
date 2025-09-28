<?php include 'templates/header.php'; ?>

<div class="container mt-5">
    <h2>Your Cart</h2>

    <?php if (empty($items)): ?>
        <div class="alert alert-warning">
            Your cart is empty.
        </div>
        <a href="?route=menu" class="btn btn-secondary">Back to Menu</a>
    <?php else: ?>
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Pizza</th>
                    <th>Size</th>
                    <th>Toppings</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Line Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $key => $item): ?>
                    <?php $lineTotal = $item['unit_price'] * $item['qty']; ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($item['size'])) ?></td>
                        <td>
                            <div class="text-muted small">
                                <?= empty($item['toppings']) ? 'None' : implode(', ', $item['toppings']) ?>
                            </div>
                        </td>
                        <td style="width: 120px;">
                            <form action="?route=cart_update" method="POST" class="d-flex">
                                <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
                                <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1" class="form-control form-control-sm me-1">
                                <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                            </form>
                        </td>
                        <td>$<?= number_format($item['unit_price'], 2) ?></td>
                        <td>$<?= number_format($lineTotal, 2) ?></td>
                        <td>
                            <a href="?route=cart_remove&key=<?= urlencode($key) ?>" class="btn btn-sm btn-danger">
                                Remove
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-end">
            <p><strong>Subtotal:</strong> $<?= $totals['subtotal'] ?></p>
            <p><strong>Tax:</strong> $<?= $totals['tax'] ?></p>
            <p><strong>Total:</strong> $<?= $totals['total'] ?></p>
        </div>

        <div class="mt-3 d-flex justify-content-between">
            <a href="?route=menu" class="btn btn-secondary">Continue Shopping</a>
            <a href="?route=checkout" class="btn btn-success">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>
