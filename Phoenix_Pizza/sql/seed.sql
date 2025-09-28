USE phoenix_pizza_db;

-- =========================
-- USERS
-- =========================
INSERT IGNORE INTO users (email, password_hash, name) VALUES
('demo@phoenix_pizza.test', '$2y$10$demo_dont_use_in_prod', 'Demo User');

-- =========================
-- PRODUCTS
-- =========================
-- ***PICK **ONE** of the following product blocks***

-- (A) If your products table has columns: id, name, description, price, is_active
--     (NO category, NO is_custom)
-- INSERT INTO products (name, description, price, is_active) VALUES
-- ('Margherita', 'Tomato sauce, fresh mozzarella, basil', 10.99, 1),
-- ('Pepperoni', 'Classic cured pepperoni & mozzarella', 12.49, 1),
-- ('BBQ Chicken', 'BBQ sauce, chicken, red onion, cilantro', 13.99, 1),
-- ('Ghost Pepper Inferno', 'Spicy red sauce base, mozzarella, ghost peppers, jalapeños, chili flakes — not for the faint of heart!', 12.99, 1),
-- ('Pickle Pizza', 'Our Signature Garlic Butter Base topped with House Blend Cheese, Bacon, Pickles, and finished with a drizzle of Ranch.', 11.49, 1),
-- ('Garlic Bread', 'Toasted bread with garlic butter and herbs.', 4.99, 1),
-- ('Garlic Knots', 'Hand-tied knots brushed with garlic butter and parmesan.', 5.49, 1)
-- ON DUPLICATE KEY UPDATE
--   description = VALUES(description),
--   price = VALUES(price),
--   is_active = VALUES(is_active);

-- (B) If your products table ALSO has: category (ENUM) and is_custom (TINYINT)
INSERT INTO products (name, description, price, is_active, category, is_custom) VALUES
('Margherita', 'Tomato sauce, fresh mozzarella, basil', 10.99, 1, 'pizza', 0),
('Pepperoni', 'Classic cured pepperoni & mozzarella', 12.49, 1, 'pizza', 0),
('BBQ Chicken', 'BBQ sauce, chicken, red onion, cilantro', 13.99, 1, 'pizza', 0),
('Ghost Pepper Inferno', 'Spicy red sauce base, mozzarella, ghost peppers, jalapeños, chili flakes — not for the faint of heart!', 12.99, 1, 'pizza', 0),
('Pickle Pizza', 'Our Signature Garlic Butter Base topped with House Blend Cheese, Bacon, Pickles, and finished with a drizzle of Ranch.', 11.49, 1, 'pizza', 0),
('Garlic Bread', 'Toasted bread with garlic butter and herbs.', 4.99, 1, 'sides', 0),
('Garlic Knots', 'Hand-tied knots brushed with garlic butter and parmesan.', 5.49, 1, 'sides', 0)
ON DUPLICATE KEY UPDATE
  description = VALUES(description),
  price = VALUES(price),
  is_active = VALUES(is_active),
  category = VALUES(category),
  is_custom = VALUES(is_custom);

-- =========================
-- TOPPINGS  (idempotent)
-- Requires a UNIQUE index on toppings.name (e.g., uk_toppings_name)
-- =========================
INSERT INTO toppings (name, extra_price) VALUES
('Extra Cheese', 1.50),
('Mushrooms', 1.25),
('Onions', 0.95),
('Olives', 1.10),
('Jalapeños', 1.10),
('Basil', 0.75),
('Pepperoni', 1.75),
('Ghost Peppers', 1.25),
('Pickles', 0.25),
('Ranch Drizzle', 0.75),
('Bacon', 0.75),
('Chicken', 2.25)
ON DUPLICATE KEY UPDATE
  extra_price = VALUES(extra_price);

-- =========================
-- PRODUCT ↔ TOPPING LINKS (by name, NOT hardcoded IDs)
-- Safe to re-run; requires a unique key on (product_id,topping_id)
-- =========================

-- Margherita
INSERT INTO product_topping (product_id, topping_id)
SELECT p.id, t.id
FROM products p JOIN toppings t ON t.name IN ('Basil','Extra Cheese','Mushrooms','Onions','Olives','Jalapeños')
WHERE p.name = 'Margherita'
ON DUPLICATE KEY UPDATE product_id = product_id;

-- Pepperoni
INSERT INTO product_topping (product_id, topping_id)
SELECT p.id, t.id
FROM products p JOIN toppings t ON t.name IN ('Pepperoni','Extra Cheese','Mushrooms','Onions','Olives','Jalapeños')
WHERE p.name = 'Pepperoni'
ON DUPLICATE KEY UPDATE product_id = product_id;

-- BBQ Chicken
INSERT INTO product_topping (product_id, topping_id)
SELECT p.id, t.id
FROM products p JOIN toppings t ON t.name IN ('Chicken','Extra Cheese','Onions','Jalapeños','Mushrooms')
WHERE p.name = 'BBQ Chicken'
ON DUPLICATE KEY UPDATE product_id = product_id;

-- (Optional) Ghost Pepper Inferno
INSERT INTO product_topping (product_id, topping_id)
SELECT p.id, t.id
FROM products p JOIN toppings t ON t.name IN ('Ghost Peppers','Jalapeños','Extra Cheese','Onions')
WHERE p.name = 'Ghost Pepper Inferno'
ON DUPLICATE KEY UPDATE product_id = product_id;

-- (Optional) Pickle Pizza
INSERT INTO product_topping (product_id, topping_id)
SELECT p.id, t.id
FROM products p JOIN toppings t ON t.name IN ('Pickles','Ranch Drizzle','Bacon','Extra Cheese','Onions')
WHERE p.name = 'Pickle Pizza'
ON DUPLICATE KEY UPDATE product_id = product_id;

-- =========================
-- SAMPLE ORDER (optional demo)
-- =========================
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
