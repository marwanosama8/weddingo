<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model 
{

    protected $table = 'favorites';
    public $timestamps = true;
    protected $fillable = array('user_id', 'partner_id');

    protected $casts = [
        'created_at' => 'date'
    ];

    /**
     * Get the user that owns the Favorite
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}