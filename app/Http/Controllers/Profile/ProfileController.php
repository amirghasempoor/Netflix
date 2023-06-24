<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
   public function info()
   {
        return response()->json(new UserResource(auth()->user()));
   }
}
