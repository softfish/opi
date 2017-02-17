<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPhysicalStatusLookup extends Model
{
    protected $table = 'item_physical_status_lookup';
    
    public static function getInitialStatus() {
        $config = \Config::get('item');
        $status = self::where('name', $config['initial_status'])->first();

        return $status->id;
    }
}
