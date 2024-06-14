<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\RoleRepositoryInterface;

class RoleController extends Controller
{
    protected RoleRepositoryInterface $repository;

    public function __construct(RoleRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }

    public function admins() 
    {
        $users = $this->repository->admin();
        return UserResource::collection($users);
    }

    public function clients() 
    {
        $users = $this->repository->client();
        return UserResource::collection($users);
    }

    public function writers() 
    {
        $users = $this->repository->writer();
        return UserResource::collection($users);
    }

    public function editors() 
    {
        $users = $this->repository->editor();
        return UserResource::collection($users);
    }
}
