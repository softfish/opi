<?php

use Illuminate\Database\Seeder;

class ItemPhysicalStatusLookupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('item_physical_status_lookup')->insert([
            ['id' => 1, 'name' => 'To order', 'description' => 'Item has been order by a customer and waiting for processing.'],
            ['id' => 2, 'name' => 'In warehouse', 'description' => 'Item is currently being hold in the warehouse and await for delivery'],
            ['id' => 3, 'name' => 'Delivered', 'description' => 'The item has reached it destination.']
        ]);
    }
}
