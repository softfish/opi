<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('items')->insert([
            ['id'=>1, 'order_id'=>1, 'product_id'=>1, 'status' => 'Assigned', 'physical_status_id'=>2, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>2, 'order_id'=>1, 'product_id'=>2, 'status' => 'Assigned', 'physical_status_id'=>2, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>3, 'order_id'=>2, 'product_id'=>1, 'status' => 'Assigned', 'physical_status_id'=>1, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>4, 'order_id'=>1, 'product_id'=>3, 'status' => 'Assigned', 'physical_status_id'=>1, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>5, 'order_id'=>null, 'product_id'=>1, 'status' => 'Available', 'physical_status_id'=>1, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>6, 'order_id'=>1, 'product_id'=>1, 'status' => 'Assigned', 'physical_status_id'=>1, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>7, 'order_id'=>2, 'product_id'=>4, 'status' => 'Assigned', 'physical_status_id'=>1, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>8, 'order_id'=>1, 'product_id'=>4, 'status' => 'Assigned', 'physical_status_id'=>1, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>9, 'order_id'=>2, 'product_id'=>1, 'status' => 'Assigned', 'physical_status_id'=>1, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>10, 'order_id'=>1, 'product_id'=>5, 'status' => 'Assigned', 'physical_status_id'=>1, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>11, 'order_id'=>3, 'product_id'=>1, 'status' => 'Assigned', 'physical_status_id'=>1, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>12, 'order_id'=>3, 'product_id'=>1, 'status' => 'Assigned', 'physical_status_id'=>2, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>13, 'order_id'=>null, 'product_id'=>8, 'status' => 'Available', 'physical_status_id'=>1, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>14, 'order_id'=>null, 'product_id'=>8, 'status' => 'Available', 'physical_status_id'=>1, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>15, 'order_id'=>null, 'product_id'=>8, 'status' => 'Available', 'physical_status_id'=>1, 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>16, 'order_id'=>null, 'product_id'=>2, 'status' => 'Available', 'physical_status_id'=>1, 'created_at'=>date('Y-m-d h:i:s')],
        ]);
    }
}
