<?php

namespace Tests\Unit\Services;

use App\Services\SlugGeneratorService;
use Tests\TestCase;

class SlugGeneratorServiceTest extends TestCase
{
    private SlugGeneratorService $slugGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->slugGenerator = new SlugGeneratorService();
    }

    /** @test */
    public function it_generates_simple_slug()
    {
        $slug = $this->slugGenerator->generateSimple('Test Category Name');

        $this->assertEquals('test-category-name', $slug);
    }

    /** @test */
    public function it_handles_special_characters()
    {
        $slug = $this->slugGenerator->generateSimple('Catégorie & Spéciale!');

        $this->assertEquals('categorie-speciale', $slug);
    }

    /** @test */
    public function it_generates_unique_slug()
    {
        $existingSlugs = ['test-category'];
        
        $checkExistence = function($slug) use ($existingSlugs) {
            return in_array($slug, $existingSlugs) ? (object)['id' => 1] : false;
        };

        $slug = $this->slugGenerator->generate('Test Category', $checkExistence);

        $this->assertEquals('test-category-1', $slug);
    }

    /** @test */
    public function it_allows_same_slug_for_excluded_id()
    {
        $existingSlugs = ['test-category' => 5];
        
        $checkExistence = function($slug) use ($existingSlugs) {
            return isset($existingSlugs[$slug]) 
                ? (object)['id' => $existingSlugs[$slug]] 
                : false;
        };

        // Excluding ID 5 should allow using 'test-category'
        $slug = $this->slugGenerator->generate('Test Category', $checkExistence, 5);

        $this->assertEquals('test-category', $slug);
    }

    /** @test */
    public function it_increments_counter_for_multiple_conflicts()
    {
        $existingSlugs = ['test-category', 'test-category-1', 'test-category-2'];
        
        $checkExistence = function($slug) use ($existingSlugs) {
            return in_array($slug, $existingSlugs) ? (object)['id' => 1] : false;
        };

        $slug = $this->slugGenerator->generate('Test Category', $checkExistence);

        $this->assertEquals('test-category-3', $slug);
    }
}
