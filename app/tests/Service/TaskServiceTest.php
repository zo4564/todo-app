<?php

// (c) 2025 zos

namespace App\Tests\Service;

use App\Entity\Task;
use App\Entity\Category;
use App\Service\TaskService;
use App\Service\TaskServiceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Task service test class.
 */
class TaskServiceTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?TaskServiceInterface $taskService;

    /**
     * Test save.
     */
    public function testSave(): void
    {
        // given
        $category = $this->createCategory();
        $expectedTask = new Task();
        $expectedTask->setTitle('Test Task');
        $expectedTask->setCategory($category); // 🔥 to jest kluczowe

        // when
        $this->taskService->save($expectedTask);

        // then
        $expectedTaskId = $expectedTask->getId();
        $resultTask = $this->entityManager->createQueryBuilder()
            ->select('task')
            ->from(Task::class, 'task')
            ->where('task.id = :id')
            ->setParameter(':id', $expectedTaskId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedTask, $resultTask);
    }

    /**
     * Test delete.
     */
    public function testDelete(): void
    {
        // given
        $category = $this->createCategory();
        $taskToDelete = new Task();
        $taskToDelete->setTitle('Task to Delete');
        $taskToDelete->setCategory($category);
        $this->entityManager->persist($taskToDelete);
        $this->entityManager->flush();
        $deletedTaskId = $taskToDelete->getId();

        // when
        $this->taskService->delete($taskToDelete);

        // then
        $resultTask = $this->entityManager->createQueryBuilder()
            ->select('task')
            ->from(Task::class, 'task')
            ->where('task.id = :id')
            ->setParameter(':id', $deletedTaskId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultTask);
    }

    /**
     * Test get paginated list.
     */
    public function testGetPaginatedList(): void
    {
        // given
        $category = $this->createCategory();
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;

        for ($i = 0; $i < $dataSetSize; ++$i) {
            $task = new Task();
            $task->setTitle('Task #'.$i);
            $task->setCategory($category);
            $this->taskService->save($task);
        }

        // when
        $result = $this->taskService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * set up.
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->taskService = $container->get(TaskService::class);
    }

    /**
     * @param string $title title
     *
     * @return Category cat
     */
    private function createCategory(string $title = 'Test Category'): Category
    {
        $category = new Category();
        $category->setTitle($title);
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }
}
