<?php

namespace App\Models;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements FilamentUser
{
    protected $table = 'admin';
    protected $guard = 'admin';
    
    public $timestamps = true;
   
    protected $fillable = array('name', 'email', 'password');
   
    public function canAccessFilament(): bool
    {
        return str_ends_with($this->email, '@admin.com');
    }
}