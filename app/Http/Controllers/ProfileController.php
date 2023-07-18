<?php

namespace App\Http\Controllers;

use App\Http\Resources\OperatorResource;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    public function userInfo()
    {
        return response()->json(new UserResource(auth()->user()));
    }

    public function operatorInfo()
    {
        return response()->json(new OperatorResource(auth('operator')->user()));
    }
}
