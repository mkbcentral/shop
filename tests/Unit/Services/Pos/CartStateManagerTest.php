<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Pos;

use App\Services\Pos\CartService;
use App\Services\Pos\CartStateManager;
use PHPUnit\Framework\TestCase;
use Mockery;

/**
 * Tests unitaires pour CartStateManager
 * 
 * Pour exécuter : php artisan test --filter=CartStateManagerTest
 */
class CartStateManagerTest extends TestCase
{
    private CartStateManager $manager;
    private $mockCartService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockCartService = Mockery::mock(CartService::class);
        $this->manager = new CartStateManager($this->mockCartService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_initializes_cart_state(): void
    {
        $cart = ['variant_1' => ['quantity' => 2]];
        
        $this->mockCartService
            ->shouldReceive('initialize')
            ->once()
            ->with($cart);

        $this->manager->initialize($cart);
        
        $this->assertEquals($cart, $this->manager->getCart());
    }

    /** @test */
    public function it_adds_item_to_cart(): void
    {
        $variantId = 1;
        $expectedResult = [
            'success' => true,
            'message' => 'Produit ajouté',
            'cart' => ['variant_1' => ['quantity' => 1]]
        ];

        $this->mockCartService
            ->shouldReceive('initialize')
            ->once();

        $this->mockCartService
            ->shouldReceive('addItem')
            ->once()
            ->with($variantId)
            ->andReturn($expectedResult);

        $result = $this->manager->addItem($variantId);

        $this->assertTrue($result['success']);
        $this->assertEquals('Produit ajouté', $result['message']);
    }

    /** @test */
    public function it_updates_item_quantity(): void
    {
        $key = 'variant_1';
        $quantity = 5;
        
        $this->mockCartService
            ->shouldReceive('initialize')
            ->once();

        $this->mockCartService
            ->shouldReceive('updateQuantity')
            ->once()
            ->with($key, $quantity)
            ->andReturn([
                'success' => true,
                'cart' => ['variant_1' => ['quantity' => 5]]
            ]);

        $result = $this->manager->updateQuantity($key, $quantity);

        $this->assertTrue($result['success']);
    }

    /** @test */
    public function it_removes_item_from_cart(): void
    {
        $key = 'variant_1';
        
        $this->mockCartService
            ->shouldReceive('initialize')
            ->once();

        $this->mockCartService
            ->shouldReceive('removeItem')
            ->once()
            ->with($key)
            ->andReturn([
                'success' => true,
                'cart' => []
            ]);

        $result = $this->manager->removeItem($key);

        $this->assertTrue($result['success']);
        $this->assertEmpty($result['cart']);
    }

    /** @test */
    public function it_clears_cart(): void
    {
        $this->mockCartService
            ->shouldReceive('initialize')
            ->once();

        $this->mockCartService
            ->shouldReceive('clear')
            ->once()
            ->andReturn([
                'success' => true,
                'cart' => []
            ]);

        $result = $this->manager->clear();

        $this->assertTrue($result['success']);
        $this->assertEmpty($result['cart']);
    }

    /** @test */
    public function it_checks_if_cart_is_empty(): void
    {
        $this->mockCartService
            ->shouldReceive('initialize')
            ->once();

        $this->mockCartService
            ->shouldReceive('isEmpty')
            ->once()
            ->andReturn(true);

        $isEmpty = $this->manager->isEmpty();

        $this->assertTrue($isEmpty);
    }

    /** @test */
    public function it_counts_cart_items(): void
    {
        $cart = [
            'variant_1' => ['quantity' => 2],
            'variant_2' => ['quantity' => 3],
        ];
        
        $this->manager->initialize($cart);

        $count = $this->manager->count();

        $this->assertEquals(2, $count);
    }

    /** @test */
    public function it_validates_stock_before_payment(): void
    {
        $this->mockCartService
            ->shouldReceive('initialize')
            ->once();

        $this->mockCartService
            ->shouldReceive('validateStock')
            ->once()
            ->andReturn([
                'valid' => true
            ]);

        $validation = $this->manager->validateStock();

        $this->assertTrue($validation['valid']);
    }

    /** @test */
    public function it_finds_variant_by_barcode(): void
    {
        $barcode = '1234567890';
        $expectedVariantId = 42;

        $this->mockCartService
            ->shouldReceive('findByBarcode')
            ->once()
            ->with($barcode)
            ->andReturn($expectedVariantId);

        $variantId = $this->manager->findByBarcode($barcode);

        $this->assertEquals($expectedVariantId, $variantId);
    }
}
