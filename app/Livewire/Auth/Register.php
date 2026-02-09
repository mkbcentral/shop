<?php

namespace App\Livewire\Auth;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionPlan;
use App\Models\Organization;
use App\Services\OrganizationService;
use App\Services\SubscriptionService;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Register extends Component
{
    public int $currentStep = 1;

    /**
     * Listen for step completion
     */
    #[On('step-completed')]
    public function handleStepCompleted(int $step)
    {
        if ($step === 1) {
            $this->currentStep = 2;
        } elseif ($step === 2) {
            $this->currentStep = 3;
        }
    }

    /**
     * Listen for back navigation
     */
    #[On('go-back')]
    public function handleGoBack(int $step)
    {
        $this->currentStep = $step;
    }

    /**
     * Listen for registration completion
     */
    #[On('complete-registration')]
    public function completeRegistration()
    {
        $this->register();
    }

    /**
     * Complete registration process
     */
    protected function register()
    {
        // Get all data from session
        $step1 = session('registration.step1', []);
        $step2 = session('registration.step2', []);

        DB::beginTransaction();

        try {
            // 1. Create the user
            $user = User::create([
                'name' => $step1['name'],
                'email' => $step1['email'],
                'password' => Hash::make($step1['password']),
            ]);

            // 2. Get the selected subscription plan from cache
            $planSlug = $step2['subscription_plan'];
            $allPlans = SubscriptionService::getPlansFromCache();
            $planData = $allPlans[$planSlug] ?? [];

            // Convert slug to enum for database storage
            $plan = SubscriptionPlan::from($planSlug);

            // 3. Determine payment status
            $isFree = ($planData['price'] ?? 0) == 0;
            $paymentStatus = $isFree
                ? PaymentStatus::COMPLETED
                : PaymentStatus::PENDING;

            // 4. Create the organization
            // Note: Les dates d'abonnement ne sont définies que pour les plans gratuits
            // Pour les plans payants, elles seront définies lors de la confirmation du paiement
            $organization = Organization::create([
                'name' => $step2['organization_name'],
                'phone' => $step2['organization_phone'],
                'slug' => Str::slug(title: $step2['organization_name']),
                'owner_id' => $user->id,
                'business_activity' => $step2['business_activity'] ?? 'retail',
                'subscription_plan' => $plan,
                'payment_status' => $paymentStatus,
                'subscription_starts_at' => $isFree ? now() : null,
                'subscription_ends_at' => $isFree ? now()->addMonth() : null,
                'is_trial' => false,
                'trial_days' => 0,
                'max_stores' => $planData['max_stores'] ?? 1,
                'max_users' => $planData['max_users'] ?? 3,
                'max_products' => $planData['max_products'] ?? 100,
                'currency' => 'CDF',
                'timezone' => config('app.timezone', 'UTC'),
                'is_active' => $isFree, // Active immediately for free plan
                'is_verified' => false,
            ]);

            // 5. Attach user to organization as owner
            $organization->members()->attach($user->id, [
                'role' => 'owner',
                'is_active' => true,
                'accepted_at' => now(),
            ]);

            // 6. Set as default organization for user
            $user->update([
                'default_organization_id' => $organization->id,
            ]);

            // 6b. Initialize product types and categories based on business activity
            $organizationService = app(OrganizationService::class);
            $organizationService->initializeProductTypesAndCategories($organization);

            // 7. Create default store for the organization
            // Use "Service Principal" for service-only businesses, "Magasin Principal" otherwise
            $isServiceOnly = ($step2['business_activity'] ?? 'retail') === 'services';
            $storeName = $isServiceOnly ? 'Service Principal' : 'Magasin Principal';
            $storeCode = $isServiceOnly ? 'SVC-' : 'MAIN-';

            $store = Store::create([
                'organization_id' => $organization->id,
                'name' => $storeName,
                'slug' => Str::slug($storeName . '-' . $organization->id),
                'code' => $storeCode . $organization->id,
                'address' => '',
                'city' => '',
                'country' => 'RD Congo',
                'phone' => $organization->phone,
                'email' => $organization->email ?? $user->email,
                'is_active' => true,
                'is_main' => true,
            ]);

            // 8. Attach the store to the user with owner role
            $user->stores()->attach($store->id, [
                'role' => 'admin',
                'is_default' => true,
            ]);

            // 9. Set as current store for user
            $user->update([
                'role' => 'admin',
                'current_store_id' => $store->id,
            ]);

            DB::commit();

            // 10. Authentifier l'utilisateur
            Auth::login($user);
            request()->session()->regenerate();

            // 11. Set organization and store in session
            session([
                'current_organization_id' => $organization->id,
                'current_store_id' => $store->id,
            ]);

            // 12. Envoyer l'email de vérification
            $user->sendEmailVerificationNotification();

            // Clear registration session data
            session()->forget(['registration.step1', 'registration.step2']);

            // 13. Flash message pour la page de vérification
            session()->flash('success', 'Votre compte a été créé avec succès ! Veuillez vérifier votre adresse email pour continuer.');

            // 14. Rediriger vers la vérification d'email avec Livewire
            // Utiliser redirectRoute() de Livewire pour une redirection fiable
            return $this->redirectRoute('verification.notice', navigate: false);

        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Une erreur est survenue lors de l\'inscription: ' . $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
