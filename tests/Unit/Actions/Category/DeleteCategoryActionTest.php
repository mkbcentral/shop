<?php

namespace Tests\Unit\Actions\Category;

use App\Actions\Category\DeleteCategoryAction;
use App\Exceptions\Category\CategoryHasProductsException;
use App\Exceptions\Category\CategoryNotFoundException;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteCategoryActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_category_without_products()
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test'
        ]);

        $action = app(DeleteCategoryAction::class);
        $result = $action->execute($category->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_throws_exception_when_deleting_non_existent_category()
    {
        $this->expectException(CategoryNotFoundException::class);

        $action = app(DeleteCategoryAction::class);
        $action->execute(999);
    }

    /** @test */
    public function it_throws_exception_when_category_has_products()
    {
        $this->expectException(CategoryHasProductsException::class);

        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test'
        ]);

        Product::factory()->create(['category_id' => $category->id]);

        $action = app(DeleteCategoryAction::class);
        $action->execute($category->id);
    }
}
