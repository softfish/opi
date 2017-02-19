<?php
return [
    'initial_status' => 'To order',
    'progress_info' => [
        'to_order' => [
            'To order' =>[
                'bar_status' => 'active',
                'bar_info' => 'Order has been placed into the system and ready for processing.'
            ],
            'In warehouse' => [
                'bar_status' => 'disabled',
                'bar_info' => 'The item is in the warehouse now and has been scheduling for delivery.'
            ],
            'Delivered' => [
                'bar_status' => 'disabled',
                'bar_info' => 'Item has been delivered to the customer'
            ]
        ],
        'in_warehouse' => [
            'To order' =>[
                'bar_status' => 'complete',
                'bar_info' => 'Item is ready for packaging.'
            ],
            'In warehouse' => [
                'bar_status' => 'active',
                'bar_info' => 'The item is currently in the warehouse now and ready for delivery.'
            ],
            'Delivered' => [
                'bar_status' => 'disabled',
                'bar_info' => 'Item has been delivered to the customer'
            ]
        ],
        'delivered' => [
            'To order' =>[
                'bar_status' => 'complete',
                'bar_info' => 'Item has been packed.'
            ],
            'In warehouse' => [
                'bar_status' => 'complete',
                'bar_info' => 'Item has been shipped.'
            ],
            'Delivered' => [
                'bar_status' => 'active',
                'bar_info' => 'Item has been delivered to the customer.'
            ]
        ]
    ]
];