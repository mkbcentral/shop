<?php

namespace App\Console\Commands;

use App\Services\ProductService;
use Illuminate\Console\Command;

class CreateDefaultVariants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:create-default-variants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default variants for products that do not have any variants';

    /**
     * Execute the console command.
     */
    public function handle(ProductService $productService): int
    {
        $this->info('Creating default variants for products without variants...');

        $count = $productService->ensureAllProductsHaveVariants();

        if ($count > 0) {
            $this->info("✓ Created default variants for {$count} product(s).");
        } else {
            $this->info('All products already have at least one variant.');
        }

        return Command::SUCCESS;
    }
}
