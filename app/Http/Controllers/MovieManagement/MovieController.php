<?php

namespace App\Http\Controllers\MovieManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\StoreRequest;
use App\Http\Requests\Movie\UpdateRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(MovieResource::collection(Movie::all()));
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try
        {
            $image = $request->file('image');

            $image_name = str_replace(' ', '', $request->title) . '.' . $image->getClientOriginalExtension();

            Movie::create([
                'title' => \ucwords($request->title),
                'description' => $request->description,
                'genre' => $request->genre,
                'publish_day' => $request->publish_day,
                'image' => Storage::disk('public')->putFileAs('images', new File($image), $image_name),
            ]);

            return response()->json([
                'message' => 'created successfully'
            ], 200);
        }
        catch (\Throwable $th)
        {
            Log::error($th->getMessage());

            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie): JsonResponse
    {
        return response()->json([
            'movie' => $movie
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(UpdateRequest $request, Movie $movie): JsonResponse
    {
        try
        {
            if (Storage::disk('public')->exists($movie->image))
            {
                Storage::disk('public')->delete($movie->image);
            }

            $image = $request->file('image');

            $image_name = str_replace(' ', '', $request->title) . '.' . $image->getClientOriginalExtension();

            $movie->update([
                'title' => \ucwords($request->title),
                'description' => $request->description,
                'genre' => $request->genre,
                'publish_day' => $request->publish_day,
                'image' => Storage::disk('public')->putFileAs('images', new File($image), $image_name),
            ]);

            return response()->json([
                'message' => 'updated successfully'
            ], 200);

        }
        catch (\Throwable $th)
        {
            Log::error($th->getMessage());

            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(Movie $movie): JsonResponse
    {
        try
        {
            if (Storage::exists($movie->image))
            {
                Storage::delete($movie->image);
            }

            $movie->delete();

            return response()->json([
                'message' => 'deleted successfully'
            ], 200);

        }
        catch (\Throwable $th)
        {
            Log::error($th->getMessage());

            throw $th;
        }
    }
}
