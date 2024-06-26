<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ChatResource;
use App\Http\Requests\Chat\SaveMessageRequest;
use App\Repositories\Contracts\ChatRepositoryInterface;

class ChatController extends Controller
{
    protected ChatRepositoryInterface $repository;

    public function __construct(ChatRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }

    public function index($sender_id,$receiver_id,$project_id) 
    {
        $chats = $this->repository->getChatting($sender_id,$receiver_id,$project_id);
        return ChatResource::collection($chats);
    }

    public function store(SaveMessageRequest $request)  
    {
        $chat = $this->repository->saveMessage($request);
        if($chat) {
            return response()->json([
                'success' => true,
                'message' => 'Message Sent Successfully',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
        ]);
    }
}
