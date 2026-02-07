<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resservasion extends Model
{
    use  HasFactory;
    protected $table = 'resservasions';
    public $timestamps = true;
    protected $fillable = array('user_id','partner_id','status', 'date_time', 'total_price');

    protected $casts = [
        'created_at' => 'date',
    ];

    /**
     * The resservasionPriceLists that belong to the Resservasion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resservasionPriceLists()
    {
        return $this->belongsToMany(PartnerPriceList::class, 'resservasion_pricelists', 'resservasion_id', 'pricelist_id');
    }

    /**
     * Get the user that owns the Resservasion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the partner that owns the Resservasion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
