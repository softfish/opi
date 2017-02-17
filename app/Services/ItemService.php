<?php

namespace App\Services;

class ItemService extends BaseModelService 
{
    /**
     * Controller
     */
	public function __construct() {
	    parent::$baseTable = 'items';
	}
    

	/**
	 *
	 * Creat an item with the business logic here.
	 * 
	 * @param array $data
	 * @return \App\Models\Item
	 *
	 */
	public function create(array $data): \App\Models\Item
	{
		// We need to make sure the product exist first before creating the item
		if (isset($data['product_id'])){
			
			$product = \App\Models\Product::where('id', $data['product_id'])->first();


		} else if (isset($data['sku'])){
			
			$product = \App\Models\Product::where('sku', $data['sku'])->first();

			if (empty($product)){
				// If we can't find the product then we will need to generate a new one
				$productData = [
					'sku' => $productSku,
					'created_by' => 'system'
				];
				$product = \App\Services\Product::create($productData);
			}

		} else {

			throw Exception(__METHOD__.'missing product identifier');

		}
		
		$data['product_id'] = $product->id;
		$item = \App\Models\Item::create($data);
		return $item;
	}

	/**
	 *
	 * Find the item with the associated id and return it to the caller
	 *
	 * @param int $itemId
	 * @return \App\Models\Item
	 *
	 */
	public function view(int $itemId): \App\Models\Item
	{
		return \App\Models\Item::where('id', $itemId)->first();
	}

	/**
	 *
	 * Update the item object with given data
	 *
	 * @param array $data
	 * @return \App\Models\Item
	 *
	 */
	public function update(array $data): \App\Models\Item
	{
		$item = \App\Models\Item::where('id', $data['id'])->first();

		if (empty($item)){
			\Log::warning(__METHOD__.' item not found.');
			return false;
		}

		$item->order_id = $data['order_id']?? $item->order_id;
		$item->product_id = $data['product_id']?? $item->product_id;
		$item->status = $data['status']?? $item->status;
		$item->physical_status = $data['physical_status']?? $item->physical_status;
		$item->updated_by = $data['updated_by']?? 'system';
		$item->updated_at = time();
		$item->save();
		
		// Fire event on item updated
		event(new App\Events\ItemUpdated($item));

		return true;
	}
	
	/**
	 *
	 * This function will take in a list of items data and create them according with its own products
	 *
	 * @param \App\Models\Order $order
	 * @param array $itemData
	 * @return void
	 *
	 */
	public static function bulkCreate(\App\Models\Order $order, array $itemsData): void
	{
	    foreach ($itemsData as $item) {
	        // find the product using sku
	        $product = \App\Models\Product::where('sku', $item['sku'])->first();
	        if (empty($product)) {
	            // 3.1 create a new product with the new sku and send admin a notification email
	            $product = \App\Models\Product::create(['sku' => $item['sku']]);
	            \Log::info('A new product('.$product->id.') has been created.');
	    
	            // Fire event on product is created so the admin got an email notification
	            event(new App\Events\ProductCreated($product));
	            
	            \Log::info('An notification email has been sent to admin in regard of product('.$product->id.') registration in the system.');
	        }
	    
	        // Get x number available items by product ID, where x is the quantity requested
	        $availItems = \App\Models\Item::where('product_id', $product->id)->where('order_id', null)
	        ->where('status', 'Available')
	        ->limit($item['quantity'])
	        ->get();
	        if (! empty($availItems)) {
	    
	            switch ($item['quantity'] <=> count($availItems)){
	                case -1: // we have enought for the order
	                case 0: // we just have enought for the order
	                    foreach ($availItems as $availItem) {
	                        // If we do find the item, then assign it to this order
	                        $availItem->order_id = $order->id;
	                        $availItem->status = 'Assigned';
	                        $availItem->physical_status_id = \App\Models\ItemPhysicalStatusLookup::getInitialStatus();
	                        $availItem->updated_by = 'api';
	                        $availItem->updated_at = time();
	                        $availItem->save();
	    
	                        \Log::info('Item ('.$availItem->id.') has been assigned to order ('.$order->id.')');
	                    }
	                    break;
	                case 1:
	                    // we don't have enought item which mean we need to create additional one.
	                    // (assuming... the item is to order but not created yet)
	                    foreach ($availItems as $availItem) {
	                        // If we do find the item, then assign it to this order
	                        $availItem->order_id = $order->id;
	                        $availItem->status = 'Assigned';
	                        $availItem->physical_status_id = \App\Models\ItemPhysicalStatusLookup::getInitialStatus();
	                        $availItem->updated_by = 'api';
	                        $availItem->updated_at = time();
	                        $availItem->save();
	    
	                        \Log::info('Item ('.$availItem->id.') has been assigned to order ('.$order->id.')');
	                    }
	                    // additional item create here.
	                    for ($i=0; $i < ($item['quantity'] - count($availItems)); $i++) {
	                        $service = new \App\Services\ItemService();
	                        $newItem = $service->create([
	                            'product_id'            => $product->id,
	                            'order_id'              => $order->id,
	                            'status'                => 'Assigned',
	                            'physical_status_id'    => \App\Models\ItemPhysicalStatusLookup::getInitialStatus()
	                        ]);
	                        \Log::info('A new item('.$newItem->id.') has been created for order('.$order->id.')');
	                    }
	                    break;
	            }
	        } else {
	            // if we can't find the item, then we will need to create x number of required items for the order.
	            for ($i=0; $i < $item['quantity']; $i++) {
	                $service = new \App\Services\ItemService();
	                $newItem = $service->create([
	                    'product_id'            => $product->id,
	                    'order_id'              => $order->id,
	                    'status'                => 'Assigned',
	                    'physical_status_id'    => \App\Models\ItemPhysicalStatusLookup::getInitialStatus()
	                ]);
	                \Log::info('A new item('.$newItem->id.') has been created for order('.$order->id.')');
	            }
	        }
	    }
	}
	
    /**
     * 
     * Get the item profile data
     * 
     * @param unknown $orderId
     * @return \stdClass
     */
	public static function getOrderItemsData($orderId): \Illuminate\Support\Collection
	{
	    $orderItems = \DB::table('items')
	    ->join('products', 'products.id', '=', 'items.product_id')
	    ->join('item_physical_status_lookup', 'items.physical_status_id', '=', 'item_physical_status_lookup.id')
	    ->where('items.order_id', '=', $orderId)
	    ->select(\DB::raw(implode(',',[
    	        '*',
	            'items.id as item_id',
                'item_physical_status_lookup.name as item_physical_status',
    	        'items.updated_by as item_updated_by',
    	        'items.updated_at as item_updated_at',
    	        'items.created_by as item_created_by',
    	        'items.created_at as item_created_at',
    	        'products.updated_by as product_updated_by',
    	        'products.updated_at as product_updated_at',
    	        'products.created_by as product_created_by',
    	        'products.created_at as product_created_at',
    	    ])
	    ))
	    ->get();
	    return $orderItems;
	}
}