<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): ?array
    {
        return $this->resource ?  
        [
            'id' => $this->id,
            'user' => $this->user->full_name,
            'title' => $this->title,
            'name' => $this->name,
            'description' => $this->description,
            'end_date' => $this->end_date->format('d M, Y'),
            'created_at' => $this->created_at->format('d M, Y'),
            'status' => $this->status,
        ] : [];
    }
}
