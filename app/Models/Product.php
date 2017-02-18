<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    
    protected $cast = [
        'id' => 'int'
    ];
    
    protected $fillable = ['sku', 'label' ,'description', 'price'];
    
    public function properties()
    {
        return $this->hasMany(ProductProperty::class);
    }
    
    public function items()
    {
        return $this->hasMany(Item::class);
    }
    
    /**
	 * 
	 * Product service to load the sale report for API or Web request.
	 * 
	 */
	public function getSaleReport()
	{
	    $rows = \DB::table('items')
	    ->where('items.product_id', $this->id)
	    ->select(\DB::raw(implode(',',[
	        'items.status',
	        'COUNT(*) as numberOfItems'
	    ])))
	    ->groupBy('items.status')
	    ->get();
	    
	    $saleReport = [];
	    foreach ($rows as $row){
	        $saleReport[$row->status] = $row->numberOfItems;
	    }
	    
	    return $saleReport;
	}
}
