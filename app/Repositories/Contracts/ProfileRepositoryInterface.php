<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface ProfileRepositoryInterface
{
    public function getProfile($user);
    public function updateProfile(Request $request);
}
