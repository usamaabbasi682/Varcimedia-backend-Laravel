<?php

namespace App\Http\Resources;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatUserResource extends JsonResource
{
    protected $projectId;

    public function __construct($resource, $projectId)
    {
        parent::__construct($resource);
        $this->projectId = $resource->pivot->project_id;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): ?array
    {        
        $chatting = Chat::whereProjectId($this->projectId)
            ->chatting(auth('sanctum')->id(),$this->id)
            ->latest('created_at')
            ->first();

        return $this->resource ?  
        [
            'id' => $this->id,
            'full_name' => ucwords($this->full_name),
            'username' => $this->username,
            'email' => $this->email,
            'created_at' => $this->created_at->format('d M, Y'),
            'role' => $this->getRoleNames()[0],
            'latest_message' => new LatestMessageResource($chatting),
        ] : [];
    }
}
