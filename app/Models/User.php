<?php

namespace App\Models;

use App\Models\Partner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Musonza\Chat\Traits\Messageable;

class User extends Authenticatable implements HasMedia
{
    use InteractsWithMedia;
    use HasApiTokens, HasFactory, Notifiable;
    use Messageable;

    protected $table = 'users';
    public $timestamps = true;
    protected $fillable = array('first_name', 'last_name', 'device_token', 'phone', 'email', 'gender', 'is_blocked', 'birth_date', 'password', 'provider_name', 'provider_id', 'country_id', 'city_id');
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'birth_date' => 'date:Y-m-d',
    ];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function getAvatarAttribute()
    {
        return $this->getFirstMediaUrl() ?? null;
    }

    //    relations

    /**
     * Get all of the favorites for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    /**
     * Get all of the partner for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function partner()
    {
        return $this->hasOne(Partner::class);
    }

    /**
     * Get all of the priceList for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function priceList()
    {
        return $this->hasManyThrough(PartnerPriceList::class, Partner::class);
    }

    /**
     * Get all of the reviews for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function reviews()
    {
        return $this->hasManyThrough(Comment::class, Post::class);
    }

    /**
     * Get all of the resservasions for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resservasions()
    {
        return $this->hasMany(Resservasion::class);
    }

    //  Accessors and mutators


    // public function birth_date(): Attribute
    // {
    //     return new Attribute(
    //         get: fn ($value) => Carbon::,
    //         set: fn ($value) => $value,
    //     );
    // }
}
