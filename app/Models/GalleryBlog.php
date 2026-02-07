<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Concerns\InteractsWithPivotTable;

class GalleryBlog extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    public $timestamps = true;
    protected $fillable = array(
        'partner_id',
        'love_reaction',
        'sad_reaction',
        'angry_reaction',
        'appreciate_reaction',
        'comment',
        'caption'
    );

    /**
     * Get all of the comments for the GalleryBlog
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'galleryblog_id', 'id');
    }

    /**
     * The reactions that belong to the GalleryBlog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reactions()
    {
        return $this->belongsToMany(Reaction::class, 'galleryblog_reactions', 'galleryblog_id', 'reaction_id')->withPivot('user_id');
    }

    /**
     * Get the partner that owns the GalleryBlog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
