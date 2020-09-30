<?php

namespace Tests\Feature;

use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected $user;

    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create('pt_BR');
    }

    protected function tearDown(): void
    {
        if ($this->user) {
            $this->user->delete();
        }

        parent::tearDown();
    }

    public function testCanCreateUser()
    {
        $password = $this->faker->password;

        $request = [
            'name' => $this->faker->name,
            'email'=> $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password
        ];
        
        $response = $this->postJson('/api/users', $request);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'email',
                'updated_at',
                'created_at',
                'id'
            ]);

        if ($response->status() === 201) {
            $this->user = User::find($response->json('id'));
        }
    }

    public function testCannotCreateUserWithInvalidRequest()
    {
        $request = [];

        $response = $this->postJson('/api/users', $request);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'email',
                    'password',
                ]
            ]);
    }
}
