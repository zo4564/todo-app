<?php

// (c) 2025 zos

namespace App\Tests\Service;

use App\Entity\Note;
use App\Entity\Task;
use App\Entity\Category;
use App\Service\NoteService;
use App\Service\NoteServiceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Note service test class.
 */
class NoteServiceTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?NoteServiceInterface $noteService;

    /**
     * Test save.
     */
    public function testSave(): void
    {
        // given
        $task = $this->createTask();
        $note = new Note();
        $note->setTitle('Test Note');

        // when
        $this->noteService->save($note);

        // then
        $savedNote = $this->entityManager->createQueryBuilder()
            ->select('n')
            ->from(Note::class, 'n')
            ->where('n.id = :id')
            ->setParameter(':id', $note->getId(), Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($note->getTitle(), $savedNote->getTitle());
    }

    /**
     * Test delete.
     */
    public function testDelete(): void
    {
        // given
        $task = $this->createTask();
        $note = new Note();
        $note->setTitle('Note to Delete');
        $this->entityManager->persist($note);
        $this->entityManager->flush();
        $noteId = $note->getId();

        // when
        $this->noteService->delete($note);

        // then
        $deletedNote = $this->entityManager->createQueryBuilder()
            ->select('n')
            ->from(Note::class, 'n')
            ->where('n.id = :id')
            ->setParameter(':id', $noteId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($deletedNote);
    }

    /**
     * Test get paginated list.
     */
    public function testGetPaginatedList(): void
    {
        // given
        $task = $this->createTask();
        $page = 1;
        $dataSetSize = 3;

        for ($i = 0; $i < $dataSetSize; ++$i) {
            $note = new Note();
            $note->setTitle('TestNote'.$i);
            $this->noteService->save($note);
        }

        // when
        $pagination = $this->noteService->getPaginatedList($page);

        // then
        $this->assertGreaterThanOrEqual(3, $pagination->count());
    }

    /**
     * Test find one by title.
     */
    public function testFindOneByTitle(): void
    {
        // given
        $task = $this->createTask();
        $note = new Note();
        $note->setTitle('Unique Note Title');
        $this->noteService->save($note);

        // when
        $fetchedNote = $this->noteService->findOneByTitle('Unique Note Title');

        // then
        $this->assertNotNull($fetchedNote);
        $this->assertEquals($note->getTitle(), $fetchedNote->getTitle());
    }

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->noteService = $container->get(NoteService::class);
    }

    /**
     * @param string $title title
     *
     * @return Task task
     */
    private function createTask(string $title = 'Related Task'): Task
    {
        $category = new Category();
        $category->setTitle('Test Category');
        $this->entityManager->persist($category);

        $task = new Task();
        $task->setTitle($title);
        $task->setCategory($category);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }
}
