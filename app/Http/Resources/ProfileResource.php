<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id_user' => $this->id,
            'name_user' => $this->name,
            'email_user' => $this->email,
            'created_at' => $this->createdAt(),
            'updated_at' => $this->updatedAt(),
        ];
    }

    public function createdAt() 
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i:s');
    }

    public function updatedAt() 
    {
        return Carbon::parse($this->updated_at)->format('Y-m-d H:i:s');
    }
}
