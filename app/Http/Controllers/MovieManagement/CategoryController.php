<?php

namespace App\Http\Controllers\MovieManagement;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActionMovieResource;
use App\Http\Resources\DramaMovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function Action(): JsonResponse
    {
        $actionMovies = Movie::where('genre', 'action')->get();

        return response()->json(ActionMovieResource::collection($actionMovies));
    }

    public function Drama(): JsonResponse
    {
        $dramaMovies = Movie::where('genre', 'drama')->get();

        return response()->json(DramaMovieResource::collection($dramaMovies));
    }
}
