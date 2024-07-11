<?php
namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface ChatRepositoryInterface
{
    public function chatHistoryProjects();
    public function senderUsers($projectId);
    public function receiverUsers($senderId,$projectId);
    public function getChatting($sender_id,$receiver_id,$project_id);
    public function saveMessage(Request $request);
}