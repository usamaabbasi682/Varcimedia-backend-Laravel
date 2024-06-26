<?php

namespace App\Http\Resources;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Http\Resources\FileResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): ?array
    {
        $projectUsers = $this->user_project()->orderByDesc(
            Chat::select('created_at')
            ->whereColumn('receiver_id', 'users.id')
            ->orWhereColumn('sender_id', 'users.id')
            ->orderByDesc('created_at')
            ->limit(1)
        )->get();
        
        return $this->resource ?  
        [
            'id' => $this->id,
            'user' => $this->user->full_name ?? '',
            'title' => $this->title,
            'name' => $this->name,
            'description' => $this->description,
            'end_date' => $this->end_date->format('d M, Y'),
            'end_date_without_format' => $this->end_date->format('Y-m-d h:m:s'),
            'created_at' => $this->created_at->format('d M, Y'),
            'status' => $this->status,
            'work_status'=> $this->work_status,
            'files' => FileResource::collection($this->whenLoaded('files')),
            'users' => ChatUserResource::collection($projectUsers),
        ] : [];
    }
}
