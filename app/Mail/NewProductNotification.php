<?php

namespace App\Mail;

use \Illuminate\Mail\Mailable;
use \App\Models\Order;
use App\Models\Product;

class NewProductNotification extends Mailable
{
    /**
     * The product instance.
     *
     * @var Product
     */
    protected $product;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\App\Models\Product $product)
    {
        $this->product = $product;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Product Notification')
                    ->view('email.admin.newproductnotification')
                    ->with([
                    	'product' => $this->product
                    ]);
    }
}