<?php
namespace \App\Services;

class ProductService extends BaseModelService
{
    /**
     * Controller
     */
	public function __construct() {
	    parent::$baseTable = 'products';
	}
    

	/**
	 *
	 * Creat an Product with the business logic here.
	 * 
	 * @param array $data
	 * @return \App\Models\Product
	 *
	 */
	public function create(array $data): \App\Models\Product
	{
		if (isset($data['sku'])){
			// We must has the sku for the product.
			$product = \App\Models\Product::create($data);
		}

		// Fire event on product is created so the admin got an email notification
		event(new App\Events\ProductCreated($product));
		
		return $product;
	}

	/**
	 *
	 * Find the Product with the associated id and return it to the caller
	 *
	 * @param int $productId
	 * @return \App\Models\Product
	 *
	 */
	public function view(int $productId): \App\Models\Product
	{
		return \App\Models\Product::where('id', $productId)->first();
	}

	/**
	 *
	 * Update the Product object with given data
	 *
	 * @param array $data
	 * @return \App\Models\Product
	 *
	 */
	public function update(array $data): \App\Models\Product
	{
		$product = \App\Models\Product::where('id', $data['id'])->first();

		if (empty($product)){
			\Log::warning(__METHOD__.' product not found.')
			return false;
		}

		$product->sku = $data['sku']?? $product->sku;
		$product->product_id = $data['product_id']?? $product->product_id;
		$product->label = $data['label']?? $product->label;
		$product->description = $data['description']?? $product->description;
		$product->updated_by = $data['updated_by']?? 'system';
		$product->updated_at = time();
		$product->save();

		return true;
	}

}