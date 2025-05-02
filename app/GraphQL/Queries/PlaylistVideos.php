<?php

namespace App\GraphQL\Queries;

use App\Models\Playlist;
use App\Models\User;
use App\Models\Video;

class PlaylistVideos
{
  public function resolve($rootValue, array $args)
  {
    // Obtener el usuario autenticado (asumimos id 1 como en tu ejemplo anterior)
    $user = auth('api')->user();
    if (!$user) {
      throw new \Exception('Unauthenticated user');
    }

    $playlistId = $args['playlistId'];

    // Verificar que la playlist pertenece al usuario autenticado
    $playlist = Playlist::where('id', $playlistId)
      ->where('admin_id', $user->id)
      ->first();

    if (!$playlist) {
      return [];
    }

    // Obtener los videos asociados a la playlist
    return Video::whereHas('playlists', function ($query) use ($playlistId) {
      $query->where('playlists.id', $playlistId);
    })->get();
  }
}