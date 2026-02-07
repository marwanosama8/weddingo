<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Advertisement extends Model  implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'advertisements';
    public $timestamps = true;
    protected $fillable = array('page_name');

}