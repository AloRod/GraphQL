<?php

namespace App\GraphQL\Queries;

use App\Models\RestrictedUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MyRestrictedUsers
{
  public function resolve($rootValue, array $args, $resolveInfo, $context)
  {
    $user = auth('api')->user();
    if (!$user) {
      throw new \Exception('Unauthenticated user');
    }

    $query = RestrictedUser::where('user_id', $user->id);

    if(isset($args['id'])){
      $query->where('id', $args['id']);
      return $query->first();
    }
    return $query->get();
  }
}