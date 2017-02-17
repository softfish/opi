<?php

namespace \App\Events;

use \App\Models\Product;

class ProductCreated
{
    use SerializesModels;

    public $product;

    /**
     * Create a new event instance.
     *
     * @param  Product $order
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

}