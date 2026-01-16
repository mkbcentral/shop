<?php

declare(strict_types=1);

namespace Tests\Feature\Pos;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Sale;
use App\Models\Invoice;
use App\Models\Client;
use App\Services\Pos\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Livewire\Pos\CashRegister;

class CashRegisterTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;
    private ProductVariant $variant;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->client = Client::factory()->create();
        
        $this->product = Product::factory()->create([
            'name' => 'Produit Test',
            'price' => 100.00,
        ]);

        $this->variant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'sku' => 'TEST-001',
            'quantity' => 50,
            'price' => 100.00,
        ]);
    }

    /** @test */
    public function it_can_mount_the_component(): void
    {
        $this->actingAs($this->user);

        Livewire::test(CashRegister::class)
            ->assertStatus(200)
            ->assertSet('cartItems', [])
            ->assertSet('subtotal', 0)
            ->assertSet('total', 0);
    }

    /** @test */
    public function it_can_add_item_to_cart(): void
    {
        $this->actingAs($this->user);

        Livewire::test(CashRegister::class)
            ->call('addToCart', $this->variant->id, 2)
            ->assertDispatched('show-toast')
            ->assertSet('subtotal', 200.00)
            ->assertSet('total', 200.00);

        // Vérifier dans la session
        $cart = session()->get('pos_cart', []);
        $this->assertNotEmpty($cart);
        $this->assertEquals(2, $cart[$this->variant->id]['quantity']);
    }

    /** @test */
    public function it_can_remove_item_from_cart(): void
    {
        $this->actingAs($this->user);

        Livewire::test(CashRegister::class)
            ->call('addToCart', $this->variant->id, 2)
            ->call('removeFromCart', $this->variant->id)
            ->assertSet('cartItems', [])
            ->assertSet('total', 0);
    }

    /** @test */
    public function it_can_update_item_quantity(): void
    {
        $this->actingAs($this->user);

        Livewire::test(CashRegister::class)
            ->call('addToCart', $this->variant->id, 2)
            ->call('updateQuantity', $this->variant->id, 5)
            ->assertSet('subtotal', 500.00)
            ->assertSet('total', 500.00);
    }

    /** @test */
    public function it_applies_discount_correctly(): void
    {
        $this->actingAs($this->user);

        Livewire::test(CashRegister::class)
            ->call('addToCart', $this->variant->id, 2)
            ->set('discount', 20.00)
            ->call('applyDiscount')
            ->assertSet('total', 180.00); // 200 - 20
    }

    /** @test */
    public function it_applies_percentage_discount(): void
    {
        $this->actingAs($this->user);

        Livewire::test(CashRegister::class)
            ->call('addToCart', $this->variant->id, 2) // 200
            ->set('discount', 10)
            ->set('discountType', 'percentage')
            ->call('applyDiscount')
            ->assertSet('total', 180.00); // 200 - 10%
    }

    /** @test */
    public function it_can_clear_cart(): void
    {
        $this->actingAs($this->user);

        Livewire::test(CashRegister::class)
            ->call('addToCart', $this->variant->id, 2)
            ->call('clearCart')
            ->assertSet('cartItems', [])
            ->assertSet('total', 0)
            ->assertSet('discount', 0);
    }

    /** @test */
    public function it_completes_cash_payment_successfully(): void
    {
        $this->actingAs($this->user);

        Livewire::test(CashRegister::class)
            ->call('addToCart', $this->variant->id, 2) // 200
            ->set('clientId', $this->client->id)
            ->set('cashReceived', 250.00)
            ->call('processPayment')
            ->assertSet('change', 50.00)
            ->assertDispatched('show-toast')
            ->assertDispatched('print-thermal-receipt');

        // Vérifier la vente en DB
        $this->assertDatabaseHas('sales', [
            'client_id' => $this->client->id,
            'user_id' => $this->user->id,
            'subtotal' => 200.00,
            'total' => 200.00,
            'paid_amount' => 250.00,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);

        // Vérifier la facture
        $this->assertDatabaseCount('invoices', 1);
        
        // Vérifier le stock
        $this->variant->refresh();
        $this->assertEquals(48, $this->variant->quantity); // 50 - 2
    }

    /** @test */
    public function it_validates_insufficient_payment(): void
    {
        $this->actingAs($this->user);

        Livewire::test(CashRegister::class)
            ->call('addToCart', $this->variant->id, 2) // 200
            ->set('cashReceived', 150.00) // Insuffisant
            ->call('processPayment')
            ->assertHasErrors(['cashReceived']);
    }

    /** @test */
    public function it_validates_empty_cart(): void
    {
        $this->actingAs($this->user);

        Livewire::test(CashRegister::class)
            ->set('cashReceived', 100.00)
            ->call('processPayment')
            ->assertSet('errorMessage', 'Le panier est vide.');
    }

    /** @test */
    public function it_validates_insufficient_stock(): void
    {
        $this->actingAs($this->user);

        // Réduire le stock
        $this->variant->update(['quantity' => 1]);

        Livewire::test(CashRegister::class)
            ->call('addToCart', $this->variant->id, 5) // Plus que disponible
            ->assertSet('errorMessage', 'Stock insuffisant pour TEST-001');
    }

    /** @test */
    public function it_can_process_card_payment(): void
    {
        $this->actingAs($this->user);

        Livewire::test(CashRegister::class)
            ->call('addToCart', $this->variant->id, 2) // 200
            ->set('clientId', $this->client->id)
            ->set('paymentMethod', 'card')
            ->call('processPayment')
            ->assertSet('change', 0) // Pas de monnaie pour carte
            ->assertDispatched('show-toast');

        $this->assertDatabaseHas('sales', [
            'payment_method' => 'card',
            'paid_amount' => 200.00,
        ]);
    }

    /** @test */
    public function it_can_process_mobile_money_payment(): void
    {
        $this->actingAs($this->user);

        Livewire::test(CashRegister::class)
            ->call('addToCart', $this->variant->id, 2)
            ->set('clientId', $this->client->id)
            ->set('paymentMethod', 'mobile_money')
            ->call('processPayment')
            ->assertDispatched('show-toast');

        $this->assertDatabaseHas('sales', [
            'payment_method' => 'mobile_money',
        ]);
    }

    /** @test */
    public function it_calculates_daily_stats_correctly(): void
    {
        $this->actingAs($this->user);

        // Créer quelques ventes
        Sale::factory()->create([
            'user_id' => $this->user->id,
            'total' => 100,
            'status' => 'completed',
            'sale_date' => now(),
        ]);

        Sale::factory()->create([
            'user_id' => $this->user->id,
            'total' => 200,
            'status' => 'completed',
            'sale_date' => now(),
        ]);

        Livewire::test(CashRegister::class)
            ->assertSet('todaySales', 300.00)
            ->assertSet('transactionCount', 2);
    }

    /** @test */
    public function it_can_reprint_transaction(): void
    {
        $this->actingAs($this->user);

        // Créer une vente avec facture
        $sale = Sale::factory()->create([
            'user_id' => $this->user->id,
            'client_id' => $this->client->id,
            'total' => 100,
            'paid_amount' => 100,
            'status' => 'completed',
        ]);

        $invoice = Invoice::factory()->create([
            'sale_id' => $sale->id,
            'client_id' => $this->client->id,
            'total_amount' => 100,
        ]);

        Livewire::test(CashRegister::class)
            ->call('reprintTransaction', $sale->id)
            ->assertDispatched('print-thermal-receipt')
            ->assertDispatched('show-toast');
    }

    /** @test */
    public function it_loads_recent_sales_correctly(): void
    {
        $this->actingAs($this->user);

        // Créer des ventes
        Sale::factory()->count(15)->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'sale_date' => now(),
        ]);

        Livewire::test(CashRegister::class)
            ->assertCount('recentSales', 10); // Limite à 10
    }

    /** @test */
    public function it_formats_currency_correctly(): void
    {
        $this->actingAs($this->user);

        $component = Livewire::test(CashRegister::class)
            ->call('addToCart', $this->variant->id, 2);

        // Vérifier le format dans la vue
        $component->assertSee('200'); // Subtotal formaté
    }

    /** @test */
    public function it_dispatches_events_on_payment(): void
    {
        $this->actingAs($this->user);

        Livewire::test(CashRegister::class)
            ->call('addToCart', $this->variant->id, 2)
            ->set('clientId', $this->client->id)
            ->set('cashReceived', 250.00)
            ->call('processPayment')
            ->assertDispatched('cart-cleared')
            ->assertDispatched('sale-completed')
            ->assertDispatched('payment-received');
    }
}
