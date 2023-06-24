<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'username' => auth('operator')->user()->username,
            'email' => auth('operator')->user()->email,
            'avatar' => auth('operator')->user()->avatar,
        ];
    }
}
