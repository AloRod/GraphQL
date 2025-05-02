<?php

namespace App\GraphQL\Queries;

use App\Models\RestrictedUser;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class SearchVideos
{
    public function resolve($rootValue, array $args)
    {
        $user = auth('api')->user();
        if (!$user) {
            throw new \Exception('Unauthenticated user');
        }

        $query = $args['query'];
        $restrictedUserId = $args['restricted_user_id'] ?? null;


        if ($restrictedUserId) {
            $restrictedUser = RestrictedUser::where('id', $restrictedUserId)
                ->where('user_id', $user->id)
                ->first();

            if (!$restrictedUser) {
                throw new \Exception('Restricted user not found or not authorized');
            }

            $accessiblePlaylistIds = DB::table('playlist_restricted_user')
                ->where('restricted_user_id', $restrictedUserId)
                ->pluck('playlist_id');

            return Video::where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
                ->whereHas('playlists', function ($query) use ($accessiblePlaylistIds) {
                    $query->whereIn('playlists.id', $accessiblePlaylistIds);
                })
                ->get();
        } else {
            return Video::where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
                ->where('user_id', $user->id)
                ->get();
        }
    }
}