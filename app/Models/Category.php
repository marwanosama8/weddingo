<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;

    protected $table = 'categories';
    public $timestamps = true;
    protected $fillable = array('name', 'viewes_count');

    // /**
    //  * Get all of the partners for the Category
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function partners()
    // {
    //     return $this->hasMany(Partner::class);
    // }

    /**
     * The partners that belong to the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function partners()
    {
        return $this->belongsToMany(Partner::class, 'partner_categories', 'category_id', 'partner_id');
    }

    /**
     * Get all of the catgeoryBlogs for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function catgeoryBlogs()
    {
        return $this->hasManyThrough(GalleryBlog::class, Partner::class)->where('active', 1);
    }
}
