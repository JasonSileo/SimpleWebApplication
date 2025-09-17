USE phoenix_pizza_db;

INSERT IGNORE INTO users (email, password_hash, name) VALUES
('demo@Phoenix's Pizza.test', '$2y$10$demo_dont_use_in_prod', 'Demo User');

-- Products
INSERT INTO products (name, description, price) VALUES
('Margherita', 'Tomato sauce, fresh mozzarella, basil', 10.99),
('Pepperoni', 'Classic cured pepperoni & mozzarella', 12.49),
('BBQ Chicken', 'BBQ sauce, chicken, red onion, cilantro', 13.99)
('Ghost Pepper Inferno', 'Spicy red sauce base, mozzarella, ghost peppers, jalapeños, chili flakes — not for the faint of heart!', 12.99, 1)
('Pickle Pizza', 'Our Signature Garlic Butter Base topped with House Blend Cheese, Bacon, Pickles, and finished with a drizzle of Ranch.', 11.49, 1);

-- Toppings
INSERT INTO toppings (name, extra_price) VALUES
('Extra Cheese', 1.50),
('Mushrooms', 1.25),
('Onions', 0.95),
('Olives', 1.10),
('Jalapeños', 1.10),
('Basil', 0.75),
('Pepperoni', 1.75),
('Ghost Peppers', 1.25),
('Pickles', .25),
('Ranch Drizzle', .75),
('Bacon', .75);
('Chicken', 2.25);

-- Product ↔ allowed toppings
-- Margherita (assumes it is id = 1)
INSERT INTO product_topping (product_id, topping_id)
SELECT 1, id FROM toppings WHERE name IN ('Basil','Extra Cheese','Mushrooms','Onions','Olives','Jalapeños');

-- Pepperoni (assumes it is id = 2)
INSERT INTO product_topping (product_id, topping_id)
SELECT 2, id FROM toppings WHERE name IN ('Pepperoni','Extra Cheese','Mushrooms','Onions','Olives','Jalapeños');

-- BBQ Chicken (assumes it is id = 3)
INSERT INTO product_topping (product_id, topping_id)
SELECT 3, id FROM toppings WHERE name IN ('Chicken','Extra Cheese','Onions','Jalapeños','Mushrooms');

-- Sample order with items
INSERT INTO orders (user_id, status, subtotal, tax, total, payment_ref)
VALUES (1, 'paid', 24.98, 2.00, 26.98, 'TESTPAY123');

SET @order_id = LAST_INSERT_ID();

-- Item 1: Pepperoni x1 with extra cheese + jalapeños
INSERT INTO order_items (order_id, product_id, quantity, unit_price, selected_toppings)
VALUES (
  @order_id,
  (SELECT id FROM products WHERE name='Pepperoni'),
  1,
  12.49,
  JSON_ARRAY(
    (SELECT id FROM toppings WHERE name='Extra Cheese'),
    (SELECT id FROM toppings WHERE name='Jalapeños')
  )
);

-- Item 2: Margherita x1 with basil
INSERT INTO order_items (order_id, product_id, quantity, unit_price, selected_toppings)
VALUES (
  @order_id,
  (SELECT id FROM products WHERE name='Margherita'),
  1,
  10.99,
  JSON_ARRAY(
    (SELECT id FROM toppings WHERE name='Basil')
  )
);