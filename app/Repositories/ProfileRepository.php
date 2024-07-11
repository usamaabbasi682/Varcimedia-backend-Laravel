<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\ProfileRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function getProfile($user)
    {
        return User::find($user);
    }

    public function updateProfile(Request $request) 
    {
        try {
            $user = auth('sanctum')->user();
            if(Hash::check($request->input('current_password'), $user->password)) {
                $hashed_password = $request->input('password') != '' ? Hash::make($request->input('password')) : $user->password;
                $user->update([
                    'full_name' => $request->input('full_name'),
                    'email' => $request->input('email'),
                    'username' => $request->input('username'),
                    'password' => $hashed_password,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'data' => $user
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                    'data' => []
                ]);
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
}
