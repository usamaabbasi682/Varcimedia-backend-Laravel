<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\Contracts\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface 
{
    public function admin() 
    {
        $users = User::role('admin')->get();
        return $users;
    }

    public function client() 
    {
        $users = User::role('client')->get();
        return $users;
    }

    public function writer() 
    {
        $users = User::role('writer')->get();
        return $users;
    }

    public function editor() 
    {
        $users = User::role('editor')->get();
        return $users;
    }
  
}