<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\ProfileRequest;
use App\Repositories\Contracts\ProfileRepositoryInterface;

class ProfileController extends Controller
{
    protected ProfileRepositoryInterface $repository;

    public function __construct(ProfileRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }

    public function edit(int $id) 
    {
        if (!$this->repository->getProfile($id)) {
            return UserResource::make(null)
                ->additional(['success' => false, 'message' => 'User not found']);
        }

        return UserResource::make($this->repository->getProfile($id))
            ->additional([
                'success' => true,
                'message' => 'User retrieved successfully',
            ]);
    }

    public function update(ProfileRequest $request) 
    {
        $response = $this->repository->updateProfile($request);
        return $response;
    }
}
