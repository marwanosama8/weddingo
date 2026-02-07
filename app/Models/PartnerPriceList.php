<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerPriceList extends Model 
{
    use HasFactory;
    protected $table = 'partner_pricelists';
    public $timestamps = false;
    protected $fillable = array('partner_id', 'service', 'price');

}