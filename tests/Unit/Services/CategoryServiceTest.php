<?php

namespace Tests\Unit\Services;

use App\Dtos\Category\CreateCategoryDto;
use App\Dtos\Category\UpdateCategoryDto;
use App\Events\Category\CategoryCreated;
use App\Events\Category\CategoryDeleted;
use App\Events\Category\CategoryUpdated;
use App\Exceptions\Category\CategoryHasProductsException;
use App\Exceptions\Category\CategoryNotFoundException;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use App\Services\SlugGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private CategoryService $categoryService;
    private CategoryRepository $categoryRepository;
    private SlugGeneratorService $slugGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->categoryRepository = new CategoryRepository();
        $this->slugGenerator = new SlugGeneratorService();
        $this->categoryService = new CategoryService(
            $this->categoryRepository,
            $this->slugGenerator
        );
    }

    /** @test */
    public function it_can_create_a_category_with_dto()
    {
        Event::fake();

        $dto = new CreateCategoryDto(
            name: 'Test Category',
            description: 'Test Description'
        );

        $category = $this->categoryService->createCategory($dto);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Test Category', $category->name);
        $this->assertEquals('Test Description', $category->description);
        $this->assertNotNull($category->slug);

        Event::assertDispatched(CategoryCreated::class);
    }

    /** @test */
    public function it_can_create_a_category_with_array()
    {
        Event::fake();

        $data = [
            'name' => 'Test Category',
            'description' => 'Test Description'
        ];

        $category = $this->categoryService->createCategory($data);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Test Category', $category->name);

        Event::assertDispatched(CategoryCreated::class);
    }

    /** @test */
    public function it_generates_unique_slug_when_creating_category()
    {
        // Create first category
        Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category'
        ]);

        $dto = new CreateCategoryDto(name: 'Test Category');
        $category = $this->categoryService->createCategory($dto);

        $this->assertEquals('test-category-1', $category->slug);
    }

    /** @test */
    public function it_can_update_a_category_with_dto()
    {
        Event::fake();

        $category = Category::create([
            'name' => 'Original Name',
            'slug' => 'original-name'
        ]);

        $dto = new UpdateCategoryDto(
            name: 'Updated Name',
            description: 'Updated Description'
        );

        $updatedCategory = $this->categoryService->updateCategory($category->id, $dto);

        $this->assertEquals('Updated Name', $updatedCategory->name);
        $this->assertEquals('Updated Description', $updatedCategory->description);
        $this->assertEquals('updated-name', $updatedCategory->slug);

        Event::assertDispatched(CategoryUpdated::class);
    }

    /** @test */
    public function it_throws_exception_when_updating_non_existent_category()
    {
        $this->expectException(CategoryNotFoundException::class);

        $dto = new UpdateCategoryDto(name: 'Test');
        $this->categoryService->updateCategory(999, $dto);
    }

    /** @test */
    public function it_can_delete_a_category_without_products()
    {
        Event::fake();

        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category'
        ]);

        $categoryId = $category->id;
        $result = $this->categoryService->deleteCategory($categoryId);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('categories', ['id' => $categoryId]);

        Event::assertDispatched(CategoryDeleted::class);
    }

    /** @test */
    public function it_throws_exception_when_deleting_category_with_products()
    {
        $this->expectException(CategoryHasProductsException::class);

        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category'
        ]);

        // Create a product in this category
        Product::factory()->create(['category_id' => $category->id]);

        $this->categoryService->deleteCategory($category->id);
    }

    /** @test */
    public function it_throws_exception_when_deleting_non_existent_category()
    {
        $this->expectException(CategoryNotFoundException::class);

        $this->categoryService->deleteCategory(999);
    }
}
