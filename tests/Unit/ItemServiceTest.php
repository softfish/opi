<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\Services\ItemService;

class ItemServiceTest extends TestCase
{
    public function testCreateItemWithProductID()
    {
        \DB::beginTransaction();
        $data = [
            'product_id' => 1
        ];
        
        $service = new ItemService();
        $item = $service->create($data);
        
        $this->assertInstanceOf('\App\Models\Item', $item, 'Return object is not an item model.');
        $this->assertTrue(isset($item->id), 'This is an empty object.');
        
        \DB::rollBack();
    }
    
    public function testCreateItemWithExistingProductSKU()
    {
        \DB::beginTransaction();
        $data = [
            'sku' => 'TESTSKU01'
        ];
    
        $service = new ItemService();
        $item = $service->create($data);
    
        $this->assertInstanceOf('\App\Models\Item', $item, 'Return object is not an item model.');
        $this->assertTrue(isset($item->id), 'This is an empty object.');
    
        \DB::rollBack();
    }
    
    public function testCreateItemWithNewProductSKU()
    {
        \DB::beginTransaction();
        $data = [
            'sku' => 'TESTSKU01'
        ];
    
        $service = new ItemService();
        $item = $service->create($data);
    
        $this->assertInstanceOf('\App\Models\Item', $item);
        $this->assertTrue(isset($item->id));
    
        \DB::rollBack();
    }
    
    public function testUpdateItemServiceWithID()
    {
        \DB::beginTransaction();
        $data = [
            'id' => 1,
            'status' => 'Assigned'
        ];
        
        $service = new ItemService();
        $item = $service->update($data);
        
        $this->assertInstanceOf('\App\Models\Item', $item);
        $this->assertTrue(isset($item->id));
        \DB::rollBack();
    }
    
    public function testUpdateItemServiceWithoutID()
    {
        \DB::beginTransaction();
        $data = [
            'id' => null,
            'status' => 'Assigned'
        ];
    
        $service = new ItemService();
        $item = $service->update($data);
        
        $this->assertTrue(empty($item), 'It should be null value.');
        \DB::rollBack();
    }
    
    public function testBulkItemCreationWithoutAnOrder()
    {
        $data['items'] = [
            ['sku' => 'TESTSKU01', 'quantity' => 2],
            ['sku' => 'TESTSKU02', 'quantity' => 1]
        ];
        
        $data['order_id'] = null;
        print "\n".__METHOD__."-------------------------------\n";
        $this->bulkItemCreationTest($data);
    }
    
//     public function testBulkItemCreationWithAnOrder()
//     {
//         $data['items'] = [
//             ['sku' => 'TESTSKU01', 'quantity' => 2],
//             ['sku' => 'TESTSKU02', 'quantity' => 1]
//         ];
    
//         $data['order_id'] = 1;
//         print "\n".__METHOD__."-------------------------------\n";
//         $this->bulkItemCreationTest($data);
//     }
    
    private function bulkItemCreationTest($data)
    {
        \DB::beginTransaction();
        $startTime = time();
        $order = null;
        if ($data['order_id']){
            $order = \App\Models\Order::find($data['order_id']);
        }
        
        $service = new ItemService();
        ItemService::bulkCreate($order, $data['items']);
        
        // If the order is define make sure the function create the same
        // number of items requested by the order
        $totalNumberOfSKUItemInDB = [];
        $totalExpectedNumberOfItemWithOrder = 0;
        // Prepare the data we need to test for product's item creation
        foreach ($data['items'] as $itemData){
            $totalExpectedNumberOfItemWithOrder += ($itemData['quantity'])?? 0;
            $totalNumberOfSKUItemInDB[$itemData['sku']]['additional_quantity'] = ($itemData['quantity'])?? 0;
            $product = \App\Models\Product::where('sku', $itemData['sku'])->first();
            $totalNumberOfSKUItemInDB[$itemData['sku']]['existing_quantity'] = count($product->items()->get());
        }
        
        if (!empty($order)){
            // Now search the database see we can find all of them.
            $items = \App\Models\Item::where('order_id', $order->id)->get();
            $this->assertTrue(($totalExpectedNumberOfItemWithOrder === count($items)));
            
        } else {
var_dump($totalNumberOfSKUItemInDB);
            foreach ($totalNumberOfSKUItemInDB as $sku => $quantity) {
                $items = \App\Models\Product::where('sku', $sku)->first()->items()->get();
                $expectedTotal = $totalNumberOfSKUItemInDB[$sku]['additional_quantity'] + 
                                    $totalNumberOfSKUItemInDB[$sku]['existing_quantity'];
print "\n";
print "additional: $sku = ".$totalNumberOfSKUItemInDB[$sku]['additional_quantity']."\n";
print "existing: $sku = ".$totalNumberOfSKUItemInDB[$sku]['existing_quantity']."\n";
print count($items)." vs ".$expectedTotal."\n";
print "\n";
                $this->assertTrue((count($items) === $expectedTotal));
            }
        }
        
        \DB::rollBack();
    }
}
