<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function getById($id): array;
    public function getAll(array $relationships): array;
    public function create(array $user): array;
    public function update($id, array $user): array;
    public function delete($id): void;
}
