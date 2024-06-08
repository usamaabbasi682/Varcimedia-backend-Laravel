<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectDetailResource;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    protected ProjectRepositoryInterface $repository;

    public function __construct(ProjectRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $projects = $this->repository->all($request);
        return ProjectResource::collection($projects->load('user_project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): ProjectResource
    {
        $project = $this->repository->create($request);

        return ProjectResource::make($project->refresh())
            ->additional([
                'success' => true,
                'message' => 'Project created successfully',
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): ProjectDetailResource
    {
        if (!$this->repository->find($id)) {
            return ProjectDetailResource::make(null)
                ->additional(['success' => false, 'message' => 'Project not found']);
        }

        $project = $this->repository->find($id);
        return ProjectDetailResource::make($project->load(['user_project','files']))
            ->additional([
                'success' => true,
                'message' => 'Project retrieved successfully',
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): ProjectResource
    {
        if (!$this->repository->find($id)) {
            return ProjectResource::make(null)
                ->additional(['success' => false, 'message' => 'Project not found']);
        }

        $project = $this->repository->update($request,$id);
        return ProjectResource::make($project->refresh())
            ->additional([
                'success' => true,
                'message' => 'Project updated successfully',
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

    public function removeFile(int $id): JsonResponse 
    {
        if (!$this->repository->findFile($id)) {
            return response()->json(null, 404);
        }

        $this->repository->deleteFile($id);
        return response()->json(null, 204);
    }
}
