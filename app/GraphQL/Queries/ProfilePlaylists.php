<?php

namespace App\GraphQL\Queries;

use App\Models\RestrictedUser;
use App\Models\Playlist;
use App\Models\PlaylistUser;
use App\Models\User;

class ProfilePlaylists
{
  public function resolve($rootValue, array $args)
  {
    $user = auth('api')->user();
    if (!$user) {
      throw new \Exception('Unauthenticated user');
    }

    $query = Playlist::where('admin_id', $user->id)->withCount('videos')->with('profiles') ;

    if (isset($args['profileId'])) {
      $profileId = $args['profileId'];

      $profile = RestrictedUser::where('id', $profileId)
        ->where('user_id', $user->id)
        ->first();

      if (!$profile) {
        return [];
      }

      $playlistIds = PlaylistUser::where('restricted_user_id', $profileId)
        ->pluck('playlist_id')
        ->toArray();

      $query->whereIn('id', $playlistIds);
    }

    return $query->get();
  }
}