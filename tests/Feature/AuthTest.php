<?php

namespace Tests\Feature;

use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected $user;

    protected $faker;

    protected $password;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create('pt_BR');
        $this->password = $this->faker->password;
        $this->user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($this->password),
        ]);
    }

    protected function tearDown(): void
    {
        $this->user->delete();

        parent::tearDown();
    }

    public function testCanAuthenticate()
    {
        $request = [
            'email' => $this->user->email,
            'password' => $this->password
        ];

        $response = $this->postJson('/api/auth/login', $request);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);
    }

    public function testCannotAuthenticateWithInvalidCredentials()
    {
        $request = [
            'email' => 'unknowuser@mail.com',
            'password' => 'unknow'
        ];

        $response = $this->postJson('/api/auth/login', $request);

        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                'error'
            ]);
    }
}
