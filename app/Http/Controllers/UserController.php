<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Mapper\UserDetailsMapper;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepository, private UserDetailsMapper $detailsMapper)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = $this->userRepository->getAll(['userDetails']);
        $users = collect($users)->map(function($user) {
            return $this->detailsMapper->parse($user);
        });
        return response()->json($users, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $mappedUser = $this->detailsMapper->map($request->validated());
        $parsedUser = $this->detailsMapper->parse($this->userRepository->create($mappedUser));

        return response()->json($parsedUser, Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $user): JsonResponse
    {
        $mappedUser = $this->detailsMapper->map($request->validated());
        $parsedUser = $this->detailsMapper->parse($this->userRepository->update($user, $mappedUser));

        return response()->json($parsedUser, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $user): Response
    {
        $this->userRepository->delete($user);
        return response(null, Response::HTTP_OK);
    }
}
