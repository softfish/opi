<?php

namespace \App\Listeners;

use \App\Events\OrderStatusEvaluation;

class UpdateOrderStatus
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
     * @param  OrderStatusEvaluation  $event
     * @return void
     */
    public function handle(OrderStatusEvaluation $event)
    {
        $order = $event->order;

        $items = \App\Models\Item::where('order_id', $order->id)->get();

        if (empty($items)){
            // If there is no more item assign to this order then the order has been cancelled.
            $order->status = 'Cancelled';
        } else {

            $itemDeliveredCounter = 0;
            foreach ($items as $item){
                if ($item->physical_status != 'Delivered'){
                    // Jump out from this foreach loop. There are still items not delivered so 
                    // we don't need to counter anymore. And this will ensure the counter will
                    // never be the same as the total number of items in the order.
                    break;
                }
                $itemDeliveredCounter ++;
            }

            if (counter($items) === $itemDeliveredCounter){
                // if item delivered count is same as the total items in this order then
                // we will mark this order to be completed.
                $order->status = 'Completed';
            }

        }

        $order->save();
    }
}