<?php

// (c) 2025 zos

namespace App\Tests\Controller;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * Note controller test.
 */
class NoteControllerTest extends WebTestCase
{
    private ?KernelBrowser $client = null;

    /**
     * Test index page.
     */
    public function testIndexPage(): void
    {
        $this->client->request('GET', '/note');

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        $this->assertStringContainsString('Note', $response->getContent());
    }

    /**
     * Test create page get.
     */
    public function testCreatePageGet(): void
    {
        $this->client->request('GET', '/note/create');

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->assertStringContainsString('<form', $response->getContent());
    }

    /**
     * Test create note.
     */
    public function testCreateNote(): void
    {
        $crawler = $this->client->request('GET', '/note/create');

        $form = $crawler->selectButton('Save')->form();

        $form['note[title]'] = 'Test Note Title';

        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect('/note'));

        $crawler = $this->client->followRedirect();

        $this->assertStringContainsString('Record created successfully.', $crawler->filter('.alert-success')->text());
    }

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();

        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->client->loginUser($adminUser);
    }

    /**
     * @param array $roles roles
     *
     * @return User users
     */
    private function createUser(array $roles): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles($roles);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                'p@55w0rd'
            )
        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }
}
