<?php
namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface ChatRepositoryInterface
{
     public function getChatting($sender_id,$receiver_id,$project_id);
    public function saveMessage(Request $request);
}