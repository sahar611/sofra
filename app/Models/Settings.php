<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model 
{

    protected $table = 'settings';
    public $timestamps = true;
    protected $fillable = array('fb_link', 'tw_link', 'instgram_link', 'phone', 'email', 'app_commission');

}