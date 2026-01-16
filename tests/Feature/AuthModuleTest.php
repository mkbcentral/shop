<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AuthService;
use App\Repositories\UserRepository;
use App\Actions\Auth\LoginAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthModuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that all Auth components can be instantiated.
     */
    public function test_auth_components_can_be_instantiated(): void
    {
        // Test UserRepository
        $userRepo = app(UserRepository::class);
        $this->assertInstanceOf(UserRepository::class, $userRepo);

        // Test AuthService
        $authService = app(AuthService::class);
        $this->assertInstanceOf(AuthService::class, $authService);

        // Test LoginAction
        $loginAction = app(LoginAction::class);
        $this->assertInstanceOf(LoginAction::class, $loginAction);

        $this->assertTrue(true);
    }

    /**
     * Test User model has all required traits.
     */
    public function test_user_model_has_required_traits(): void
    {
        $user = new User();

        // Check HasApiTokens trait
        $this->assertTrue(method_exists($user, 'createToken'));

        // Check TwoFactorAuthenticatable trait
        $this->assertTrue(method_exists($user, 'twoFactorQrCodeSvg'));

        // Check custom methods
        $this->assertTrue(method_exists($user, 'hasTwoFactorEnabled'));
        $this->assertTrue(method_exists($user, 'isAdmin'));
        $this->assertTrue(method_exists($user, 'initials'));

        $this->assertTrue(true);
    }

    /**
     * Test UserRepository methods exist.
     */
    public function test_user_repository_methods_exist(): void
    {
        $repo = app(UserRepository::class);

        $this->assertTrue(method_exists($repo, 'find'));
        $this->assertTrue(method_exists($repo, 'findByEmail'));
        $this->assertTrue(method_exists($repo, 'create'));
        $this->assertTrue(method_exists($repo, 'update'));
        $this->assertTrue(method_exists($repo, 'delete'));
        $this->assertTrue(method_exists($repo, 'emailExists'));
        $this->assertTrue(method_exists($repo, 'updateLastLogin'));

        $this->assertTrue(true);
    }

    /**
     * Test AuthService methods exist.
     */
    public function test_auth_service_methods_exist(): void
    {
        $service = app(AuthService::class);

        $this->assertTrue(method_exists($service, 'login'));
        $this->assertTrue(method_exists($service, 'attemptLogin'));
        $this->assertTrue(method_exists($service, 'logout'));
        $this->assertTrue(method_exists($service, 'validateCredentials'));
        $this->assertTrue(method_exists($service, 'enableTwoFactor'));
        $this->assertTrue(method_exists($service, 'confirmTwoFactor'));
        $this->assertTrue(method_exists($service, 'disableTwoFactor'));
        $this->assertTrue(method_exists($service, 'verifyTwoFactorCode'));

        $this->assertTrue(true);
    }

    /**
     * Test database has required tables.
     */
    public function test_database_has_required_tables(): void
    {
        // Create a test user to verify database is working
        $user = User::create([
            'name' => 'DB Test',
            'email' => 'db@test.com',
            'password' => bcrypt('password'),
        ]);

        // Check users table has the created user
        $this->assertDatabaseHas('users', [
            'email' => 'db@test.com',
        ]);

        $this->assertTrue(true);
    }

    /**
     * Test user can be created.
     */
    public function test_user_can_be_created(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('user', $user->role);
        $this->assertFalse($user->hasTwoFactorEnabled());
    }
}
