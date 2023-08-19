<?php

namespace App\Http\Controllers;

use App\Http\Requests\Operator\ChangePasswordRequest as OperatorChangePasswordRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Resources\OperatorResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        try {

            $user = auth()->user();

            if (! Hash::check($request->current_password, $user->password)) {
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

        } catch (\Throwable $th) {

            Log::error($th->getMessage());

            throw $th;
        }
    }

    /**
     * @throws \Throwable
     */
    public function operatorChangePassword(OperatorChangePasswordRequest $request): JsonResponse
    {
        try {

            $operator = auth('operator')->user();

            if (! Hash::check($request->current_password, $operator->password)) {
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

        } catch (\Throwable $th) {

            Log::error($th->getMessage());

            throw $th;
        }
    }
}
