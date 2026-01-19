<?php

namespace App\Services;

use App\Dtos\Auth\LoginDto;
use App\Dtos\Auth\AuthResponseDto;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Authenticate user with credentials.
     */
    public function login(LoginDto $loginDto): AuthResponseDto
    {
        // Check rate limiting
        $this->ensureIsNotRateLimited($loginDto->email);

        // Find user by email
        $user = $this->userRepository->findByEmail($loginDto->email);

        // Validate credentials
        if (!$user || !Hash::check($loginDto->password, $user->password)) {
            $this->hitRateLimit($loginDto->email);

            return AuthResponseDto::failure('Les informations d\'identification fournies sont incorrectes.');
        }

        // Clear rate limiter
        RateLimiter::clear($this->throttleKey($loginDto->email));

        // Update last login
        $this->userRepository->updateLastLogin($user);

        // Create token (for API authentication)
        $token = $user->createToken('auth-token')->plainTextToken;

        return AuthResponseDto::success(
            token: $token,
            user: $this->formatUserData($user)
        );
    }

    /**
     * Attempt to authenticate user with web guard.
     */
    public function attemptLogin(LoginDto $loginDto): bool
    {
        // Check rate limiting
        $this->ensureIsNotRateLimited($loginDto->email);

        // Attempt authentication
        if (Auth::attempt($loginDto->credentials(), $loginDto->remember)) {
            // Clear rate limiter
            RateLimiter::clear($this->throttleKey($loginDto->email));

            // Update last login
            if ($user = Auth::user()) {
                $this->userRepository->updateLastLogin($user);
            }

            return true;
        }

        // Hit rate limiter
        $this->hitRateLimit($loginDto->email);

        return false;
    }

    /**
     * Logout user.
     */
    public function logout(): void
    {
        if ($user = Auth::user()) {
            // Revoke all tokens
            $user->tokens()->delete();
        }

        Auth::logout();
    }

    /**
     * Logout and revoke current token only.
     */
    public function logoutCurrentDevice(): void
    {
        if ($user = Auth::user()) {
            // Revoke current token only
            $user->currentAccessToken()?->delete();
        }
    }

    /**
     * Validate user credentials without logging in.
     */
    public function validateCredentials(string $email, string $password): bool
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return false;
        }

        return Hash::check($password, $user->password);
    }

    /**
     * Get authenticated user.
     */
    public function getAuthenticatedUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Check if user is authenticated.
     */
    public function isAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Enable two-factor authentication for user.
     */
    public function enableTwoFactor(User $user): array
    {
        $user->forceFill([
            'two_factor_secret' => encrypt(app(\PragmaRX\Google2FA\Google2FA::class)->generateSecretKey()),
        ])->save();

        return [
            'qr_code' => $user->twoFactorQrCodeSvg(),
            'recovery_codes' => $user->recoveryCodes(),
        ];
    }

    /**
     * Confirm two-factor authentication for user.
     */
    public function confirmTwoFactor(User $user, string $code): bool
    {
        $google2fa = app(\PragmaRX\Google2FA\Google2FA::class);

        if ($google2fa->verifyKey(decrypt($user->two_factor_secret), $code)) {
            $user->forceFill([
                'two_factor_confirmed_at' => now(),
            ])->save();

            $user->replaceRecoveryCodes();

            return true;
        }

        return false;
    }

    /**
     * Disable two-factor authentication for user.
     */
    public function disableTwoFactor(User $user): bool
    {
        return $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    /**
     * Verify two-factor code.
     */
    public function verifyTwoFactorCode(User $user, string $code): bool
    {
        if (!$user->two_factor_secret) {
            return false;
        }

        $google2fa = app(\PragmaRX\Google2FA\Google2FA::class);

        return $google2fa->verifyKey(decrypt($user->two_factor_secret), $code);
    }

    /**
     * Verify recovery code.
     */
    public function verifyRecoveryCode(User $user, string $code): bool
    {
        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        if (!in_array($code, $recoveryCodes)) {
            return false;
        }

        // Remove used recovery code
        $recoveryCodes = array_diff($recoveryCodes, [$code]);

        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(array_values($recoveryCodes))),
        ])->save();

        return true;
    }

    /**
     * Format user data for response.
     */
    private function formatUserData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'initials' => $user->initials(),
            'email_verified_at' => $user->email_verified_at?->format('Y-m-d H:i:s'),
            'two_factor_enabled' => $user->hasTwoFactorEnabled(),
            'last_login_at' => $user->last_login_at?->format('Y-m-d H:i:s'),
            'current_store' => $user->currentStore ? [
                'id' => $user->currentStore->id,
                'name' => $user->currentStore->name,
                'code' => $user->currentStore->code,
            ] : null,
            'available_stores' => $this->getAvailableStores($user),
        ];
    }

    /**
     * Récupérer les magasins disponibles pour l'utilisateur.
     */
    private function getAvailableStores(User $user): array
    {
        // Super-admin n'a pas de stores ni d'organisation
        if ($user->hasRole('super-admin')) {
            return [];
        }

        $currentOrganization = $user->defaultOrganization;

        // Admin peut accéder à tous les magasins de l'organisation
        if ($user->isAdmin()) {
            $query = \App\Models\Store::query()
                ->where('is_active', true)
                ->orderBy('name');

            if ($currentOrganization) {
                $query->where('organization_id', $currentOrganization->id);
            }

            return $query->get(['id', 'name', 'code', 'address', 'phone'])->toArray();
        }

        // Utilisateurs réguliers ne peuvent accéder qu'à leurs magasins assignés
        $query = $user->stores()
            ->where('is_active', true)
            ->orderBy('name');

        if ($currentOrganization) {
            $query->where('organization_id', $currentOrganization->id);
        }

        return $query->get(['stores.id', 'stores.name', 'stores.code', 'stores.address', 'stores.phone'])->toArray();
    }

    /**
     * Ensure the login request is not rate limited.
     */
    private function ensureIsNotRateLimited(string $email): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($email), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($email));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Increment the login attempts for the user.
     */
    private function hitRateLimit(string $email): void
    {
        RateLimiter::hit($this->throttleKey($email), 60);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    private function throttleKey(string $email): string
    {
        return Str::transliterate(Str::lower($email) . '|' . request()->ip());
    }
}
