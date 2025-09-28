<?php

namespace PhoenixPizza\controllers;

class MenuController
{
    public function index()
    {
        $pizzas = [
            [
                'id' => 1,
                'name' => 'BBQ Chicken',
                'description' => 'BBQ sauce, chicken, red onion, cilantro',
                'price' => 13.99,
                'toppings' => [
                    ['id' => 1, 'name' => 'Chicken (+$2.25)'],
                    ['id' => 2, 'name' => 'Extra Cheese (+$1.50)'],
                    ['id' => 3, 'name' => 'Jalapeños (+$1.10)'],
                    ['id' => 4, 'name' => 'Mushrooms (+$1.25)'],
                    ['id' => 5, 'name' => 'Onions (+$0.95)']
                ]
            ],
            [
                'id' => 2,
                'name' => 'Four Artisan Cheese',
                'description' => 'Mozzarella, provolone, parmesan, feta on a red or white base.',
                'price' => 12.49,
                'toppings' => []
            ],
            [
                'id' => 3,
                'name' => 'Ghost Pepper Inferno',
                'description' => 'Spicy red sauce base, mozzarella, ghost peppers, jalapeños, chili flakes.',
                'price' => 12.99,
                'toppings' => [
                    ['id' => 2, 'name' => 'Extra Cheese (+$1.50)'],
                    ['id' => 10, 'name' => 'Ghost Peppers (+$1.25)'],
                    ['id' => 3, 'name' => 'Jalapeños (+$1.10)'],
                    ['id' => 5, 'name' => 'Onions (+$0.95)']
                ]
            ],
            [
                'id' => 4,
                'name' => 'Margherita',
                'description' => 'Tomato sauce, fresh mozzarella, basil',
                'price' => 10.99,
                'toppings' => [
                    ['id' => 11, 'name' => 'Basil (+$0.75)'],
                    ['id' => 2, 'name' => 'Extra Cheese (+$1.50)'],
                    ['id' => 3, 'name' => 'Jalapeños (+$1.10)'],
                    ['id' => 4, 'name' => 'Mushrooms (+$1.25)'],
                    ['id' => 6, 'name' => 'Olives (+$1.10)'],
                    ['id' => 5, 'name' => 'Onions (+$0.95)']
                ]
            ],
            [
                'id' => 5,
                'name' => 'Pepperoni',
                'description' => 'Classic cured pepperoni & mozzarella',
                'price' => 12.49,
                'toppings' => [
                    ['id' => 2, 'name' => 'Extra Cheese (+$1.50)'],
                    ['id' => 3, 'name' => 'Jalapeños (+$1.10)'],
                    ['id' => 4, 'name' => 'Mushrooms (+$1.25)'],
                    ['id' => 6, 'name' => 'Olives (+$1.10)'],
                    ['id' => 5, 'name' => 'Onions (+$0.95)'],
                    ['id' => 12, 'name' => 'Pepperoni (+$1.75)']
                ]
            ],
            [
                'id' => 6,
                'name' => 'Pickle Pizza',
                'description' => 'Garlic butter base, bacon, pickles, and ranch drizzle.',
                'price' => 11.49,
                'toppings' => [
                    ['id' => 7, 'name' => 'Bacon (+$0.75)'],
                    ['id' => 2, 'name' => 'Extra Cheese (+$1.50)'],
                    ['id' => 5, 'name' => 'Onions (+$0.95)'],
                    ['id' => 8, 'name' => 'Pickles (+$0.95)'],
                    ['id' => 9, 'name' => 'Ranch Drizzle (+$0.75)']
                ]
            ],
            [
                'id' => 7,
                'name' => 'Garlic Bread',
                'description' => 'Toasted bread with garlic butter and herbs.',
                'price' => 4.99,
                'toppings' => []
            ],
            [
                'id' => 8,
                'name' => 'Garlic Knots',
                'description' => 'Hand-tied knots brushed with garlic butter and parmesan.',
                'price' => 5.49,
                'toppings' => []
            ],
            [
                'id' => 9,
                'name' => 'House Salad',
                'description' => 'Mixed greens, tomato, cucumber, red onion, house dressing.',
                'price' => 6.99,
                'toppings' => []
            ]
        ];

        $sizes = ['small', 'medium', 'large'];

        $this->render('menu', [
            'pizzas' => $pizzas,
            'sizes' => $sizes,
            'pageTitle' => 'Menu'
        ]);
    }

    private function render($view, $vars = [])
    {
        extract($vars);
        include __DIR__ . '/../../views/' . $view . '.php';
    }
}
