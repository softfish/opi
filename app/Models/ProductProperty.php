<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductProperty extends Model
{
    protected $table = 'product_properties';
    
    protected $cast = [
        'id' => 'int',
        'product_id' => 'int',
        'type' => 'string',
        'name' => 'string',
        'value' => 'string'
    ];
    
    protected $fillable = ['product_id', 'type', 'name' ,'value'];
}
