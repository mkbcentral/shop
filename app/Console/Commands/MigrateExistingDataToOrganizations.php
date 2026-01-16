<?php

namespace App\Console\Commands;

use App\Models\Organization;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\StockMovement;
use App\Models\StoreTransfer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateExistingDataToOrganizations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'organization:migrate-existing-data
                            {--organization_id= : ID of the organization to migrate data to}
                            {--create-default : Create a default organization if none exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing data to a specified organization';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting data migration to organizations...');

        // Get or create organization
        $organizationId = $this->option('organization_id');

        if (!$organizationId && $this->option('create-default')) {
            $organization = $this->createDefaultOrganization();
            $organizationId = $organization->id;
        } elseif (!$organizationId) {
            $organizations = Organization::all();

            if ($organizations->isEmpty()) {
                $this->error('No organizations found. Use --create-default to create one.');
                return 1;
            }

            $choices = $organizations->mapWithKeys(function ($org) {
                return [$org->id => $org->name];
            })->toArray();

            $organizationId = $this->choice(
                'Select an organization to migrate data to:',
                $choices,
                0
            );
        }

        $organization = Organization::find($organizationId);

        if (!$organization) {
            $this->error("Organization with ID {$organizationId} not found.");
            return 1;
        }

        $this->info("Migrating data to organization: {$organization->name}");

        DB::beginTransaction();

        try {
            // Migrate Stores
            $this->migrateModel(Store::class, $organizationId, 'stores');

            // Migrate Products
            $this->migrateModel(Product::class, $organizationId, 'products');

            // Migrate Categories
            $this->migrateModel(Category::class, $organizationId, 'categories');

            // Migrate Product Variants
            $this->migrateModel(ProductVariant::class, $organizationId, 'product_variants');

            // Migrate Clients
            $this->migrateModel(Client::class, $organizationId, 'clients');

            // Migrate Suppliers
            $this->migrateModel(Supplier::class, $organizationId, 'suppliers');

            // Migrate Sales
            $this->migrateModel(Sale::class, $organizationId, 'sales');

            // Migrate Purchases
            $this->migrateModel(Purchase::class, $organizationId, 'purchases');

            // Migrate Invoices
            $this->migrateModel(Invoice::class, $organizationId, 'invoices');

            // Migrate Payments
            $this->migrateModel(Payment::class, $organizationId, 'payments');

            // Migrate Stock Movements
            $this->migrateModel(StockMovement::class, $organizationId, 'stock_movements');

            // Migrate Store Transfers
            $this->migrateModel(StoreTransfer::class, $organizationId, 'store_transfers');

            // Associate all users with this organization
            $this->migrateUsers($organization);

            DB::commit();

            $this->info('✓ Data migration completed successfully!');
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Migration failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }

    /**
     * Migrate a model to the organization.
     */
    protected function migrateModel(string $modelClass, int $organizationId, string $tableName): void
    {
        $count = DB::table($tableName)
            ->whereNull('organization_id')
            ->update(['organization_id' => $organizationId]);

        if ($count > 0) {
            $this->info("✓ Migrated {$count} records in {$tableName}");
        } else {
            $this->line("  No records to migrate in {$tableName}");
        }
    }

    /**
     * Associate users with the organization.
     */
    protected function migrateUsers(Organization $organization): void
    {
        $users = User::whereDoesntHave('organizations')->get();

        foreach ($users as $user) {
            $organization->members()->attach($user->id, [
                'role' => 'admin',
                'accepted_at' => now(),
            ]);

            // Set as default organization if user doesn't have one
            if (!$user->default_organization_id) {
                $user->update(['default_organization_id' => $organization->id]);
            }
        }

        $this->info("✓ Associated {$users->count()} users with organization");
    }

    /**
     * Create a default organization.
     */
    protected function createDefaultOrganization(): Organization
    {
        // Get the first user as owner, or create a system user
        $owner = User::first();

        if (!$owner) {
            $this->error('No users found. Please create a user first.');
            exit(1);
        }

        $organization = Organization::create([
            'name' => 'Default Organization',
            'slug' => 'default-organization',
            'owner_id' => $owner->id,
            'subscription_plan' => 'free',
            'subscription_starts_at' => now(),
            'max_stores' => 1,
            'max_users' => 5,
            'max_products' => 100,
            'is_active' => true,
        ]);

        $this->info("✓ Created default organization: {$organization->name}");

        return $organization;
    }
}
