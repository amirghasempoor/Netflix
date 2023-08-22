<?php

namespace App\Http\Controllers\MovieManagement;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActionMovieResource;
use App\Http\Resources\ComedyMovieResource;
use App\Http\Resources\DramaMovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function action(): JsonResponse
    {
        $actionMovies = Movie::where('genre', 'LIKE', '%action%')->get();

        return response()->json(ActionMovieResource::collection($actionMovies));
    }

    public function drama(): JsonResponse
    {
        $dramaMovies = Movie::where('genre', 'LIKE', '%drama%')->get();

        return response()->json(DramaMovieResource::collection($dramaMovies));
    }

    public function comedy(): JsonResponse
    {
        $comedyMovies = Movie::where('genre', 'LIKE', '%comedy%')->get();

        return response()->json(ComedyMovieResource::collection($comedyMovies));
    }

    public function adventure(): JsonResponse
    {
        $adventureMovies = Movie::where('genre', 'LIKE', '%adventure%')->get();

        return response()->json(ComedyMovieResource::collection($adventureMovies));
    }
}
