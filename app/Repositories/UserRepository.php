<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface 
{
    
    public function all() 
    {
        return User::orderBy('id','DESC')->paginate(12);
    }

    public function create(Request $request) 
    {
        try {
            $user = User::create([
                'full_name' => $request->input('full_name') ?? '',
                'username' => $request->input('username') ?? '',
                'email' => $request->input('email') ?? '',
                'password' => Hash::make($request->input('password')) ?? '',
            ]);
            $user->assignRole($request->input('role'));

            return $user;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function find($user)
    {
        return User::find($user);
    }

    public function update($user, Request $request) 
    {
        try {
            $user = User::findOrFail($user);
            $user->update([
                'full_name' => $request->input('full_name') ?? '',
                'username' => $request->input('username') ?? '',
                'email' => $request->input('email') ?? '',
            ]);

            if (!$user->hasRole($request->input('role'))) {
                $user->syncRoles($request->input('role'));
            }
            return $user;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function delete($user) 
    {
        $user = User::findOrFail($user);
        $user->delete();
    }
}