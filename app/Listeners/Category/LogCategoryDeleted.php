<?php

namespace App\Listeners\Category;

use App\Events\Category\CategoryDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogCategoryDeleted implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(CategoryDeleted $event): void
    {
        Log::info('Category deleted via event listener', [
            'category_id' => $event->categoryId,
            'category_name' => $event->categoryName,
        ]);
    }
}
