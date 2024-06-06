<?php
namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function all(Request $request);
    public function find($user);
    public function create(Request $request);
    public function update($user, Request $request);
    public function delete($user);
}