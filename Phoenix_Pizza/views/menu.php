<?php
// views/menu.php
use PhoenixPizza\DAO\ProductDAO;
use PhoenixPizza\DAO\ToppingDAO;

$dao  = new ProductDAO();
$tDao = new ToppingDAO();

// IMPORTANT: ProductDAO::listActive() must select is_custom in the SQL:
$products = $dao->listActive();
?>
<h2 class="mb-3">Menu</h2>
<div class="row g-3">
<?php foreach ($products as $p): ?>
  <div class="col-md-6 col-lg-4">
    <div class="card h-100">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title mb-1"><?php echo htmlspecialchars($p['name']); ?></h5>
        <p class="card-text small flex-grow-1"><?php echo htmlspecialchars($p['description']); ?></p>
        <div class="text-muted small mb-2">Base: $<?php echo number_format($p['price'], 2); ?></div>

        <?php $isCustom = !empty($p['is_custom']); ?>

        <?php if ($isCustom): ?>
          <!-- Create Your Own: send user to the dedicated customizer page -->
          <a class="btn btn-secondary btn-sm w-100"
             href="<?php echo htmlspecialchars(PP_BASE_URL . 'index.php?route=custom&pid=' . (int)$p['id']); ?>">
             Customize
          </a>
        <?php else: ?>
          <!-- Standard pizzas: size + optional toppings + qty + Add to Cart -->
          <form method="post" action="<?php echo htmlspecialchars(PP_BASE_URL . 'cart.php?action=add'); ?>" class="mt-auto">
            <input type="hidden" name="product_id" value="<?php echo (int)$p['id']; ?>">

            <div class="mb-2">
              <label class="form-label small">Size</label>
              <select class="form-select form-select-sm" name="size">
                <option value="small">Small</option>
                <option value="medium" selected>Medium (+$2.00)</option>
                <option value="large">Large (+$4.00)</option>
                <option value="xl">XL (+$6.00)</option>
              </select>
            </div>

            <?php
              // Show allowed toppings (checkboxes)
              $allowed = $tDao->listAllowed((int)$p['id']); // returns id,name,extra_price,category
              if (!empty($allowed)):
            ?>
              <div class="mb-2">
                <div class="fw-semibold small mb-1">Add Toppings</div>
                <?php foreach ($allowed as $t): ?>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="topping_ids[]" value="<?php echo (int)$t['id']; ?>" id="t_<?php echo (int)$p['id']; ?>_<?php echo (int)$t['id']; ?>">
                    <label class="form-check-label small" for="t_<?php echo (int)$p['id']; ?>_<?php echo (int)$t['id']; ?>">
                      <?php echo htmlspecialchars($t['name']); ?>
                      (+$<?php echo number_format((float)$t['extra_price'], 2); ?>)
                    </label>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <div class="mb-2">
              <label class="form-label small">Quantity</label>
              <input class="form-control form-control-sm" type="number" min="1" value="1" name="qty">
            </div>

            <button class="btn btn-primary btn-sm w-100">Add to Cart</button>
          </form>
        <?php endif; ?>

      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
