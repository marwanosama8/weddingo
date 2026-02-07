<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $table = 'subscriptions';
    public $timestamps = true;
    protected $fillable = array('description','subscription_request', 'name','price','blog_per_month');

    /**
     * Get the user that owns the Subscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the uniqueId for the Subscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function uniqueId()
    {
        return $this->hasMany(UniqueId::class, 'subscription_id', 'id');
    }
    
}
