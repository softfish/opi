<?php

namespace App\Services;

class OrderService extends BaseModelService
{
    /**
     * Controller
     */
	public function __construct() {
	    parent::$baseTable = 'orders';
	}

	/**
	 *
	 * Creat an order with the business logic here.
	 * 
	 * @param array $data
	 * @return \App\Models\Order
	 *
	 */
	public function create(array $data): \App\Models\Order
	{
		// Validate the data before injecting it to the database
		// This assuming the data which passed down here has going through the HTML input validation in controller

		// TODO
		// create order, create product (optional), create item (optional)

		$order = \App\Models\Order::create($data);
		return $order;
	}

	/**
	 *
	 * Find the order with the associated id and return it to the caller
	 *
	 * @param int $orderId
	 * @return \App\Models\Order
	 *
	 */
	public function view(int $orderId): \App\Models\Order
	{
		return \App\Models\Order::where('id', $orderId)->first();
	}

	/**
	 *
	 * Update the order object with given data
	 *
	 * @param array $data
	 * @return \App\Models\Order
	 *
	 */
	public function update(array $data): \App\Models\Order
	{
		$order = \App\Models\Order::find($data['id']);
		if (!empty($order)){
			// Now we can update the fields in the order
			$order->customer_name = $data['customerName']?? $order->customer_name;
			$order->address = $data['address']?? $order->address;
			$order->updated_by = $data['updatedBy']?? 'user';
			$order->updated_at = time();
			$order->save();			

		} else {
			\Log::error(__METHOD__.': order ('.$data['id'].') not found in the system.');
		}

		return $order;
	}
}