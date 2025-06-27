<?php
// (c) 2025 zos
namespace App\Tests\Controller;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Category;

/**
 * Task controller test class.
 */
class TaskControllerTest extends WebTestCase
{
    /**
     * Test create
     *
     * @return void
     */
    public function testCreate()
    {

        $entityManager = $this->client->getContainer()->get('doctrine')->getManager();

        $category = new Category();
        $category->setTitle('Test category');
        $entityManager->persist($category);
        $entityManager->flush();

        $crawler = $this->client->request('GET', '/task/create');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Save')->form([
            'task[title]' => 'Test task',
            'task[notes]' => 'Test notes',
            'task[category]' => $category->getId(),
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/task');

        $this->client->followRedirect();

        $this->assertSelectorTextContains('.alert-success', 'Record created successfully.');
    }

    /**
     * Set up.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();

        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->client->loginUser($adminUser);
    }

    /**
     * Create user.
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
