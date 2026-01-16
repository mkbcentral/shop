<?php

namespace App\Observers\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

/**
 * Category Observer
 *
 * Optional observer for additional model lifecycle hooks.
 * To activate, register in EventServiceProvider:
 * Category::observe(CategoryObserver::class);
 */
class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        // Clear category cache
        $this->clearCache();
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        // Clear category cache
        $this->clearCache();
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        // Clear category cache
        $this->clearCache();
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        // Clear category cache if using soft deletes
        $this->clearCache();
    }

    /**
     * Clear all category-related cache.
     */
    private function clearCache(): void
    {
        Cache::forget('categories.all');
        Cache::forget('categories.popular');
        Cache::tags(['categories'])->flush();
    }
}
