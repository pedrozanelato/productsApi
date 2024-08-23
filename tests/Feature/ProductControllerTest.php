<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // Cria um usuário autenticado para os testes
        $this->user = User::factory()->create();
    }

    // Usuário autenticado criando produto
    /** @test */
    public function user_aut_create_product()
    {
        $productData = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(['em estoque', 'em reposição', 'em falta']),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/products/create', $productData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'error',
                'response' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('products', $productData);
    }

    // Usuário não autenticado criando produto
    /** @test */
    public function user_unaut_create_product()
    {
        $productData = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(['em estoque', 'em reposição', 'em falta']),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
        ];

        $response = $this->postJson('/api/products/create', $productData);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Unauthorized access.'
            ]);
    }

    // Validar campos obrigatórios criando um produto
    /** @test */
    public function valid_fields_req_create_prod()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/products/create', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'status']);
    }

    
    // Validar tipos dos campos criando um produto
    /** @test */
    public function valid_fields_type_create_prod()
    {
        $productData = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'status' => 'em estoquee',
            'stock_quantity' => $this->faker->randomFloat(2, 10, 1000),
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/products/create', $productData);

        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status', 'stock_quantity']);
    }

    // Listar todos os produtos
    /** @test */
    public function get_all_products()
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'error',
                'response' => [
                    '*' => [
                        'name',
                        'description',
                        'price'
                    ]
                ]
            ]);
    }

    // Atualizando produto
    /** @test */
    public function update_product()
    {
        $product = Product::factory()->create();

        $updatedData = [
            'name' => 'Updated Name',
            'description' => $product->description,
            'price' => 200,
            'status' => $product->status,
            'stock_quantity' => $product->stock_quantity,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/products/update/{$product->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'error' => false,
                'response' => [
                    'id' => $product->id,
                    'name' => 'Updated Name',
                    'price' => 200,
                    'status' => $product->status,
                    'stock_quantity' => $product->stock_quantity,
                ]
            ]);

        $this->assertDatabaseHas('products', $updatedData);
    }

    // Retorno de erro ao atualizar um produto não encontrado.
    /** @test */
    public function update_product_not_found_error()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson('/api/products/update/999', [
                'name' => 'Updated Name',
                'description' => $this->faker->sentence,
                'price' => $this->faker->randomFloat(2, 10, 1000),
                'status' => $this->faker->randomElement(['em estoque', 'em reposição', 'em falta']),
                'stock_quantity' => $this->faker->numberBetween(0, 100),
            ]);

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'true',
                'message' => 'Registro não encontrado.'
            ]);
    }

    //  Validar campos atualizando produto
    /** @test */
    public function valid_fields_update_product()
    {
        $product = Product::factory()->create();

        $updatedData = [
            'name' => '',
            'status' => 'em estoquee',
            'stock_quantity' => $this->faker->randomFloat(2, 10, 1000),
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/products/update/{$product->id}", $updatedData);

        
            $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'status', 'stock_quantity']);
    }


    // Apaga o produto
    /** @test */
    public function delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/products/delete/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'error' => false,
                'response' => true
            ]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    // Retorno de erro ao apagar um produto não encontrado.
    /** @test */
    public function delete_product_not_found_error()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson('/api/products/delete/999');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'true',
                'message' => 'Registro não encontrado.'
            ]);
    }
}
