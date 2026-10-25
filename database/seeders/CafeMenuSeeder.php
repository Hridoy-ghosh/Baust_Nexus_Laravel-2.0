<?php

namespace Database\Seeders;

use App\Models\CafeItem;
use Illuminate\Database\Seeder;

class CafeMenuSeeder extends Seeder
{
    public function run(): void
    {
        CafeItem::whereIn('name', [
            'Hot Chicken Singara & Samosa (2 Pcs)',
            'BAUST Special Chicken Khichuri Bowl',
            'Egg Fried Rice with Crispy Chili Chicken',
            'Cold Coffee with Ice Cream Float',
        ])->delete();

        foreach (config('cafe.menu') as $item) {
            CafeItem::firstOrCreate(
                ['name' => $item['name']],
                [
                    'category' => $item['category'],
                    'price' => $item['price'],
                    'image_url' => $item['icon'],
                    'description' => 'Campus canteen favourite, freshly served at BAUST.',
                    'is_available' => true,
                    'is_special_offer' => false,
                ]
            );
        }

        CafeItem::firstOrCreate(
            ['name' => 'Bubby Cuew & Porota'],
            [
                'category' => 'snacks',
                'price' => 70,
                'image_url' => '🥞',
                'description' => 'Tuesday weekly special: Bubby cuew with porota.',
                'is_available' => true,
                'is_special_offer' => true,
            ]
        );

        foreach (config('cafe.weekly_menu') as $day) {
            foreach ($day['meals'] as $meal) {
                if (isset($meal['products'])) {
                    continue;
                }

                CafeItem::firstOrCreate(
                    ['name' => $meal['name']],
                    [
                        'category' => 'combos',
                        'price' => $meal['price'],
                        'image_url' => '🍱',
                        'description' => $meal['items'],
                        'is_available' => true,
                        'is_special_offer' => true,
                    ]
                );
            }
        }
    }
}
