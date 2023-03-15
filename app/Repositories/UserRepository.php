<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function getById($id, array $relationships = []): array
    {
        return User::with($relationships)->findOrFail($id)->toArray();
    }

    public function getAll(array $relationships = []): array
    {
        $users = User::with($relationships)->get();
        return $users->toArray();

    }

    public function create(array $user): array
    {
        $user['password'] = Hash::make($user['password']);
        $userModel = User::create($user);

        if (Arr::has($user, 'userDetails')) {
            $userModel->userDetails()->createMany(Arr::get($user, 'userDetails', []));
            $userModel = $userModel->load(['userDetails']);
        }

        return $userModel->toArray();
    }

    public function update($id, array $user): array
    {
        $user['password'] = Hash::make($user['password']);

        $userModel = User::findOrFail($id);

        if (Arr::has($user, 'userDetails')) {
            $userModel->userDetails()->delete();
            $userModel->userDetails()->createMany(Arr::get($user, 'userDetails', []));
            $userModel = $userModel->load(['userDetails']);
        }

        $userModel->update(Arr::except($user,'userDetails'));

        return $userModel->toArray();
    }

    public function delete($id): void
    {
        User::destroy($id);
    }
}
