<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Services\AuthService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
use Laravel\Fortify\Contracts\LogoutResponse;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegisterResponse;
use App\Http\Responses\VerifyEmailResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Enregistrer les responses personnalisées pour gérer le flux email + paiement
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);
        $this->app->singleton(VerifyEmailResponseContract::class, VerifyEmailResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Fortify actions
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Customize authentication logic using our AuthService
        Fortify::authenticateUsing(function (Request $request) {
            $authService = app(AuthService::class);

            return $authService->validateCredentials(
                $request->input(Fortify::username()),
                $request->input('password')
            ) ? $authService->getAuthenticatedUser() : null;
        });

        // Configure rate limiting
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Customize login view
        Fortify::loginView(function () {
            return view('auth.login-root');
        });

        // Customize register view
        Fortify::registerView(function () {
            return view('auth.register-root');
        });

        // Customize password reset views
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password-root');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.reset-password-root', ['request' => $request]);
        });

        // Customize two-factor views
        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge-root');
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password-root');
        });

        // Customize email verification view
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email-root');
        });
    }
}
