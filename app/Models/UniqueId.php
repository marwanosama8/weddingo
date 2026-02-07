<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniqueId extends Model
{
    protected $table = 'subscribtion_unique_id';
    public $timestamps = true;
    protected $fillable = array('subscription_id','unique_id');

    /**
     * Get the subscription that owns the UniqueId
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

}
