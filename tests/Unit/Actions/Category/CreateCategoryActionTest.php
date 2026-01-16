<?php

namespace Tests\Unit\Actions\Category;

use App\Actions\Category\CreateCategoryAction;
use App\Dtos\Category\CreateCategoryDto;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateCategoryActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_category_with_dto()
    {
        $action = app(CreateCategoryAction::class);

        $dto = new CreateCategoryDto(
            name: 'Electronics',
            description: 'Electronic devices'
        );

        $category = $action->execute($dto);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Electronics', $category->name);
        $this->assertEquals('Electronic devices', $category->description);
        $this->assertDatabaseHas('categories', [
            'name' => 'Electronics'
        ]);
    }

    /** @test */
    public function it_can_create_category_with_array()
    {
        $action = app(CreateCategoryAction::class);

        $data = [
            'name' => 'Books',
            'description' => 'Book collection'
        ];

        $category = $action->execute($data);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Books', $category->name);
    }

    /** @test */
    public function it_auto_generates_slug()
    {
        $action = app(CreateCategoryAction::class);

        $dto = new CreateCategoryDto(name: 'Test Category Name');
        $category = $action->execute($dto);

        $this->assertEquals('test-category-name', $category->slug);
    }
}
