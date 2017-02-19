<?php

namespace App\Listeners;

use \App\Events\ProductCreated;
use \App\Mail\newProductNotification;

class SendNewProductNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProudctCreated  $event
     * @return void
     */
    public function handle(ProductCreated $event)
    {
        $product = $event->product;

        // Remember we only need to sent out the email if the new product is created from an new order.
        // So product in the event should be a new product.
        // Now we need to check is this product come from an order. If it is from an order, then only send out the email here.
        $items = $product->items()->whereNotNull('order_id')->get();
        
        if (!empty($items) && count($items) > 0) {
            // Sending out notification email to the admin.
            $to = env('ADMIN_EMAIL');
            $subject = 'New Product Registration';
            \Mail::to($to)->send(new newProductNotification($product));
            
            \Log::info('An notification email has been sent to admin in regard of product('.$product->id.') registration in the system.');
        }
    }
}