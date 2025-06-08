<?php
/**
 * Task controller.
 */

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class TaskController.
 */
#[Route('/task')]
class TaskController extends AbstractController
{
    // ...
    /**
     * Index action.
     *
     * @param TaskRepository     $taskRepository Task repository
     * @param PaginatorInterface $paginator      Paginator
     * @param int                $page           Default page number
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'task_index',
        methods: 'GET'
    )]
    public function index(TaskRepository $taskRepository, PaginatorInterface $paginator, int $page = 1): Response
    {
        $pagination = $paginator->paginate(
            $taskRepository->queryAll(),
            $page,

            TaskRepository::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['task.id', 'task.createdAt', 'task.updatedAt', 'task.title'],
                'defaultSortFieldName' => 'task.updatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );

        return $this->render('task/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * View action.
     *
     * @param Task $task Task entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'task_view',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function view(Task $task): Response
    {
        return $this->render(
            'task/view.html.twig',
            ['task' => $task]
        );
    }
}
