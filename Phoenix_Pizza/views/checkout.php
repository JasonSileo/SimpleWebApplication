<?php
// views/checkout.php
?>
<h2 class="mb-3">Checkout</h2>
<p>This is a placeholder checkout page. Clicking the button below will create an order from your current cart.</p>
<form method="post" action="<?php echo htmlspecialchars(PP_BASE_URL . 'order_create.php'); ?>">
  <button class="btn btn-primary">Create Order</button>
</form>
<div class="alert alert-secondary mt-3 small">
  Orders are created with status <code>pending</code>. After success, your cart is cleared and an order number is returned by the API.
</div>
