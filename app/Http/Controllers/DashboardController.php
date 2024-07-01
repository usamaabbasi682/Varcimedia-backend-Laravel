<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\DashboardResource;

class DashboardController extends Controller
{
    public function index(): DashboardResource
    {
        $users = User::all();
        return new DashboardResource($users);
    }
}
