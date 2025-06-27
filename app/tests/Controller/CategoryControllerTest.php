<?php
// (c) 2025 zos
namespace App\Tests\Controller;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CategoryControllerTest.
 */
class CategoryControllerTest extends WebTestCase
{
    private \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    /**
     * Test index page.
     *
     * @return void
     */
    public function testIndexPage(): void
    {
        $this->client->request('GET', '/category');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    /**
     * Test create page get.
     *
     * @return void
     */
    public function testCreatePageGet(): void
    {
        $this->client->request('GET', '/category/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    /**
     * Test create category.
     *
     * @return void
     */
    public function testCreateCategory(): void
    {


        $crawler = $this->client->request('GET', '/category/create');
        $form = $crawler->selectButton('Save')->form();

        $form['category[title]'] = 'Test Category';
        $this->client->submit($form);

        $this->assertResponseRedirects('/category');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Record created successfully.');
    }

    /**
     * Test set up.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->client->loginUser($adminUser);
    }

    /**
     * Test create user.
     *
     * @param array $roles
     *
     * @return User
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
