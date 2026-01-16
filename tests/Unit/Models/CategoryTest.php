<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_products_relationship()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category'
        ]);

        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertTrue($category->products->contains($product));
    }

    /** @test */
    public function it_auto_generates_slug_on_creation()
    {
        $category = Category::create([
            'name' => 'Test Category Name'
        ]);

        $this->assertEquals('test-category-name', $category->slug);
    }

    /** @test */
    public function it_can_check_if_has_products()
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test'
        ]);

        $this->assertFalse($category->hasProducts());

        Product::factory()->create(['category_id' => $category->id]);

        $this->assertTrue($category->fresh()->hasProducts());
    }

    /** @test */
    public function it_can_check_if_can_be_deleted()
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test'
        ]);

        $this->assertTrue($category->canBeDeleted());

        Product::factory()->create(['category_id' => $category->id]);

        $this->assertFalse($category->fresh()->canBeDeleted());
    }

    /** @test */
    public function it_can_get_products_count()
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test'
        ]);

        $this->assertEquals(0, $category->getProductsCount());

        Product::factory()->count(3)->create(['category_id' => $category->id]);

        $this->assertEquals(3, $category->fresh()->getProductsCount());
    }

    /** @test */
    public function it_has_formatted_name_accessor()
    {
        $category = Category::create([
            'name' => 'test category',
            'slug' => 'test-category'
        ]);

        $this->assertEquals('Test category', $category->formatted_name);
    }

    /** @test */
    public function it_has_short_description_accessor()
    {
        $longDescription = str_repeat('a', 150);

        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test',
            'description' => $longDescription
        ]);

        $this->assertLessThan(strlen($longDescription), strlen($category->short_description));
        $this->assertStringEndsWith('...', $category->short_description);
    }

    /** @test */
    public function it_can_scope_search()
    {
        Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
        Category::create(['name' => 'Books', 'slug' => 'books']);
        Category::create(['name' => 'Clothing', 'slug' => 'clothing']);

        $results = Category::search('elec')->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Electronics', $results->first()->name);
    }

    /** @test */
    public function it_can_scope_with_products()
    {
        $categoryWithProducts = Category::create(['name' => 'Has Products', 'slug' => 'has']);
        $categoryWithoutProducts = Category::create(['name' => 'No Products', 'slug' => 'no']);

        Product::factory()->create(['category_id' => $categoryWithProducts->id]);

        $results = Category::withProducts()->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Has Products', $results->first()->name);
    }

    /** @test */
    public function it_can_scope_without_products()
    {
        $categoryWithProducts = Category::create(['name' => 'Has Products', 'slug' => 'has']);
        $categoryWithoutProducts = Category::create(['name' => 'No Products', 'slug' => 'no']);

        Product::factory()->create(['category_id' => $categoryWithProducts->id]);

        $results = Category::withoutProducts()->get();

        $this->assertCount(1, $results);
        $this->assertEquals('No Products', $results->first()->name);
    }

    /** @test */
    public function it_trims_name_on_set()
    {
        $category = Category::create([
            'name' => '  Test Category  ',
            'slug' => 'test'
        ]);

        $this->assertEquals('Test Category', $category->name);
    }

    /** @test */
    public function it_trims_description_on_set()
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test',
            'description' => '  Test Description  '
        ]);

        $this->assertEquals('Test Description', $category->description);
    }
}
