<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;


    protected $fillable = [
        'project_id',
        'sender_id',
        'receiver_id',
        'message'
    ];
    
    public function scopeChatting(Builder $query, $sender_id, $receiver_id) 
    {
        $query->where(function($query) use($sender_id,$receiver_id){
            $query->where('sender_id','=',$sender_id)
                ->orWhere('sender_id','=',$receiver_id);
        })->where(function($query) use($sender_id,$receiver_id){
            $query->where('receiver_id','=',$sender_id)
                ->orWhere('receiver_id','=',$receiver_id);
        });
    }

    public function scopeOrder(Builder $query,$order) 
    {
        $query->orderBy('id',$order);
    }

    public function scopeWhereProjectId(Builder $query,$project_id) 
    {
        $query->where('project_id',$project_id);
    }
}
