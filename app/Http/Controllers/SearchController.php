<?php

namespace App\Http\Controllers;

use App\Http\Resources\SearchedMovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $searched = Movie::where('title', $request->title)->get();

        return response()->json(SearchedMovieResource::collection($searched));
    }
}
