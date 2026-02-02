<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // បញ្ចូលទំនិញគំរូ
        $products = [
            // --- គ្រឿងសមុទ្រ (Seafood) ---
            [
                'name' => 'ក្តាមសេះ (Flower Crab)',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/8/87/Portunus_pelagicus.jpg', // រូបគំរូ
                'price' => 15.00,
                'stock' => 50,
                'unit' => 'kg',
                'category' => 'seafood',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'បង្កងប៉ាក (River Prawn)',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Macrobrachium_rosenbergii_01.jpg/640px-Macrobrachium_rosenbergii_01.jpg',
                'price' => 25.50,
                'stock' => 30,
                'unit' => 'kg',
                'category' => 'seafood',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'មឹកបំពង់ (Squid)',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Squid_komodo.jpg/640px-Squid_komodo.jpg',
                'price' => 8.00,
                'stock' => 100,
                'unit' => 'kg',
                'category' => 'seafood',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- ស្រាបៀរ (Beer) ---
            [
                'name' => 'Hanuman Beer (កំប៉ុង)',
                'image' => 'https://tse2.mm.bing.net/th?id=OIP.F-j_dDqQh3wz1uqKj5z_gAHaHa&pid=Api',
                'price' => 0.75,
                'stock' => 200,
                'unit' => 'can',
                'category' => 'beer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vattanac Beer (កេស)',
                'image' => 'https://tse4.mm.bing.net/th?id=OIP.abc12345sample', // រូបគំរូ
                'price' => 18.50,
                'stock' => 50,
                'unit' => 'case',
                'category' => 'beer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'name' => 'Cambodia Beer (ដប)',
                'image' => 'https://tse3.mm.bing.net/th?id=OIP.samplebeerbottle',
                'price' => 1.50,
                'stock' => 100,
                'unit' => 'bottle',
                'category' => 'beer',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- បន្លែ (Vegetable) ---
            [
                'name' => 'ស្ពៃក្តោប (Cabbage)',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Cabbage_and_cross_section_on_white.jpg/640px-Cabbage_and_cross_section_on_white.jpg',
                'price' => 1.20,
                'stock' => 50,
                'unit' => 'kg',
                'category' => 'vegetable',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ការ៉ុត (Carrot)',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Vegetable-Carrot-Bundle-wStalks.jpg/640px-Vegetable-Carrot-Bundle-wStalks.jpg',
                'price' => 0.90,
                'stock' => 80,
                'unit' => 'kg',
                'category' => 'vegetable',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('products')->insert($products);
    }
}