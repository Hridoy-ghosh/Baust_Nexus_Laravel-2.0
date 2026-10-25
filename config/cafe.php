<?php

return [
    'payments' => [
        'bkash_number' => env('CAFE_BKASH_NUMBER', '01748608369'),
        'nagad_number' => env('CAFE_NAGAD_NUMBER', '01748608369'),
    ],

    'categories' => [
        'breakfast' => 'Breakfast & Bakery',
        'lunch' => 'Lunch Meals',
        'snacks' => 'Fast Food & Snacks',
        'beverages' => 'Drinks & Beverages',
        'combos' => 'Meal Combos',
    ],

    'menu' => [
        ['name' => 'Singara', 'category' => 'snacks', 'price' => 10, 'icon' => '🥟'],
        ['name' => 'Samosa', 'category' => 'snacks', 'price' => 10, 'icon' => '🥟'],
        ['name' => 'Cha', 'category' => 'beverages', 'price' => 10, 'icon' => '🍵'],
        ['name' => 'Coffee', 'category' => 'beverages', 'price' => 20, 'icon' => '☕'],
        ['name' => 'Vegetable Roll', 'category' => 'snacks', 'price' => 20, 'icon' => '🌯'],
        ['name' => 'Chicken Roll', 'category' => 'snacks', 'price' => 30, 'icon' => '🌯'],
        ['name' => 'Pizza', 'category' => 'snacks', 'price' => 90, 'icon' => '🍕'],
        ['name' => 'Burger', 'category' => 'snacks', 'price' => 50, 'icon' => '🍔'],
        ['name' => 'Piyaju', 'category' => 'snacks', 'price' => 10, 'icon' => '🧆'],
        ['name' => 'Sandwich', 'category' => 'snacks', 'price' => 50, 'icon' => '🥪'],
        ['name' => 'Chips', 'category' => 'snacks', 'price' => 10, 'icon' => '🍟'],
        ['name' => 'Biscuits', 'category' => 'snacks', 'price' => 10, 'icon' => '🍪'],
        ['name' => 'Chanachur', 'category' => 'snacks', 'price' => 10, 'icon' => '🥜'],
        ['name' => 'Chocolate', 'category' => 'snacks', 'price' => 10, 'icon' => '🍫'],
        ['name' => 'Cake', 'category' => 'snacks', 'price' => 10, 'icon' => '🍰'],
        ['name' => 'Juice', 'category' => 'beverages', 'price' => 20, 'icon' => '🧃'],
        ['name' => 'Cold Drink', 'category' => 'beverages', 'price' => 20, 'icon' => '🥤'],
    ],

    'weekly_menu' => [
        [
            'day' => 'Sunday',
            'special' => false,
            'meals' => [
                ['type' => 'Breakfast', 'name' => 'Sunday Breakfast Combo', 'items' => 'Porota, dal, cha', 'price' => 30],
                ['type' => 'Lunch', 'name' => 'Sunday Lunch Combo', 'items' => 'Vat, dal, vegetable, chicken', 'price' => 70],
                ['type' => 'Snacks', 'products' => ['Pizza', 'Juice', 'Cold Drink']],
                ['type' => 'Dinner', 'name' => 'Sunday Dinner Combo', 'items' => 'Vat, dal, alu vorta, piyaju', 'price' => 30],
            ],
        ],
        [
            'day' => 'Monday',
            'special' => false,
            'meals' => [
                ['type' => 'Breakfast', 'name' => 'Monday Breakfast Combo', 'items' => 'Dal puri, dal, cha', 'price' => 30],
                ['type' => 'Lunch', 'name' => 'Monday Lunch Combo', 'items' => 'Vat, dal, vegetable, egg curry', 'price' => 50],
                ['type' => 'Snacks', 'products' => ['Burger', 'Juice', 'Cold Drink']],
                ['type' => 'Dinner', 'name' => 'Monday Dinner Combo', 'items' => 'Vat, dal, badam vorta, piyaju', 'price' => 35],
            ],
        ],
        [
            'day' => 'Tuesday',
            'special' => false,
            'meals' => [
                ['type' => 'Breakfast', 'name' => 'Tuesday Breakfast Combo', 'items' => 'Khichuri, dim vaja, cha', 'price' => 40],
                ['type' => 'Lunch', 'name' => 'Tuesday Lunch Combo', 'items' => 'Vat, dal, vegetable, fish curry', 'price' => 70],
                ['type' => 'Snacks', 'products' => ['Bubby Cuew & Porota', 'Juice', 'Cold Drink']],
                ['type' => 'Dinner', 'name' => 'Tuesday Dinner Combo', 'items' => 'Vat, dal, vegetable, egg curry', 'price' => 60],
            ],
        ],
        [
            'day' => 'Wednesday',
            'special' => false,
            'meals' => [
                ['type' => 'Breakfast', 'name' => 'Wednesday Breakfast Combo', 'items' => 'Noodles, boiled egg, cha', 'price' => 40],
                ['type' => 'Lunch', 'name' => 'Wednesday Lunch Combo', 'items' => 'Vat, dal, vegetable, mutton curry', 'price' => 100],
                ['type' => 'Snacks', 'products' => ['Pizza', 'Juice', 'Cold Drink']],
                ['type' => 'Dinner', 'name' => 'Wednesday Dinner Combo', 'items' => 'Vat, dal, alu vorta, fish curry', 'price' => 70],
            ],
        ],
        [
            'day' => 'Thursday',
            'special' => false,
            'meals' => [
                ['type' => 'Breakfast', 'name' => 'Thursday Breakfast Combo', 'items' => 'Bread & jelly, boiled egg, cha', 'price' => 40],
                ['type' => 'Lunch', 'name' => 'Thursday Lunch Combo', 'items' => 'Vat, dal, vegetable, fish curry', 'price' => 70],
                ['type' => 'Snacks', 'products' => ['Sandwich', 'Juice', 'Cold Drink']],
                ['type' => 'Dinner', 'name' => 'Thursday Dinner Combo', 'items' => 'Vat, dal, badam vorta, egg curry', 'price' => 60],
            ],
        ],
        [
            'day' => 'Friday',
            'special' => true,
            'meals' => [
                ['type' => 'Breakfast', 'name' => 'Friday Breakfast Combo', 'items' => 'Khichuri, boiled egg, cha', 'price' => 40],
                ['type' => 'Lunch', 'name' => 'Chicken Biriyani', 'items' => 'Chicken biriyani', 'price' => 70],
                ['type' => 'Snacks', 'products' => ['Pizza', 'Juice', 'Cold Drink']],
                ['type' => 'Dinner', 'name' => 'Friday Dinner Combo', 'items' => 'Polao, rost, dal, beguni', 'price' => 100],
            ],
        ],
        [
            'day' => 'Saturday',
            'special' => true,
            'meals' => [
                ['type' => 'Breakfast', 'name' => 'Saturday Breakfast Combo', 'items' => 'Khichuri, dim vaja, cha', 'price' => 40],
                ['type' => 'Lunch', 'name' => 'Mutton Biriyani', 'items' => 'Mutton biriyani', 'price' => 100],
                ['type' => 'Snacks', 'products' => ['Burger', 'Juice', 'Cold Drink']],
                ['type' => 'Dinner', 'name' => 'Saturday Dinner Combo', 'items' => 'Polao, mutton, dal, beguni', 'price' => 120],
            ],
        ],
    ],
];
