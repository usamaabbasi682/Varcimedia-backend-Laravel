<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LatestMessageResource extends JsonResource
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
            'message' => Str::limit($this->message, 55, '...'),
            'created_at' => $this->created_at->format('d M')
        ] : [];
    }
}
