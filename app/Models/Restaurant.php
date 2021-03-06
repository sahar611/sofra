<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Restaurant extends Authenticatable 
{
    use  HasApiTokens;
    protected $table = 'restaurants';
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'phone', 'password', 'delivery_cost', 'minimum_order', 'image', 'whatsapp', 'activated', 'region_id','api_token','pin_code');

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notifiable');
    }

    public function tokens()
    {
        return $this->morphMany('App\Models\Token', 'tokenable');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment');
    }

}