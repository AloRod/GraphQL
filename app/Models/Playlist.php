<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = [
        'name',
        'admin_id',
        'associated_profiles',
    ];

    // Convierte automáticamente el campo associated_profiles a un array
    protected $casts = [
        'associated_profiles' => 'array',
    ];

    // Relación con Videos
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'playlist_video', 'playlist_id', 'video_id');
    }
    // Relación con Perfiles (Usuarios Restringidos)
    public function profiles()
    {
        return $this->belongsToMany(RestrictedUser::class, 'playlist_restricted_user', 'playlist_id', 'restricted_user_id');
    }
    
}

