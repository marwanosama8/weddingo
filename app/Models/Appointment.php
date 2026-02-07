<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model 
{

    protected $table = 'appointments';
    public $timestamps = true;
    protected $fillable = array('user_id', 'service_id', 'from_time', 'date_time', 'total_price', 'status');

}