<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model 
{

    protected $table = 'points';
    public $timestamps = true;
    protected $fillable = array('user_id', 'points');

}