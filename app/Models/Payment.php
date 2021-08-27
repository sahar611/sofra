<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model 
{

    protected $table = 'payments';
    public $timestamps = true;
    protected $fillable = array('amount', 'restaurant_id', 'notes', 'payment_date');

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

}