<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\ChatHistory\ProjectResource;
use App\Repositories\Contracts\ChatRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatHistoryController extends Controller
{
    protected ChatRepositoryInterface $repository;

    public function __construct(ChatRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }
    
    public function fetchProject(): AnonymousResourceCollection
    {
        $projects = $this->repository->chatHistoryProjects();
        return ProjectResource::collection($projects);
    }

    public function fetchSenderUsers($id) 
    {
        $users = $this->repository->senderUsers($id);
        return UserResource::collection($users);
    }

    public function fetchReceiverUsers($senderId,$projectId) 
    {
        $users = $this->repository->receiverUsers($senderId,$projectId);
        return UserResource::collection($users);
    }
}
