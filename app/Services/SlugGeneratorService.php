<?php

namespace App\Services;

use Illuminate\Support\Str;

class SlugGeneratorService
{
    /**
     * Generate a unique slug for the given text.
     *
     * @param string $text The text to generate slug from
     * @param callable $checkExistence Callback to check if slug exists: fn(string $slug): bool
     * @param int|null $excludeId Optional ID to exclude from uniqueness check
     * @return string
     */
    public function generate(string $text, callable $checkExistence, ?int $excludeId = null): string
    {
        $slug = Str::slug($text);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $exists = $checkExistence($slug);
            
            // If doesn't exist, or exists but it's the excluded entity, use this slug
            if (!$exists || ($excludeId && $exists->id === $excludeId)) {
                break;
            }

            // Generate next variation
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Generate slug without uniqueness check.
     *
     * @param string $text
     * @return string
     */
    public function generateSimple(string $text): string
    {
        return Str::slug($text);
    }
}
