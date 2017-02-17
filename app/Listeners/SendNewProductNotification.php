<?php

namespace \App\Listeners;

use \App\Events\ProudctCreated;
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
    public function handle(ProudctCreated $event)
    {
        $product = $event->product;

        // TODO
        // Sending out notification email to the admin.
        $to = env('ADMIN_EMAIL');
        $subject = 'New Product Registration';
        // \Mail::to($to)->send(new newProductNotification());

    }
}