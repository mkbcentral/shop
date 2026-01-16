<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Pos;

use App\Services\Pos\StatsService;
use App\Models\Sale;
use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class StatsServiceTest extends TestCase
{
    use RefreshDatabase;

    private StatsService $statsService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->statsService = new StatsService();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_loads_today_stats_for_user(): void
    {
        // Créer des ventes pour aujourd'hui
        Sale::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'total' => 1000,
            'created_at' => now(),
        ]);

        $stats = $this->statsService->loadTodayStats($this->user->id);

        $this->assertEquals(3, $stats['sales_count']);
        $this->assertEquals(3000, $stats['revenue']);
        $this->assertEquals(3, $stats['transactions']);
        $this->assertEquals(1000, $stats['average_sale']);
    }

    /** @test */
    public function it_returns_zero_stats_when_no_sales(): void
    {
        $stats = $this->statsService->loadTodayStats($this->user->id);

        $this->assertEquals(0, $stats['sales_count']);
        $this->assertEquals(0, $stats['revenue']);
        $this->assertEquals(0, $stats['transactions']);
        $this->assertEquals(0, $stats['average_sale']);
    }

    /** @test */
    public function it_only_counts_completed_sales(): void
    {
        // Vente complétée
        Sale::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'total' => 1000,
            'created_at' => now(),
        ]);

        // Vente en attente (ne doit pas être comptée)
        Sale::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
            'total' => 2000,
            'created_at' => now(),
        ]);

        $stats = $this->statsService->loadTodayStats($this->user->id);

        $this->assertEquals(1, $stats['sales_count']);
        $this->assertEquals(1000, $stats['revenue']);
    }

    /** @test */
    public function it_only_counts_todays_sales(): void
    {
        // Vente d'aujourd'hui
        Sale::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'total' => 1000,
            'created_at' => now(),
        ]);

        // Vente d'hier (ne doit pas être comptée)
        Sale::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'total' => 2000,
            'created_at' => now()->subDay(),
        ]);

        $stats = $this->statsService->loadTodayStats($this->user->id);

        $this->assertEquals(1, $stats['sales_count']);
        $this->assertEquals(1000, $stats['revenue']);
    }

    /** @test */
    public function it_only_counts_sales_for_specific_user(): void
    {
        $otherUser = User::factory()->create();

        // Vente de l'utilisateur test
        Sale::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'total' => 1000,
            'created_at' => now(),
        ]);

        // Vente d'un autre utilisateur
        Sale::factory()->create([
            'user_id' => $otherUser->id,
            'status' => 'completed',
            'total' => 2000,
            'created_at' => now(),
        ]);

        $stats = $this->statsService->loadTodayStats($this->user->id);

        $this->assertEquals(1, $stats['sales_count']);
        $this->assertEquals(1000, $stats['revenue']);
    }

    /** @test */
    public function it_caches_stats_for_performance(): void
    {
        Sale::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'total' => 1000,
            'created_at' => now(),
        ]);

        // Premier appel - devrait mettre en cache
        $stats1 = $this->statsService->loadTodayStats($this->user->id);

        // Créer une nouvelle vente
        Sale::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'total' => 2000,
            'created_at' => now(),
        ]);

        // Deuxième appel - devrait retourner le cache (ancienne valeur)
        $stats2 = $this->statsService->loadTodayStats($this->user->id);

        $this->assertEquals($stats1, $stats2);
        $this->assertEquals(1, $stats2['sales_count']); // Pas 2, car le cache est utilisé
    }

    /** @test */
    public function it_invalidates_cache_on_demand(): void
    {
        Sale::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'total' => 1000,
            'created_at' => now(),
        ]);

        // Charger et mettre en cache
        $this->statsService->loadTodayStats($this->user->id);

        // Créer une nouvelle vente
        Sale::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'total' => 2000,
            'created_at' => now(),
        ]);

        // Invalider le cache
        $this->statsService->invalidateStatsCache($this->user->id);

        // Recharger - devrait avoir les nouvelles données
        $stats = $this->statsService->loadTodayStats($this->user->id);

        $this->assertEquals(2, $stats['sales_count']);
        $this->assertEquals(3000, $stats['revenue']);
    }

    /** @test */
    public function it_loads_transaction_history(): void
    {
        $client = Client::factory()->create(['name' => 'Test Client']);
        
        $sale1 = Sale::factory()->create([
            'user_id' => $this->user->id,
            'client_id' => $client->id,
            'status' => 'completed',
            'total' => 1000,
            'payment_method' => 'cash',
            'created_at' => now()->subMinutes(10),
        ]);
        Invoice::factory()->create(['sale_id' => $sale1->id, 'invoice_number' => 'INV-001']);

        $sale2 = Sale::factory()->create([
            'user_id' => $this->user->id,
            'client_id' => $client->id,
            'status' => 'completed',
            'total' => 2000,
            'payment_method' => 'card',
            'created_at' => now()->subMinutes(5),
        ]);
        Invoice::factory()->create(['sale_id' => $sale2->id, 'invoice_number' => 'INV-002']);

        $history = $this->statsService->loadTransactionHistory($this->user->id, 10);

        $this->assertCount(2, $history);
        $this->assertEquals('INV-002', $history[0]['invoice_number']); // Plus récente en premier
        $this->assertEquals('INV-001', $history[1]['invoice_number']);
        $this->assertEquals('Test Client', $history[0]['client']);
    }

    /** @test */
    public function it_limits_transaction_history(): void
    {
        $client = Client::factory()->create();
        
        // Créer 15 ventes
        for ($i = 0; $i < 15; $i++) {
            $sale = Sale::factory()->create([
                'user_id' => $this->user->id,
                'client_id' => $client->id,
                'status' => 'completed',
                'created_at' => now()->subMinutes(15 - $i),
            ]);
            Invoice::factory()->create(['sale_id' => $sale->id]);
        }

        // Demander seulement 5
        $history = $this->statsService->loadTransactionHistory($this->user->id, 5);

        $this->assertCount(5, $history);
    }

    /** @test */
    public function it_shows_comptant_for_null_client(): void
    {
        $sale = Sale::factory()->create([
            'user_id' => $this->user->id,
            'client_id' => null,
            'status' => 'completed',
            'created_at' => now(),
        ]);
        Invoice::factory()->create(['sale_id' => $sale->id]);

        $history = $this->statsService->loadTransactionHistory($this->user->id);

        $this->assertEquals('Comptant', $history[0]['client']);
    }

    /** @test */
    public function it_counts_payment_methods(): void
    {
        Sale::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'payment_method' => 'cash',
            'created_at' => now(),
        ]);

        Sale::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'payment_method' => 'cash',
            'created_at' => now(),
        ]);

        Sale::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'payment_method' => 'card',
            'created_at' => now(),
        ]);

        $stats = $this->statsService->loadTodayStats($this->user->id);

        $this->assertEquals(2, $stats['cash_sales']);
        $this->assertEquals(1, $stats['card_sales']);
    }
}
