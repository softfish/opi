<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * 
     * API request to create a new product entry
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiCreate(Request $request): \Illuminate\Http\JsonResponse
    {
        $postdata = $request->json()->all();
        
        // Check post data and validation
        $v = \Validator::make($postdata, [
            'sku' => 'required|max:25',
            'label' => 'max:100',
        ]);
        
        if ($v->fails()){
            return \Response::json([
                'success' => false,
                'error' => $v->errors()
            ]);
        }
        
        // create product + data sanitizing
        $product = new \App\Models\Product();
        $product->sku = strip_tags($postdata['sku']);
        $product->label = strip_tags($postdata['label']);
        $product->description = htmlspecialchars($postdata['label']);
        $product->updated_by = $postdata['updated_by']?? 'system';
        $product->save();
        
        \Log::info('new product ('.$product->id.') has been created through API request');
        return \Response::json([
            'success' => true,
            'message' => 'new product has been created'
        ]);
    }
    
    /**
     * 
     * Get all existing products in the database
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiList(): \Illuminate\Http\JsonResponse
    {
        $products = \App\Models\Product::get();
        
        return \Response::json([
            'success' => true,
            'products' => $products
        ]);
    }
}
