<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('orders')->insert([
            ['id'=>1, 'customer_name'=>1, 'address'=>'Test Address 1', 'total'=>522.30, 'status' => 'In progress', 'order_date'=>date('Y-m-d h:i:s', strtotime('-1 Day')), 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>2, 'customer_name'=>1, 'address'=>'Test Address 1', 'total'=>300, 'status' => 'In progress', 'order_date'=>date('Y-m-d h:i:s', strtotime('-3 Day')), 'created_at'=>date('Y-m-d h:i:s')],
            ['id'=>3, 'customer_name'=>2, 'address'=>'Test Address 1', 'total'=>232.6, 'status' => 'In progress', 'order_date'=>date('Y-m-d h:i:s', strtotime('-5 Day')), 'created_at'=>date('Y-m-d h:i:s')],
        ]);
    }
}
