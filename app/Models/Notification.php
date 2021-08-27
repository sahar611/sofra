<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model 
{

    protected $table = 'notifications';
    public $timestamps = true;
    protected $fillable = array('title', 'body', 'notifiable_id', 'order_id', 'notifiable_type');

    public function clients()
    {
        return $this->morphTo();
    }

}