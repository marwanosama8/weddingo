<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentInvoice extends Model 
{

    protected $table = 'appointment_invoices';
    public $timestamps = true;
    protected $fillable = array('appointment_id', 'payment_method', 'total_price', 'fixed_price');

}