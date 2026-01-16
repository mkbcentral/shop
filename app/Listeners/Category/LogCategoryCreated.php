<?php

namespace App\Listeners\Category;

use App\Events\Category\CategoryCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogCategoryCreated implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(CategoryCreated $event): void
    {
        Log::info('Category created via event listener', [
            'category_id' => $event->category->id,
            'category_name' => $event->category->name,
            'slug' => $event->category->slug,
        ]);
    }
}
