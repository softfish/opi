<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderControllerTest extends TestCase
{
    public function testNewOrderSubmissionWithNormalData()
    {
        \DB::beginTransaction();
        $orderRequest = [
                    'order' => [
                        'customer' => 'Gabriel Jaramillo',
                        'address' => 'test address',
                        'total' => 100,
                        'items' => [
                            [
                                'sku' => 'TESTSKU1',
                                'quanitiy' => 2,
                            ],
                            [
                                'sku' => 'TESTSKU2',
                                'quanitiy' => 1
                            ]
                        ]
                    ]
                ];
        $response = $this->json('POST', '/api/order/new', $orderRequest);
        $response->assertJson([
                    'success' => true,
                   ]);
        \DB::rollBack();
    }
}
