<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = auth()->user();
        return [
            "id"                => $user->id,
            "name"              => $user->name,
            "email"             => $user->email,
            "email_verified_at" => $user->email_verified_at,
            "created_at"        => $user->created_at,
            "updated_at"        => $user->updated_at,
        ];
    }
}
