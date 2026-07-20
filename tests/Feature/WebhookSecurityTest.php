<?php

use App\Mail\ActiveAccount;
use App\Models\Store;
use App\Models\User;
use App\Models\WebhookEvent;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    config(['services.checkout.secret' => 'segredo-de-teste']);

    $this->user = User::factory()->create([
        'email' => 'cliente@example.com',
        'status' => 'pending',
        'expires_at' => null,
    ]);

    Store::create([
        'user_id' => $this->user->id,
        'name' => 'Loja Webhook',
        'slug' => 'loja-webhook',
        'phone' => '11999999999',
    ]);
});

it('rejeita webhook sem autenticação válida', function () {
    $this->postJson('/api/webhook/active-account', [
        'secret' => 'segredo-incorreto',
        'event' => 'purchase_approved',
    ])->assertForbidden();

    expect($this->user->fresh()->status)->toBe('pending');
});

it('valida o conteúdo do webhook antes de processar', function () {
    $this->postJson('/api/webhook/active-account', [
        'secret' => 'segredo-de-teste',
        'event' => 'purchase_approved',
        'data' => [],
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['data.id', 'data.customer.email']);
});

it('processa cada pagamento uma única vez', function () {
    Mail::fake();

    $payload = [
        'secret' => 'segredo-de-teste',
        'event' => 'purchase_approved',
        'data' => [
            'id' => 'pagamento-123',
            'customer' => ['email' => $this->user->email],
        ],
    ];

    $this->postJson('/api/webhook/active-account', $payload)
        ->assertOk()
        ->assertJson(['status' => 'ok']);

    $firstExpiration = $this->user->fresh()->expires_at;

    $this->postJson('/api/webhook/active-account', $payload)
        ->assertOk()
        ->assertJson(['status' => 'already_processed']);

    expect($this->user->fresh()->status)->toBe('active')
        ->and($this->user->fresh()->expires_at->equalTo($firstExpiration))->toBeTrue()
        ->and(WebhookEvent::count())->toBe(1);

    Mail::assertSent(ActiveAccount::class, 1);
});

it('rejeita pagamento de produto ou valor diferente do configurado', function () {
    config([
        'services.checkout.product_id' => 'produto-storex',
        'services.checkout.product_id_field' => 'data.product.id',
        'services.checkout.amount' => '61.75',
        'services.checkout.amount_field' => 'data.amount',
    ]);

    $payload = [
        'secret' => 'segredo-de-teste',
        'event' => 'purchase_approved',
        'data' => [
            'id' => 'pagamento-invalido',
            'customer' => ['email' => $this->user->email],
            'product' => ['id' => 'outro-produto'],
            'amount' => 10,
        ],
    ];

    $this->postJson('/api/webhook/active-account', $payload)
        ->assertUnprocessable()
        ->assertJson(['error' => 'Purchase does not match']);

    expect($this->user->fresh()->status)->toBe('pending')
        ->and(WebhookEvent::count())->toBe(0);
});
