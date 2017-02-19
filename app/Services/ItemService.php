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

		} else {

			throw \Exception(__METHOD__.'missing product identifier');

		}

		if (empty($product)){
			// If we can't find the product then we will need to generate a new one
			$productData = [
				'sku' => $data['sku'],
				'created_by' => 'system'
			];
			$service = new \App\Services\ProductService();
			$product = $service->create($productData);
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
		return \App\Models\Item::find($itemId);
	}

	/**
	 *
	 * Update the item object with given data
	 *
	 * @param array $data
	 * @return \App\Models\Item
	 *
	 */
	public function update(array $data)
	{
		$item = \App\Models\Item::find($data['id']);

		if (empty($item)){
			\Log::warning(__METHOD__.' item not found.');
			return null;
		}
		
		$item->order_id = $data['order_id']?? $item->order_id;
		$item->product_id = $data['product_id']?? $item->product_id;
		$item->status = $data['status']?? $item->status;
		$item->physical_status_id = $data['physical_status_id']?? $item->physical_status_id;
		$item->updated_by = $data['updated_by']?? 'system';
		$item->updated_at = time();
		$item->save();
		
		// Fire event on item updated if we have an order link to this item
		if (!empty($item->order_id)){
    		$order = \App\Models\Order::find($item->order_id);
    		event(new \App\Events\OrderStatusEvaluation($order));
		}

		return $item;
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
	public static function bulkCreate(\App\Models\Order $order=null, array $itemsData): void
	{
	    // Create an empty Order object for new item creation, if order is not given.
	    // doing this so we can make this function reusable for order or non-order operation.
	    // e.g. product's item creation requested by an user. Hence they won't have an order
	    // for that operation
	    if (empty($order)){
	        $order = new \App\Models\Order();
	    }	    
	    $isNewProductCreated = false;
	    foreach ($itemsData as $item) {
	        // Find the product using sku
	        $product = \App\Models\Product::where('sku', $item['sku'])->first();
	        if (empty($product)) {
	            // Create a new product with the new sku but don't fire the event to
	            // send admin a notification email just yet.
	            // Because we need the order assigned items below to trigger
	            // "ordered new product notification" email.
	            $product = \App\Models\Product::create(['sku' => $item['sku']]);
	            $isNewProductCreated = true;
	        }
	    
	        // Get x number available items by product ID, where x is the quantity requested
	        $availItems = \App\Models\Item::where('product_id', $product->id)->where('order_id', null)
                	        ->where('status', 'Available')
                	        ->limit($item['quantity'])
                	        ->get();
	        $numOfItemsAvailable = count($availItems);
	        if (! empty($availItems) && $numOfItemsAvailable > 0) {
	    
	            switch ($item['quantity'] <=> $numOfItemsAvailable){
	                case -1: // we have enought for the order
	                case 0: // we just have enought for the order
	                    foreach ($availItems as $availItem) {
	                        // If we do find the item, then assign it to this order
	                        
	                        // Make sure we only need to assign what we need and left the rest of
	                        // the available item untouched.
	                        if ($numOfItemsAvailable >= $item['quantity']) {
    	                        $availItem->order_id = ($order->id)?? null;
    	                        // If we have an order for this item then assign it else mark it as available.
    	                        $availItem->status = (isset($order->id))? 'Assigned' : 'Available';
    	                        $availItem->physical_status_id = \App\Models\ItemPhysicalStatusLookup::getInitialStatus();
    	                        $availItem->updated_by = 'api';
    	                        $availItem->updated_at = time();
    	                        $availItem->save();
    	                        
    	                        if (!empty($order->id)) {
    	                           \Log::info('Item ('.$availItem->id.') has been assigned to order ('.$order->id.')');
    	                        } else {                    
    	                           \Log::info('A new item('.$newItem->id.') has been created.');
    	                        }
    	                        $numOfItemsAvailable --;
	                        }
	                    }
	                    break;
	                case 1:
	                    // we don't have enough items which mean we need to create additional one.
	                    // (assuming... the item is to order but not created yet for the physical status.)
	                    foreach ($availItems as $availItem) {
	                        // If we do find the item, then assign it to this order
	                        $availItem->order_id = ($order->id)?? null;
	                        $availItem->status = 'Assigned';
	                        $availItem->physical_status_id = \App\Models\ItemPhysicalStatusLookup::getInitialStatus();
	                        $availItem->updated_by = 'api';
	                        $availItem->updated_at = time();
	                        $availItem->save();
	                        
	                        if (!empty($order->id)) {
	                           \Log::info('Item ('.$availItem->id.') has been assigned to order ('.$order->id.')');
	                        } else {
	                            \Log::info('A new item('.$newItem->id.') has been created.');
	                        }
	                    }
	                    // additional item create here.
	                    for ($i=0; $i < ($item['quantity'] - count($availItems)); $i++) {
	                        $service = new \App\Services\ItemService();
	                        $newItem = $service->create([
	                            'product_id'            => $product->id,
	                            'order_id'              => ($order->id)?? null,
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
	                    'order_id'              => ($order->id)?? null,
	                    'status'                => 'Assigned',
	                    'physical_status_id'    => \App\Models\ItemPhysicalStatusLookup::getInitialStatus()
	                ]);
	                
	                if (!empty($order->id)) {
	                   \Log::info('A new item('.$newItem->id.') has been created for order('.$order->id.')');
	                } else {
	                    \Log::info('A new item('.$newItem->id.') has been created.');
	                }
	            }
	        }
	        
	        // Now we should have all the items (new or old) need for this request.
	        // Check do we need to send out a new product notification to admin
	        if ($isNewProductCreated){
	            event(new \App\Events\ProductCreated($product));
	        }
	    }
	}
	
    /**
     * 
     * Get the item profile data
     * 
     * @param int $orderId
     * @return \stdClass
     */
	public static function getOrderItemsData(int $orderId): \Illuminate\Support\Collection
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
        ->orderBy('item_id')
	    ->get();
	    return $orderItems;
	}
	
	/**
	 * 
	 * update the item to be available and remove order ID and current status.
	 * 
	 * @param unknown $itemId
	 * @return bool
	 */
	public static function unlinkOrder($itemId): bool
	{
	    $result = false;
	    $item = \App\Models\Item::find($itemId);
	    if (!empty($item)) {
    	    $item->order_id = null;
    	    $item->status = 'Available';
    	    $item->physical_status_id = \App\Models\ItemPhysicalStatusLookup::getInitialStatus();
    	    $item->save();
    	    
    	    $result = true;
	    } else {
	        \Log::warning(__METHOD__.' Attempted to unlink item ('.$itemId.') but item not found.');
	        $result = false;
	    }
	    
	    return $result;
	}
	
	/**
	 *
	 * Provide a list of tables items with pagination limit
	 *
	 * @param array $filters
	 * @param int $paginationLimit
	 * @return \Illuminate\Pagination\LengthAwarePaginator
	 *
	 */
	public function itemlist(array $filters=null, int $paginationLimit=10) : \Illuminate\Pagination\LengthAwarePaginator 
	{
	    $query = \DB::table('items');
        $query->join('products', 'products.id', '=', 'items.product_id');
        $query->join('item_physical_status_lookup', 'items.physical_status_id', '=', 'item_physical_status_lookup.id');
        $query->select(\DB::raw(implode(',',[
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
	    ));
        
	    // For Item, since we want to join the product table here, the list method will be
	    // a bit different and the parent's list metho will be overwrite in here.
	    $validFilters = [];
	    if (!empty($filters)) {
    	    foreach ($filters as $filter){
    	        
    	        // We need to validate is the keyname has either "products." and "items" here.
    	        // This is for the john table format. It will ignore filter don't follow this
    	        // format.
    	        if (preg_match('/(products\.|items\.)/', $filter['field_name'])) {
    	           $validFilters[] = $filter;
    	        }
    	    }
	    }
	    if (!empty($validFilters) && count($validFilters) > 0){
	       $query = $this->applyFilters($query, $validFilters);
	    }
	    $rows = $query->paginate($paginationLimit);
	
	    return $rows;
	}
}