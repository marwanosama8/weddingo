<?php
namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    /**
     * Get all of the galleryblog for the Media
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function galleryblog()
    {
        return $this->hasMany(GalleryBlog::class,'media_id','id');
    }
}