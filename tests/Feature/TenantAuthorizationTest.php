<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Store;
use App\Models\StoreAddress;
use App\Models\User;
use App\Models\Variation;
use App\Models\VariationGroup;
use Illuminate\Support\Facades\Gate;

beforeEach(function () {
    config([
        'app.domain' => 'storex.test',
        'app.url' => 'http://storex.test',
    ]);

    $this->userA = User::factory()->create();
    $this->storeA = Store::create([
        'user_id' => $this->userA->id,
        'name' => 'Loja A',
        'slug' => 'loja-a',
        'phone' => '11999999999',
    ]);
    StoreAddress::create([
        'store_id' => $this->storeA->id,
        'street' => 'Rua A',
        'number' => '10',
        'neighborhood' => 'Centro',
        'city' => 'São Paulo',
        'state' => 'SP',
        'zip_code' => '01001000',
    ]);

    $this->userB = User::factory()->create();
    $this->storeB = Store::create([
        'user_id' => $this->userB->id,
        'name' => 'Loja B',
        'slug' => 'loja-b',
        'phone' => '21999999999',
    ]);
    StoreAddress::create([
        'store_id' => $this->storeB->id,
        'street' => 'Rua B',
        'number' => '20',
        'neighborhood' => 'Centro',
        'city' => 'Rio de Janeiro',
        'state' => 'RJ',
        'zip_code' => '20040002',
    ]);
});

it('edita somente o perfil do usuário autenticado', function () {
    $response = $this->actingAs($this->userA)->patch('http://storex.test/dashboard/profile', [
        'store' => [
            'name' => 'Loja A Atualizada',
            'phone' => '11888888888',
            'description' => 'Descrição atualizada',
            'delivery_fee' => 5,
        ],
        'address' => [
            'street' => 'Rua Atualizada',
            'number' => '11',
            'complement' => null,
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01001000',
        ],
    ]);

    $response->assertSessionHasNoErrors();
    expect($this->storeA->fresh()->name)->toBe('Loja A Atualizada')
        ->and($this->storeB->fresh()->name)->toBe('Loja B');
});

it('não expõe mais uma rota de perfil com id de outro usuário', function () {
    $this->actingAs($this->userA)
        ->get("http://storex.test/dashboard/profile/{$this->userB->id}/edit")
        ->assertNotFound();
});

it('nega pelas policies alterações em produtos e categorias de outro tenant', function () {
    $categoryB = Category::create([
        'tenant_id' => $this->storeB->id,
        'name' => 'Categoria B',
        'status' => true,
    ]);
    $productB = Product::create([
        'tenant_id' => $this->storeB->id,
        'category_id' => $categoryB->id,
        'name' => 'Produto B',
        'price' => 20,
        'status' => true,
    ]);

    expect(Gate::forUser($this->userA)->denies('update', $categoryB))->toBeTrue()
        ->and(Gate::forUser($this->userA)->denies('update', $productB))->toBeTrue();
});

it('não permite cadastrar produto em categoria de outro tenant', function () {
    $categoryB = Category::create([
        'tenant_id' => $this->storeB->id,
        'name' => 'Categoria exclusiva da Loja B',
        'status' => true,
    ]);

    $response = $this->actingAs($this->userA)
        ->from('http://storex.test/dashboard/products/create')
        ->post('http://storex.test/dashboard/products', [
            'name' => 'Produto invasor',
            'category_id' => $categoryB->id,
            'price' => 10,
            'status' => 1,
        ]);

    $response->assertSessionHasErrors('category_id');
    $this->assertDatabaseMissing('products', ['name' => 'Produto invasor']);
});

it('não aceita no carrinho uma variação de outro produto ou tenant', function () {
    $categoryA = Category::create([
        'tenant_id' => $this->storeA->id,
        'name' => 'Categoria A',
        'status' => true,
    ]);
    $productA = Product::create([
        'tenant_id' => $this->storeA->id,
        'category_id' => $categoryA->id,
        'name' => 'Produto A',
        'price' => 10,
        'status' => true,
    ]);
    $categoryB = Category::create([
        'tenant_id' => $this->storeB->id,
        'name' => 'Categoria B',
        'status' => true,
    ]);
    $productB = Product::create([
        'tenant_id' => $this->storeB->id,
        'category_id' => $categoryB->id,
        'name' => 'Produto B',
        'price' => 20,
        'status' => true,
    ]);
    $variation = new Variation;
    $variation->name = 'Tamanho';
    $variation->save();
    $groupB = VariationGroup::create([
        'tenant_id' => $this->storeB->id,
        'product_id' => $productB->id,
        'variation_id' => $variation->id,
        'min_selection' => 0,
        'max_selection' => 1,
    ]);
    $optionB = ProductVariation::create([
        'product_id' => $productB->id,
        'variation_group_id' => $groupB->id,
        'value' => 'Grande',
        'additional_price' => 5,
        'status' => true,
    ]);

    $response = $this->from('http://loja-a.storex.test/loja/product/'.$productA->id)
        ->post('http://loja-a.storex.test/loja/cart', [
            'product_id' => $productA->id,
            'quantity' => 1,
            'product_variations' => [$optionB->id],
        ]);

    $response->assertSessionHasErrors('error');
    $response->assertSessionMissing('cart:'.$this->storeA->id);
});

it('adiciona produto sem variação e mantém carrinhos separados por tenant', function () {
    $categoryA = Category::create([
        'tenant_id' => $this->storeA->id,
        'name' => 'Categoria A',
        'status' => true,
    ]);
    $productA = Product::create([
        'tenant_id' => $this->storeA->id,
        'category_id' => $categoryA->id,
        'name' => 'Produto simples',
        'price' => 10,
        'status' => true,
    ]);

    $this->post('http://loja-a.storex.test/loja/cart', [
        'product_id' => $productA->id,
        'quantity' => 2,
    ])->assertSessionHas('cart:'.$this->storeA->id);

    $this->get('http://loja-b.storex.test/loja/cart')
        ->assertSessionMissing('cart:'.$this->storeB->id);
});

it('aplica no servidor o mínimo obrigatório de uma variação', function () {
    $categoryA = Category::create([
        'tenant_id' => $this->storeA->id,
        'name' => 'Categoria A',
        'status' => true,
    ]);
    $productA = Product::create([
        'tenant_id' => $this->storeA->id,
        'category_id' => $categoryA->id,
        'name' => 'Produto com tamanho',
        'price' => 10,
        'status' => true,
    ]);
    $variation = new Variation;
    $variation->name = 'Tamanho';
    $variation->save();
    VariationGroup::create([
        'tenant_id' => $this->storeA->id,
        'product_id' => $productA->id,
        'variation_id' => $variation->id,
        'min_selection' => 1,
        'max_selection' => 1,
    ]);

    $response = $this->from('http://loja-a.storex.test/loja/product/'.$productA->id)
        ->post('http://loja-a.storex.test/loja/cart', [
            'product_id' => $productA->id,
            'quantity' => 1,
        ]);

    $response->assertSessionHasErrors('error');
    $response->assertSessionMissing('cart:'.$this->storeA->id);
});
