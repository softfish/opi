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
    
    public function removeProperty($id): \Illuminate\Http\JsonResponse
    {
        $property = \App\Models\ProductProperty::find($id);
        if (!empty($property)){
            if ($property->delete()){
                \Log::info(__METHOD__.' Product property('.$id.') has been removed.');
                return \Response::json([
                    'success' => true,
                    'message' => 'Product property has been removed successfully'
                ]);
            } else {
                \Log::error(__METHOD__.' Product property('.$id.')cannot be removed.');
                return \Response::json([
                    'success' => false,
                    'error' => 'Product property cannot be removed.'
                ]);
            }
        } else {
            \Log::error(__METHOD__.' Product property('.$id.') not found.');
            return \Response::json([
                'success' => false,
                'error' => 'no property found.'
            ]);
        }
    }
    
    
    /**
     * 
     * Add a new property to a product
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNewProperty(Request $request): \Illuminate\Http\JsonResponse
    {
        $postdata = $request->json()->all();
        if (empty($postdata)){
           $postdata = $request->all(); 
        }
        $v = \Validator($postdata,[
            'name' => 'required|max:25',
            'value' => 'required|max:50',
            'type' => 'required'
        ]);

        if ($v->fails()){
            return \Response::json([
                'success' => false,
                'error' => $v->errors()
            ]);
        }
        $productProperty = \App\Models\ProductProperty::create($postdata);
        if (!empty($productProperty)){
            return \Response::json([
                'success' => true,
                'data' => $productProperty
            ]);
        } else {
            return \Response::json([
                'success' => false,
                'error' => 'Failed to craete new product property.'
            ]);
        }
    }
    
    public function viewProduct(int $id)
    {
        $product = \App\Models\Product::find($id);
        return view('admin.product.view-jq', ['product' => $product]);
    }
    
    public function updateProduct(Request $request)
    {
        $postdata = $request->all();
        $flashMessages = null;
        $service = new \App\Services\ProductService();
        if (isset($postdata['id']) && $postdata['id'] != '' ){
            // This is done on website so set the updated_by user
            $postdata['updated_by'] = 'user';
            if ($service->update($postdata)) {
                $flashMessages['success'][] = 'Product has been updated.';
            } else {
                $flashMessages['danger'][] = 'Product update failed.';
            }

            $product = $service->view($postdata['id']);
            return view('admin.product.view-jq', ['product' => $product, 'flashMessages' => $flashMessages]);
        } else {
            $products = $service->list();
            $flashMessages['warning'][] = 'Product data missing during product update.';
            return view('admin.product.list', ['products' => $products, 'flashMessages' => $flashMessages]);
        }
    }
    
    public function webList()
    {
        $service = new \App\Services\ProductService();
        $products = $service->list();
        return view('admin.product.list', ['products' => $products]);
    }
    
    public function addProductItems(Request $request)
    {
        $postdata = $request->all();
        $flashMessages = null;
        if (isset($postdata['id']) || isset($postdata['sku'])) {
            $postdata['product_id'] = ($postdata['id'])?? null;
            $service = new \App\Services\ItemService();
            
            // use the items holder so we know how many has been created.
            $items = [];
            for ($i=0; $i < $postdata['quantity']; $i++) {
                $items[] = $service->create($postdata);
            }
            
            if (count($items) < 1){
                $flashMessages['danger'][] = 'Create item failed';
            } else {
                $flashMessages['success'][] = count($items).' x Item(s) has been created for this product.';
            }
            // load the product with its ID. If ID not found use the SKU.
            // Because if you are here, then you must have either ID or SKU.
            $product = \App\Models\Product::find(($postdata['id'])?? $postdata['sku']);
            return view('admin.product.view-jq', ['product' => $product, 'flashMessages' => $flashMessages]);
        } else {
            $flashMessages['danger'][] = 'Failed create item for this product. Product identifier not found.';
            $service = new \App\Services\ProductService();
            $products = $service->list();
            return view('admin.product.list', ['products' => $products, 'flashMessages' => $flashMessages]);
        }
    }
    
    public function addNewProduct(Request $request)
    {
        $postdata = $request->all();
        $flashMessages = null;
        if (isset($postdata['sku']) && !empty($postdata['sku'])) {
            $service = new \App\Services\ProductService();
            $product = $service->create($postdata);
            if (!isset($product->existing) || $product->existing != true) {
                $flashMessages['success'][] = 'A new product has been created ['.$product->sku.'].';
                $flashMessages['success'][] = 'Since it is a new product an notification email will send to the system admin.';
            } else {
                $flashMessages['warning'][] = 'This SKU has been used. Please check and try again.';
            }
        }else{
            $flashMessages['danger'][] = 'Failed create item for this product. Product identifier not found.';
        }
        $service = new \App\Services\ProductService();
        $products = $service->list();
        return view('admin.product.list', ['products' => $products, 'flashMessages' => $flashMessages]);
    }
}
