<?php

namespace Tests\Unit\Actions\Category;

use App\Actions\Category\UpdateCategoryAction;
use App\Dtos\Category\UpdateCategoryDto;
use App\Exceptions\Category\CategoryNotFoundException;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCategoryActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_category_with_dto()
    {
        $category = Category::create([
            'name' => 'Original',
            'slug' => 'original'
        ]);

        $action = app(UpdateCategoryAction::class);

        $dto = new UpdateCategoryDto(
            name: 'Updated Name',
            description: 'Updated Description'
        );

        $updatedCategory = $action->execute($category->id, $dto);

        $this->assertEquals('Updated Name', $updatedCategory->name);
        $this->assertEquals('Updated Description', $updatedCategory->description);
    }

    /** @test */
    public function it_can_update_category_with_array()
    {
        $category = Category::create([
            'name' => 'Original',
            'slug' => 'original'
        ]);

        $action = app(UpdateCategoryAction::class);

        $data = ['name' => 'Updated'];
        $updatedCategory = $action->execute($category->id, $data);

        $this->assertEquals('Updated', $updatedCategory->name);
    }

    /** @test */
    public function it_throws_exception_for_non_existent_category()
    {
        $this->expectException(CategoryNotFoundException::class);

        $action = app(UpdateCategoryAction::class);
        $dto = new UpdateCategoryDto(name: 'Test');

        $action->execute(999, $dto);
    }

    /** @test */
    public function it_updates_slug_when_name_changes()
    {
        $category = Category::create([
            'name' => 'Original',
            'slug' => 'original'
        ]);

        $action = app(UpdateCategoryAction::class);

        $dto = new UpdateCategoryDto(name: 'New Name');
        $updatedCategory = $action->execute($category->id, $dto);

        $this->assertEquals('new-name', $updatedCategory->slug);
    }
}
