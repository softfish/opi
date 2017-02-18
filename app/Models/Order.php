<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    protected $cast = [
    	'customer_name' => 'string',
    	'address'		=> 'string',
    	'total'			=> 'float',
    	'status'		=> 'string'
    ];
    
    protected $dates = ['order_date'];

    protected $fillable = ['customer_name', 'address' ,'total'];
    
    public function items() {
    	return $this->hasMany(Item::class);
    }
}
 