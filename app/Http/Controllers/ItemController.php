<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * get the list of item through the API
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiList(): \Illuminate\Http\JsonResponse
    {
        $items = \App\Models\Item::get();
        
        return \Response::json([
            'success' => true,
            'items' => $items
        ]);
    }
    
    public function apiUpdate(Request $request): \Illuminate\Http\JsonResponse
    {
        $postdata = $request->json()->all();
        if (empty($postdata)){
            // Sometime the request might not be json data.
            // So just to be safe we load it again if we can't find any postdata from json.
            $postdata = $request->all();
        }
 
        if (!empty($postdata)){
            $service = new \App\Services\ItemService();
            $result = $service->update($postdata);
            if ($result) {
                return \Response::json([
                    'success' => true,
                    'message' => 'Item update successfully.'
                ]);
            } else {
                return \Response::json([
                    'success' => false,
                    'error' => 'item ('.$postdata['id'].') not found.'
                ]);
            }
        } else {
            return \Response::json([
                'success' => false,
                'error' => 'item data not found.'
            ]);
        }
    }
    
    /**
     * 
     * Remove single item from order
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiRemoveItemFromOrder(int $id): \Illuminate\Http\JsonResponse
    {
        $result = \App\Services\ItemService::unlinkOrder($id);
        
        if ($result) {
            return \Response::json([
                'success' => true,
                'message' => 'item has been removed from order.'
            ]);
        } else {
            return \Response::json([
                'success' => false,
                'error' => 'Unable to remove item from order.'
            ]);
        }
    }
    
    /**
     * 
     * Return a single item view
     * 
     * @param unknown $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function viewItem($id)
    {
        $service = new \App\Services\ItemService();
        $items = $service->itemList([
            [ 'field_name' => 'items.id', 'operator' => '=', 'value' => $id]
        ]);
        
        $availablePhyscialStatus = \DB::table('item_physical_status_lookup')->get();
        
        // But in here we only need to first one (in fact it should be just the one row.
        // Since we can just reuse the itemList function in ItemService with a filter,
        // we just need to make sure we only pass the first record to item.
        // Also we can use the information from the joined tables.
        if (!empty($items) && count($items) > 0){
            $item = $items[0];
            return view('admin.item.view-jq', ['item' => $item, 'availablePhyscialStatus' => $availablePhyscialStatus]);
        }
        
        
    }
    
    public function webList()
    {
        $service = new \App\Services\ItemService();
        $items = $service->itemList();
        
        return view('admin.item.list', ['items' => $items]);
    }
}
