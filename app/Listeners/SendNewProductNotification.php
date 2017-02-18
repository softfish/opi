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

        // Sending out notification email to the admin.
        $to = env('ADMIN_EMAIL');
        $subject = 'New Product Registration';
        \Mail::to($to)->send(new newProductNotification($product));

        \Log::info('An notification email has been sent to admin in regard of product('.$product->id.') registration in the system.');

    }
}