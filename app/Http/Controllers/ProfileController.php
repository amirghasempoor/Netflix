<?php

namespace App\Http\Controllers;

use App\Http\Requests\Operator\ChangePasswordRequest as OperatorChangePasswordRequest;
use App\Http\Requests\User\ChangeAvatarRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Resources\OperatorResource;
use App\Http\Resources\UserResource;
use App\Models\UserMovie;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function userInfo(): JsonResponse
    {
        return response()->json(new UserResource(auth()->user()));
    }

    public function operatorInfo(): JsonResponse
    {
        return response()->json(new OperatorResource(auth('operator')->user()));
    }

    /**
     * @throws \Throwable
     */
    public function userChangePassword(ChangePasswordRequest $request): JsonResponse
    {
        try
        {
            $user = auth()->user();

            if (! Hash::check($request->current_password, $user->password))
            {
                return response()->json([
                    'message' => 'the current password is wrong .'
                ], 422);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'message' => 'your password has been changed'
            ]);
        }
        catch (\Throwable $th)
        {
            Log::error($th->getMessage());

            throw $th;
        }
    }

    /**
     * @throws \Throwable
     */
    public function operatorChangePassword(OperatorChangePasswordRequest $request): JsonResponse
    {
        try
        {
            $operator = auth('operator')->user();

            if (! Hash::check($request->current_password, $operator->password))
            {
                return response()->json([
                    'message' => 'the current password is wrong .'
                ], 422);
            }

            $operator->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'message' => 'your password has been changed'
            ]);
        }
        catch (\Throwable $th)
        {
            Log::error($th->getMessage());

            throw $th;
        }
    }

    public function userFavoriteMovie(Request $request): JsonResponse
    {
        UserMovie::create([
            'user_id' => auth()->user()->id,
            'movie_id' => $request->movie_id,
        ]);

        return response()->json([
            'message' => 'saved successfully'
        ]);
    }

    public function userChangeAvatar(ChangeAvatarRequest $request)
    {
        $avatar = null;

        if ($request->avatar)
        {
            $avatar = $request->file('avatar');

            $avatar_name = $request->username.'.'.$avatar->getClientOriginalExtension();

            $avatar = Storage::disk('public')->putFileAs('images', new File($avatar), $avatar_name);

        }

        auth()->user()->update([
            'avatar' => $avatar
        ]);

        return response()->json([
            'message' => 'avatar changed successfully'
        ]);
    }

    public function deleteFavoriteMovie(UserMovie $movie): JsonResponse
    {
        $movie->delete();

        return response()->json([
            'message' => 'deleted successfully'
        ]);
    }
}
