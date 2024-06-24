<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            'id' => $this->id ?? '',
            'url' => asset('storage'.$this->url) ?? '',
            'original_name' => $this->original_name ?? '',
            'uploaded_at' => $this->created_at->format('d, M Y'),
        ] : [];
    }
}
