<?php

namespace App\Http\Controllers\MovieManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\StoreRequest;
use App\Http\Requests\Movie\UpdateRequest;
use App\Models\Movie;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'movies' => Movie::all(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $image = $request->file('image');
        $image_name = $request->title . '.' . $image->getClientOriginalExtension();

        Movie::create([
            'title' => $request->title,
            'description' => $request->description,
            'genre' => $request->inputgenre,
            'publish_day' => $request->publish_day,
            'image' => $image->storeAs('public/images', $image_name),
        ]);

        return response()->json([
            'message' => 'created successfully'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        return response()->json([
            'movie' => $movie
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Movie $movie)
    {
        if (Storage::exists($movie->image)) {
            Storage::delete($movie->image);
        }

        $image = $request->file('image');
        $image_name = $request->title . '.' . $image->getClientOriginalExtension();

        $movie->update([
            'title' => $request->title,
            'description' => $request->description,
            'genre' => $request->inputgenre,
            'publish_day' => $request->publish_day,
            'image' => $image->storeAs('public/images', $image_name),
        ]);

        return response()->json([
            'message' => 'updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        if (Storage::exists($movie->image)) {
            Storage::delete($movie->image);
        }

        $movie->delete();

        return response()->json([
            'message' => 'deleted successfully'
        ], 200);
    }
}
