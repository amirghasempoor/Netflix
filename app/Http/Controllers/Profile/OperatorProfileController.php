<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\OperatorResource;
use Illuminate\Http\Request;

class OperatorProfileController extends Controller
{
    public function profile()
    {
        return response()->json(new OperatorResource(auth('operator')->user()));
    }
}
