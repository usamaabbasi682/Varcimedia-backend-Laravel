<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'full_name' => ucwords($this->full_name),
            'username' => $this->username,
            'email' => $this->email,
            'created_at' => $this->created_at->format('d M, Y'),
            'role' => $this->getRoleNames()[0],
            'projects' => $this->projects()->count(),
            'completed_projects' => $this->projects()->where('work_status', 'completed')->count(),
            'pending_projects' => $this->projects()->where('work_status', 'pending')->count(),
        ] : [];
    }
}
