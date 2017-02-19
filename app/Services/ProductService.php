<?php
namespace App\Services;

class ProductService extends BaseModelService
{

    /**
     * Controller
     */
    public function __construct()
    {
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
        if (isset($data['sku'])) {
            // We must has the sku for the product.
            // Lets first check is the sku has already been used.
            $product = \App\Models\Product::where('sku', $data['sku'])->first();
            if (empty($product)) {
                // we need to set a default price first, zero!
                $data['price'] = 0;
                $product = \App\Models\Product::create($data);
                \Log::info('A new product(' . $product->id . ') has been created.');
                
                // Only do this when we create a new product
                // Fire event on product is created so the admin got an email notification
                event(new \App\Events\ProductCreated($product));
            } else {
                // If it is already been use the at a custom attribute to tell the
                // caller the product we return is an existing record.
                $product->existing = true;
            }
        }
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
        $product = \App\Models\Product::find($productId);
        return $product;
    }

    /**
     *
     * Update the Product object with given data
     *
     * @param array $data            
     * @return \App\Models\Product
     *
     */
    public function update(array $data): bool
    {
        $product = \App\Models\Product::find($data['id']);
        if (empty($product)) {
            \Log::warning(__METHOD__ . ' product not found.');
            return false;
        }
        
        $product->sku = $data['sku'] ?? $product->sku;
        $product->label = $data['label'] ?? $product->label;
        $product->description = $data['description'] ?? $product->description;
        $product->price = $data['price'] ?? $product->price;
        $product->updated_by = $data['updated_by'] ?? 'system';
        $product->updated_at = time();
        $product->save();
        
        return true;
    }
}