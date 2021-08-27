<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{

    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = array('notes', 'address', 'cost', 'delivery_cost', 'total', 'restaurant_id', 'client_id', 'delivery_time', 'status', 'commission');

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product')->withPivot('price', 'quantity', 'notes');
    }

}