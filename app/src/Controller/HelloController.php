<?php
/**
 * Hello controller.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class HelloController.
 */
class HelloController
{
    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route(
        '/hello',
        name: 'hello_index',
        methods: 'GET'
    )]
    public function index(): Response
    {
        return new Response('Hello World!');
    }
}
