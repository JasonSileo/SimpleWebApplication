<?php include 'templates/header.php'; ?>

<div class="container mt-4">
    <h2>Menu</h2>
    <div class="row">
        <?php if (!empty($pizzas) && is_array($pizzas)): ?>
            <?php foreach ($pizzas as $pizza): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($pizza['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($pizza['description']) ?></p>
                            <p><strong>Base: </strong> $<?= number_format($pizza['price'], 2) ?></p>

                            <form action="?route=cart_add" method="POST" class="mt-auto">
                                <input type="hidden" name="pizza_id" value="<?= $pizza['id'] ?>">
                                <input type="hidden" name="pizza_name" value="<?= htmlspecialchars($pizza['name']) ?>">
                                <input type="hidden" name="base_price" value="<?= $pizza['price'] ?>">

                                <div class="mb-2">
                                    <label for="size<?= $pizza['id'] ?>">Size:</label>
                                    <select name="size" class="form-select form-select-sm" id="size<?= $pizza['id'] ?>">
                                        <?php foreach ($sizes as $size): ?>
                                            <option value="<?= $size ?>">
                                                <?= ucfirst($size) ?>
                                                (+$
                                                <?= $size === 'small' ? '0.00' : ($size === 'medium' ? '2.00' : '4.00') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <?php if (!empty($pizza['toppings'])): ?>
                                    <div class="mb-2">
                                        <label>Add Toppings:</label>
                                        <?php foreach ($pizza['toppings'] as $topping): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="toppings[]"
                                                       value="<?= $topping['id'] ?>"
                                                       id="top<?= $pizza['id'] . '-' . $topping['id'] ?>">
                                                <label class="form-check-label"
                                                       for="top<?= $pizza['id'] . '-' . $topping['id'] ?>">
                                                    <?= htmlspecialchars($topping['name']) ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-2">
                                    <label>Quantity:</label>
                                    <input type="number" name="qty" value="1" min="1"
                                           class="form-control form-control-sm">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm w-100">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No pizzas found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
