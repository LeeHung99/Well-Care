<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $name = "sp";

        for ($i = 1; $i <= 100; $i++) {
            $name = $name . $i;
            $id_third_cate = rand(1, 47);
            $id_image = 1;
            $avtar = $name;
            $price = rand(100000, 5000000);
            $in_stoke = rand(1, 100);
            $hot = rand(0, 1);
            $sold = rand(1, 50);
            $sale = rand(1, 50);

            DB::table('products')->insert([
                'id_third_category' => $id_third_cate,
                'id_image_product' => $id_image,
                'name' => $name,
                'avatar' => $avtar,
                'price' => $price,
                'in_stock' => $in_stoke,
                'hot' => $hot,
                'sold' => $sold,
                'sale' => $sale,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
