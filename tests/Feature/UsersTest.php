<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_fetching_users(): void
    {
        User::factory(3)->create();
        $response = $this->get('/api/users');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(fn (AssertableJson $json) => $json->has(3));

    }

    public function test_creating_a_new_user(): void
    {
        $payload = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'email@email.com',
            'password' => 'password',
        ];

        $response = $this->json('post','/api/users', $payload);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_create_with_invalid_data(): void
    {
        $payload = [
            'last_name' => 'Last name',
            'email' => 'email@email.com',
            'password' => 'password',
        ];

        $response = $this->json('post','/api/users', $payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    public function test_update_for_missing_user() {

        $payload = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'email@email.com',
            'password' => 'password',
        ];

        $this->json('put', '/api/user/45', $payload)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_updating_a_user(): void
    {
        $user = User::factory()->create();

        $payload = [
            'first_name' => 'Updated name',
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->json('put','/api/users/' . $user->id, $payload);
        $response->assertStatus(Response::HTTP_OK);

        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['first_name'] === 'Updated name');
    }

    public function test_update_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $payload = [
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->json('put','/api/users/' . $user->id, $payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
