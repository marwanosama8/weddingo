<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Musonza\Chat\Traits\Messageable;

class Partner extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    use Messageable;


    protected $table = 'partners';
    public $timestamps = true;
    protected $fillable = array('user_id', 'points', 'weekends', 'category_id', 'other_categroy_id', 'bio', 'rate', 'gallery_limit', 'active', 'business_name', 'business_type', 'social_provider', 'social_url', 'about_us_survey', 'timestamps', 'address_address', 'address_latitude', 'address_longitude');
    protected $casts = ['weekends' => 'array'];
    /**
     * Get the user that owns the Partner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the reviews for the Partner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * The categories that belong to the Partner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'partner_categories', 'partner_id', 'category_id');
    }

    /**
     * Get all of the priceList for the Partner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function priceLists()
    {
        return $this->hasMany(PartnerPriceList::class);
    }

    /**
     * Get the category that owns the Partner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('active', 1);
    }

    /**
     * Get all of the galleryblogs for the Partner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function galleryBlogs()
    {
        return $this->hasMany(GalleryBlog::class);
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
}
