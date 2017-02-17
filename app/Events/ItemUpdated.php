<?php

namespace \App\Events;

use \App\Models\Item;

class ItemCreated
{
    use SerializesModels;

    public $item;

    /**
     * Create a new event instance.
     *
     * @param  Item $item
     * @return void
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }

}