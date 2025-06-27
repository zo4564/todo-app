<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Security controller test class
 */
class SecurityControllerTest extends WebTestCase
{
    /**
     * Test login page loads.
     *
     * @return void
     */
    public function testLoginPageLoadsSuccessfully(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    /**
     * Test login
     *
     * @return void
     */
    public function testLoginWithWrongCredentialsShowsError(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form([
            '_username' => 'wrong@example.com',
            '_password' => 'wrongpassword',
        ]);

        $client->submit($form);

        $client->followRedirect();

        $this->assertSelectorExists('.alert');
    }

    /**
     * Test logout action
     *
     * @return void
     */
    public function testLogoutActionThrowsLogicException(): void
    {
        $this->expectException(\LogicException::class);
        $controller = new \App\Controller\SecurityController();
        $controller->logout();
    }
}
