<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function apiList(): \Illuminate\Http\JsonResponse
    {
        $items = \App\Models\Item::get();
        
        return \Response::json([
            'success' => true,
            'items' => $items
        ]);
    }
}
