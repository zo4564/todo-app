<?php

/**
 * Note service.
 */

namespace App\Service;

use App\Repository\NoteRepository;
use App\Entity\Note;
use App\Repository\TaskRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class NoteService.
 */
class NoteService implements NoteServiceInterface
{
    /**
     * Find by title.
     *
     * @param string $title Note title
     *
     * @return Note|null Note entity
     */
    public function findOneByTitle(string $title): ?Note
    {
        return $this->noteRepository->findOneByTitle($title);
    }
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param NoteRepository $noteRepository
     * @param TaskRepository $taskRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(private readonly NoteRepository $noteRepository, private readonly TaskRepository $taskRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->noteRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['note.id', 'note.createdAt', 'note.updatedAt', 'note.title', 'note.title'],
                'defaultSortFieldName' => 'note.updatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );
    }

    /**
     * Save entity.
     *
     * @param Note $note Note entity
     */
    public function save(Note $note): void
    {
        $this->noteRepository->save($note);
    }

    /**
     * Delete entity.
     *
     * @param Note $note Note entity
     */
    public function delete(Note $note): void
    {
        $this->noteRepository->delete($note);
    }
}
