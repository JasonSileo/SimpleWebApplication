<?php
// views/cart.php
use PhoenixPizza\Cart;
Cart::start();
$items = Cart::items();
$totals = Cart::totals();
?>
<h2 class="mb-3">Your Cart</h2>
<?php if (empty($items)): ?>
  <div class="alert alert-info">Your cart is empty. Visit the <a href="<?php echo htmlspecialchars(PP_BASE_URL . 'index.php?route=menu'); ?>">Menu</a> to add items.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead><tr><th>Item</th><th>Size</th><th>Qty</th><th class="text-end">Unit</th><th class="text-end">Line</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($items as $sig => $it): ?>
        <tr>
          <td><?php echo htmlspecialchars($it['name']); ?></td>
          <td><?php echo htmlspecialchars(ucfirst($it['size'])); ?></td>
          <td style="max-width:100px">
            <form method="post" action="<?php echo htmlspecialchars(PP_BASE_URL . 'cart.php?action=update'); ?>" class="d-flex gap-2">
              <input type="hidden" name="signature" value="<?php echo htmlspecialchars($sig); ?>">
              <input class="form-control form-control-sm" type="number" min="0" name="qty" value="<?php echo (int)$it['qty']; ?>">
              <button class="btn btn-outline-secondary btn-sm">Update</button>
            </form>
          </td>
          <td class="text-end">$<?php echo number_format($it['unit_price'], 2); ?></td>
          <td class="text-end">$<?php echo number_format($it['line_total'], 2); ?></td>
          <td class="text-end">
            <form method="post" action="<?php echo htmlspecialchars(PP_BASE_URL . 'cart.php?action=remove'); ?>">
              <input type="hidden" name="signature" value="<?php echo htmlspecialchars($sig); ?>">
              <button class="btn btn-outline-danger btn-sm">Remove</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="d-flex justify-content-end">
    <div class="card" style="min-width: 320px;">
      <div class="card-body">
        <div class="d-flex justify-content-between"><span>Subtotal</span><span>$<?php echo number_format($totals['subtotal'], 2); ?></span></div>
        <div class="d-flex justify-content-between"><span>Tax</span><span>$<?php echo number_format($totals['tax'], 2); ?></span></div>
        <hr>
        <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>$<?php echo number_format($totals['total'], 2); ?></span></div>
        <a class="btn btn-success w-100 mt-3" href="<?php echo htmlspecialchars(PP_BASE_URL . 'index.php?route=checkout'); ?>">Proceed to Checkout</a>
      </div>
    </div>
  </div>
<?php endif; ?>
