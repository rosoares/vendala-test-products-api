<?php

namespace Tests\Feature;

use App\Models\Products;
use App\Models\ProductsVariations;
use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    protected $userToken;

    protected $userPassword;

    protected $user;

    protected $faker;

    protected $createdProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create('pt_BR');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function createUser()
    {
        $this->userPassword = $this->faker->password;

        $this->user = $this->user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($this->userPassword),
        ]);
    }

    protected function authUser()
    {
        $this->createUser();

        $response = $this->postJson('/api/auth/login', [
            'email' => $this->user->email,
            'password' => $this->userPassword
        ]);

        $this->userToken = $response->json()['access_token'];
    }

    public function testCanCreateProductWithoutVariation()
    {
        $this->authUser();

        $product = [
            'name' => 'Tênis de Basquete Nike',
            'description' => 'Tênis de Basquete',
            'slug' => 'tenis-basquete-nike',
            'first_stock' => 100,
            'available_stock' => 100,
            'price' => 369.90,
            'hasColorVariation' => false
        ];

        $response = $this
            ->postJson('api/products', $product);

        $this->createdProduct = $response->json();

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'slug',
                'created_by',
                'created_at',
                'updated_at',
                'color_variations' => [
                    [
                        'product_id',
                        'first_stock',
                        'available_stock',
                        'price',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                    ]
                ]
            ]);


    }
}
