<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    
    protected $cast = [
        'id' => 'int'
    ];
    
    protected $fillable = ['sku', 'label' ,'description'];
}
