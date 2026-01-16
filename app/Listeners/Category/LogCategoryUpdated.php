<?php

namespace App\Listeners\Category;

use App\Events\Category\CategoryUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogCategoryUpdated implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(CategoryUpdated $event): void
    {
        Log::info('Category updated via event listener', [
            'category_id' => $event->category->id,
            'category_name' => $event->category->name,
            'changed_attributes' => $event->changedAttributes,
        ]);
    }
}
