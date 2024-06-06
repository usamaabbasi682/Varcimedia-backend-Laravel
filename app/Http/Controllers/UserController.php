<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    protected UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $users = $this->repository->all($request);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request): UserResource
    {
        $user = $this->repository->create($request);

        return UserResource::make($user->refresh())
            ->additional([
                'success' => true,
                'message' => 'User created successfully',
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): UserResource
    {
        if (!$this->repository->find($id)) {
            return UserResource::make(null)
                ->additional(['success' => false, 'message' => 'User not found']);
        }

        return UserResource::make($this->repository->find($id))
            ->additional([
                'success' => true,
                'message' => 'User retrieved successfully',
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id): UserResource
    {
        if (!$this->repository->find($id)) {
            return UserResource::make(null)
                ->additional(['success' => false, 'message' => 'User not found']);
        }

        $user = $this->repository->update($id, $request);
        return UserResource::make($user->refresh())
            ->additional([
                'success' => true,
                'message' => 'User updated successfully',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        if (!$this->repository->find($id)) {
            return response()->json(null, 404);
        }

        $this->repository->delete($id);
        return response()->json(null, 204);
    }
}
