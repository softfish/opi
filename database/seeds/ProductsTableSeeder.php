<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('products')->insert([
            ['id'=>1, 'sku'=>'TESTSKU01', 'label'=>'Bed A', 'description'=>'Testing bed not real', 'price'=>100, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>2, 'sku'=>'TESTSKU02', 'label'=>'Chair A', 'description'=>'Testing chair not real', 'price'=>120, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>3, 'sku'=>'TESTSKU03', 'label'=>'Bed B', 'description'=>'Test Sample', 'price'=>130, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>4, 'sku'=>'TESTSKU04', 'label'=>'Picture P', 'description'=>'PP not real item', 'price'=>140, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>5, 'sku'=>'TESTSKU05', 'label'=>'Chair A', 'description'=>'Test Sample', 'price'=>150, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>6, 'sku'=>'TESTSKU06', 'label'=>'Sofa A', 'description'=>'Test Sample', 'price'=>160, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>7, 'sku'=>'TESTSKU07', 'label'=>'Bed O', 'description'=>'Test Sample', 'price'=>100.23, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>8, 'sku'=>'TESTSKU08', 'label'=>'Picture O', 'description'=>'Test Sample', 'price'=>100.12, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>9, 'sku'=>'TESTSKU09', 'label'=>'Sofa K', 'description'=>'Test Sample', 'price'=>102, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>10, 'sku'=>'TESTSKU010', 'label'=>'Bed D', 'description'=>'Test Sample', 'price'=>102, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>11, 'sku'=>'TESTSKU011', 'label'=>'Picture D', 'description'=>'Test Sample', 'price'=>102, 'created_at'=>date('Y-m-d h:i:s')],
        ]);
    }
}
