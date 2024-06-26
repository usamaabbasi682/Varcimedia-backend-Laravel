<?php

namespace App\Repositories;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Repositories\Contracts\ChatRepositoryInterface;

class ChatRepository implements ChatRepositoryInterface 
{
    public function getChatting($sender_id,$receiver_id,$project_id) 
    {
        $chatting = Chat::whereProjectId($project_id)
                ->chatting($sender_id,$receiver_id)
                ->order('ASC')
                ->get();
        return $chatting;
    }

    public function saveMessage(Request $request) 
    {
        try {       
            Chat::create($request->safe()->only(['project_id','sender_id', 'receiver_id','message']));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}