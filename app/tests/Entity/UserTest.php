<?php
// (c) 2025 zos
namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Enum\UserRole;
use PHPUnit\Framework\TestCase;

/**
 * User test class.
 */
class UserTest extends TestCase
{
    /**
     * Test default values.
     *
     * @return void
     */
    public function testDefaultValues(): void
    {
        $user = new User();

        $this->assertNull($user->getId());
        $this->assertNull($user->getEmail());
        $this->assertNull($user->getPassword());

        $this->assertContains(UserRole::ROLE_USER->value, $user->getRoles());
    }

    /**
     * Test email.
     *
     * @return void
     */
    public function testEmail(): void
    {
        $user = new User();
        $email = 'test@example.com';

        $user->setEmail($email);
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($email, $user->getUserIdentifier());
    }

    /**
     * Test password.
     *
     * @return void
     */
    public function testPassword(): void
    {
        $user = new User();
        $password = 'hashed_password';

        $user->setPassword($password);
        $this->assertSame($password, $user->getPassword());
    }

    /**
     * Test roles.
     *
     * @return void
     */
    public function testRoles(): void
    {
        $user = new User();

        $user->setRoles([UserRole::ROLE_ADMIN->value]);

        $roles = $user->getRoles();

        $this->assertContains(UserRole::ROLE_ADMIN->value, $roles);
        $this->assertContains(UserRole::ROLE_USER->value, $roles);
        $this->assertCount(2, $roles);
    }

    /**
     * Test erase credentials.
     *
     * @return void
     */
    public function testEraseCredentialsDoesNothing(): void
    {
        $user = new User();

        $user->eraseCredentials();

        $this->assertTrue(true);
    }
}
