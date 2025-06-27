<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPageLoadsSuccessfully(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

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

    public function testLogoutActionThrowsLogicException(): void
    {
        $this->expectException(\LogicException::class);
        $controller = new \App\Controller\SecurityController();
        $controller->logout();
    }
}
