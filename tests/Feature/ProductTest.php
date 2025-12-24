<?php

use App\Models\Product;
use Illuminate\Support\Facades\Http;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('products page displays empty state when no products exist', function () {
    $response = $this->get('/products');

    $response->assertStatus(200)
        ->assertSee('No Products Found');
});

test('products page displays products list', function () {
    Product::factory()->create([
        'title' => 'Test Product',
        'price' => 99.99,
    ]);

    $response = $this->get('/products');

    $response->assertStatus(200)
        ->assertSee('Test Product')
        ->assertSee('99.99');
});

test('can fetch products from api', function () {
    Http::fake([
        'fakestoreapi.com/products' => Http::response([
            [
                'id' => 1,
                'title' => 'Fetched Product',
                'price' => 49.99,
                'description' => 'Test description',
                'category' => 'electronics',
                'image' => 'https://example.com/image.jpg',
                'rating' => [
                    'rate' => 4.5,
                    'count' => 100,
                ],
            ],
        ]),
    ]);

    $response = $this->post('/products/fetch');

    $response->assertRedirect('/products');

    $this->assertDatabaseHas('products', [
        'api_product_id' => 1,
        'title' => 'Fetched Product',
        'price' => 49.99,
    ]);
});

test('can edit a product', function () {
    $product = Product::factory()->create();

    $response = $this->get("/products/{$product->id}/edit");

    $response->assertStatus(200)
        ->assertSee($product->title)
        ->assertSee('Edit Product');
});

test('can update a product', function () {
    $product = Product::factory()->create([
        'title' => 'Original Title',
        'price' => 50.00,
    ]);

    $response = $this->put("/products/{$product->id}", [
        'title' => 'Updated Title',
        'price' => 75.50,
        'category' => 'electronics',
        'description' => 'Updated description',
        'image' => 'https://example.com/new-image.jpg',
    ]);

    $response->assertRedirect('/products');

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'title' => 'Updated Title',
        'price' => 75.50,
    ]);
});

test('update validation requires title and price', function () {
    $product = Product::factory()->create();

    $response = $this->put("/products/{$product->id}", [
        'title' => '',
        'price' => '',
    ]);

    $response->assertSessionHasErrors(['title', 'price']);
});

test('price must be numeric on update', function () {
    $product = Product::factory()->create();

    $response = $this->put("/products/{$product->id}", [
        'title' => 'Valid Title',
        'price' => 'not-a-number',
        'category' => 'electronics',
    ]);

    $response->assertSessionHasErrors(['price']);
});

test('image must be valid url if provided', function () {
    $product = Product::factory()->create();

    $response = $this->put("/products/{$product->id}", [
        'title' => 'Valid Title',
        'price' => 50.00,
        'category' => 'electronics',
        'image' => 'not-a-valid-url',
    ]);

    $response->assertSessionHasErrors(['image']);
});

test('can delete a product', function () {
    $product = Product::factory()->create();

    $response = $this->delete("/products/{$product->id}");

    $response->assertRedirect('/products');

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});

test('duplicate products are not imported when fetching from api', function () {
    Product::factory()->create([
        'api_product_id' => 1,
        'title' => 'Existing Product',
    ]);

    Http::fake([
        'fakestoreapi.com/products' => Http::response([
            [
                'id' => 1,
                'title' => 'Product 1',
                'price' => 10.00,
                'description' => 'Description 1',
                'category' => 'electronics',
                'image' => 'https://example.com/1.jpg',
                'rating' => ['rate' => 4.0, 'count' => 50],
            ],
            [
                'id' => 2,
                'title' => 'Product 2',
                'price' => 20.00,
                'description' => 'Description 2',
                'category' => 'jewelery',
                'image' => 'https://example.com/2.jpg',
                'rating' => ['rate' => 4.5, 'count' => 100],
            ],
        ]),
    ]);

    $this->post('/products/fetch');

    $this->assertDatabaseHas('products', [
        'api_product_id' => 1,
        'title' => 'Existing Product',
    ]);

    $this->assertDatabaseHas('products', [
        'api_product_id' => 2,
        'title' => 'Product 2',
    ]);

    expect(Product::where('api_product_id', 1)->count())->toBe(1);
});
