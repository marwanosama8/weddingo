<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model 
{
    use HasFactory;
    protected $table = 'reviews';
    public $timestamps = true;
    protected $fillable = array('partner_id', 'rate', 'review');
    
    // public function getCreatedAtAttribute()
    // {
    //     return $this->created_at;
    // }
}