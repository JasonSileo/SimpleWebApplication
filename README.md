# SimpleWebApplication
SDC310L
http://localhost/phoenix_pizza/public/index.php

Project Name: Phoenix Pizza MVC Application
Project Description

Phoenix Pizza MVC Application is a web-based ordering system re-architected using the Model-View-Controller (MVC) pattern. The application allows users to browse pizzas, customize orders with toppings and sizes, manage a shopping cart, and complete checkout securely. Features include database persistence, server-side price calculations, validation, error handling, and responsive design.

Application Testing

Test 1: Database Connection and CRUD Operations

Description: Verified that the application could connect to the MySQL database and perform Create, Read, Update, and Delete operations.

How Performed: Used db.php with PDO and prepared statements to insert, read, update, and delete pizzas, toppings, and orders.

Results: Database connected successfully; all CRUD operations worked without errors.

Test 2: Cart Functionality

Description: Checked adding, updating, and removing items from the cart.

How Performed: Used the UI to add pizzas with toppings, adjusted quantities, and removed items.

Results: Cart updated correctly in the session; invalid values were handled gracefully.

Test 3: Price Calculation

Description: Ensured prices calculated correctly on the server side.

How Performed: Compared expected totals (base + size + toppings) with calculated values.

Results: Totals matched expectations and were secure from client-side manipulation.

Test 4: MVC Routing and Architecture

Description: Verified proper routing between pages and adherence to MVC separation.

How Performed: Navigated between /home, /menu, /cart, and /checkout; confirmed data passed from Controllers → Models → Views.

Results: Routes worked correctly; all files rendered with clear MVC separation.

Test 5: Form Validation and Security

Description: Tested validation, sanitization, and CSRF protection.

How Performed: Entered invalid/empty inputs, injected special characters, and tested CSRF token removal.

Results: Inputs were validated and sanitized, special characters escaped, and invalid form submissions blocked.

Test 6: Error Handling

Description: Checked error messages and custom 404 pages.

How Performed: Attempted invalid routes and triggered missing record errors.

Results: User-friendly error pages displayed instead of raw PHP errors.

Test 7: Styling and User Experience

Description: Validated responsive design and usability.

How Performed: Tested the app on desktop and mobile views; checked Bootstrap responsiveness.

Results: Layout was consistent across devices, and UI elements worked smoothly.

Test 8: End-to-End Order Flow

Description: Verified the full flow from menu browsing to checkout.

How Performed: Placed a test order with multiple pizzas and toppings, proceeded through checkout, and confirmed database persistence.

Results: End-to-end order placement worked correctly with no errors.
