<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function submitNewOrder(Request $request): \Illuminate\Http\JsonResponse
    {
        $postdata = $request->json()->all();
        
        // the post data structure must started from keyname 'order'
        if (isset($postdata['order'])) {
            // Check post data and validation
            $v = \Validator::make($postdata['order'], [
                'customer_name' => 'required|max:70',
                'address' => 'required|max:255',
                'total' => 'required|numeric',
                'items.*.sku' => 'required',
                'items.*.quantity' => 'required',
            ]);
            
            if ($v->fails()){
               return \Response::json([
                   'success' => false,
                   'error' => $v->errors()
               ]);
            }
            // 1. create an order
            $service = new \App\Services\OrderService();
            $order = $service->create($postdata['order']);

            if (! empty($order)) {
                \App\Services\ItemService::bulkCreate($order, $postdata['order']['items']);
                return \Response::json([
                    'success' => true,
                    'message' => 'A new order ['.$order->id.'] has been submitted.'
                ]);
            } else {
                return \Response::json([
                    'success' => false,
                    'error' => 'fail to create a new order'
                ]);
            }
        } else {
            return \Response::json([
                'success' => false,
                'error' => 'missing order data'
            ]);
        }
    }
    
    public function apiList(): \Illuminate\Http\JsonResponse
    {
        $orders = \App\Models\Order::get();
        
        return \Response::json([
            'success' => true,
            'orders' => $orders
        ]);
    }
    
    public function webList()
    {
        $orders = \App\Models\Order::orderBy('id', 'DESC')->get();
        return view('admin.order.listv2', ['orders' => $orders]);
    }
    
    public function getFullOrderData($id)
    {
        
    }
    
    public function viewOrder($id)
    {
        $order = \App\Models\Order::find($id);
        $orderItems = \App\Services\ItemService::getOrderItemsData($id);
        return view('admin.order.view-jq', ['order' => $order, 'orderItems' => $orderItems]);
    }
}
