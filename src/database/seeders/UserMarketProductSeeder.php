<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Market;
use App\Models\Product;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserMarketProductSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'super-admin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'viewer']);

        $user = User::create([
            'name'     => 'Test User',
            'email'    => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        $market = Market::create([
            'name'      => 'Tech Market',
            'domain' => "http://localhost:5173",
            'icon'      => 'https://example.com/icon.png',
        ]);

        $categories = [
            'Keyboards',
            'GPUs',
            'CPUs',
            'RAM',
            'Hardrive'
        ];

        DB::table('market_user_role')->insert([
            'market_id' => $market->id,
            'user_id'   => $user->id,
            'role_id'   => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $categoryModels = collect($categories)->map(function ($name) {
            return Category::create(['name' => $name, 'slug' => Str::slug($name)]);
        });

        $graphicsCards = [
            ['brand' => 'NVIDIA', 'model' => 'RTX 4090',           'min_cost' => 1650, 'max_cost' => 1800, 'profit' => 400, 'category' => 'GPUs'],
            ['brand' => 'NVIDIA', 'model' => 'RTX 4080',           'min_cost' => 1200, 'max_cost' => 1400, 'profit' => 200, 'category' => 'GPUs'],
            ['brand' => 'NVIDIA', 'model' => 'RTX 4070 Ti',        'min_cost' => 850,  'max_cost' => 950,  'profit' => 150, 'category' => 'GPUs'],
            ['brand' => 'AMD',    'model' => 'Radeon RX 7900 XTX', 'min_cost' => 950,  'max_cost' => 1050, 'profit' => 200, 'category' => 'GPUs'],
            ['brand' => 'AMD',    'model' => 'Radeon RX 7800 XT',  'min_cost' => 550,  'max_cost' => 650,  'profit' => 150, 'category' => 'GPUs'],
            ['brand' => 'AMD',    'model' => 'Radeon RX 7700 XT',  'min_cost' => 450,  'max_cost' => 550,  'profit' => 120, 'category' => 'GPUs'],
            ['brand' => 'Intel',  'model' => 'Arc A770',           'min_cost' => 350,  'max_cost' => 450,  'profit' => 100, 'category' => 'GPUs'],
            ['brand' => 'Intel',  'model' => 'Arc A750',           'min_cost' => 250,  'max_cost' => 350,  'profit' => 80,  'category' => 'GPUs'],
            ['brand' => 'NVIDIA', 'model' => 'RTX 3060 Ti',        'min_cost' => 320,  'max_cost' => 400,  'profit' => 100, 'category' => 'GPUs'],
            ['brand' => 'AMD',    'model' => 'Radeon RX 6700 XT',  'min_cost' => 350,  'max_cost' => 450,  'profit' => 120, 'category' => 'GPUs'],
        ];

        foreach ($graphicsCards as $card) {

            $cost = rand($card['min_cost'], $card['max_cost']);
            $sale = $cost + $card['profit'];

            $product = Product::create([
                'market_id'   => $market->id,
                'reference'   => 'GPU-' . Str::upper(Str::random(6)),
                'stock'       => rand(1, 30),
                'name'        => $card['model'],
                'brand'       => $card['brand'],
                'cost_price'  => $cost,
                'sale_price'  => $sale,
                'is_active'   => true,
                'description' => 'Powerful ' . $card['brand'] . ' graphics card model ' . $card['model'],
            ]);

            $category = $categoryModels->firstWhere('name', $card['category']);
            if ($category) {
                $product->categories()->attach($category->id);
            }
        }

        $extraProducts = [
            ['brand' => 'Intel', 'model' => 'Core i9-14900K', 'cost_price' => 580, 'sale_price' => 650, 'category' => 'CPUs'],
            ['brand' => 'Intel', 'model' => 'Core i7-14700K', 'cost_price' => 420, 'sale_price' => 490, 'category' => 'CPUs'],
            ['brand' => 'Intel', 'model' => 'Core i5-14600K', 'cost_price' => 340, 'sale_price' => 400, 'category' => 'CPUs'],
            ['brand' => 'AMD', 'model' => 'Ryzen 9 7950X3D', 'cost_price' => 590, 'sale_price' => 680, 'category' => 'CPUs'],
            ['brand' => 'AMD', 'model' => 'Ryzen 7 7800X3D', 'cost_price' => 400, 'sale_price' => 470, 'category' => 'CPUs'],
            ['brand' => 'AMD', 'model' => 'Ryzen 5 7600X', 'cost_price' => 260, 'sale_price' => 330, 'category' => 'CPUs'],
            ['brand' => 'Intel', 'model' => 'Core i5-13400F', 'cost_price' => 180, 'sale_price' => 240, 'category' => 'CPUs'],
            ['brand' => 'AMD', 'model' => 'Ryzen 5 5600', 'cost_price' => 140, 'sale_price' => 200, 'category' => 'CPUs'],

            ['brand' => 'Logitech', 'model' => 'MX Keys S', 'cost_price' => 90, 'sale_price' => 120, 'category' => 'Keyboards'],
            ['brand' => 'Corsair', 'model' => 'K70 RGB Pro', 'cost_price' => 120, 'sale_price' => 160, 'category' => 'Keyboards'],
            ['brand' => 'Razer', 'model' => 'BlackWidow V4 X', 'cost_price' => 110, 'sale_price' => 150, 'category' => 'Keyboards'],

            ['brand' => 'Corsair', 'model' => 'Vengeance DDR5 32GB 6000MHz', 'cost_price' => 130, 'sale_price' => 180, 'category' => 'RAM'],
            ['brand' => 'Kingston', 'model' => 'Fury Beast DDR5 32GB 6000MHz', 'cost_price' => 125, 'sale_price' => 170, 'category' => 'RAM'],
            ['brand' => 'G.Skill', 'model' => 'Trident Z5 RGB 32GB 6400MHz', 'cost_price' => 150, 'sale_price' => 200, 'category' => 'RAM'],

            ['brand' => 'Samsung', 'model' => '990 PRO 2TB NVMe', 'cost_price' => 160, 'sale_price' => 220, 'category' => 'Hardrive'],
            ['brand' => 'Western Digital', 'model' => 'Black SN850X 2TB NVMe', 'cost_price' => 150, 'sale_price' => 210, 'category' => 'Hardrive'],
            ['brand' => 'Crucial', 'model' => 'T700 2TB NVMe Gen5', 'cost_price' => 280, 'sale_price' => 350, 'category' => 'Hardrive'],
            ['brand' => 'Seagate', 'model' => 'Barracuda 4TB HDD', 'cost_price' => 75, 'sale_price' => 110, 'category' => 'Hardrive'],
        ];

        foreach ($extraProducts as $item) {
            $product = Product::create([
                'market_id'   => $market->id,
                'reference'   => 'REF-' . strtoupper(Str::random(6)),
                'stock'       => rand(5, 50),
                'name'        => $item['model'],
                'brand'       => $item['brand'],
                'cost_price'  => $item['cost_price'],
                'sale_price'  => $item['sale_price'],
                'is_active'   => true,
                'description' => "Producto {$item['brand']} modelo {$item['model']}",
            ]);

            $category = $categoryModels->firstWhere('name', $item['category']);
            if ($category) {
                $product->categories()->attach($category->id);
            }
        }
    }
}
