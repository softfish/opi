<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    protected $table = 'items';

    protected $fillable = [
        'order_id',
        'product_id',
        'status',
        'physical_status_id'
    ];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class);
    }
}
