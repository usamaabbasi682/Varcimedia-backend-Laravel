<?php

namespace App\Repositories;

use App\Models\Chat;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Repositories\Contracts\ChatRepositoryInterface;

class ChatRepository implements ChatRepositoryInterface 
{
    public function chatHistoryProjects() 
    {
        $projects = Project::select('name','id')->orderBy('name','ASC')->get();
        return $projects;
    }

    public function getChatting($sender_id,$receiver_id,$project_id) 
    {
        $chatting = Chat::whereProjectId($project_id)
                ->chatting($sender_id,$receiver_id)
                ->order('ASC')
                ->get();
        return $chatting;
    }

    public function senderUsers($projectId) 
    {
        $project = Project::findOrFail($projectId);
        return $project->chat_history;
    }
    
    public function receiverUsers($senderId,$projectId) 
    {
        $project = Project::findOrFail($projectId);
        return $project->chat_history()->where('user_id','!=',$senderId)->get();
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