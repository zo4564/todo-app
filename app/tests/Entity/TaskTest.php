<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Note;
use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $task = new Task();

        $this->assertNull($task->getId());

        $task->setTitle('Test task title');
        $this->assertSame('Test task title', $task->getTitle());

        $createdAt = new \DateTimeImmutable('2025-06-26 12:00:00');
        $task->setCreatedAt($createdAt);
        $this->assertSame($createdAt, $task->getCreatedAt());

        $updatedAt = new \DateTimeImmutable('2025-06-26 13:00:00');
        $task->setUpdatedAt($updatedAt);
        $this->assertSame($updatedAt, $task->getUpdatedAt());

        $task->setComment('This is a comment');
        $this->assertSame('This is a comment', $task->getComment());

        $category = new Category();
        $category->setTitle('Test category');
        $task->setCategory($category);
        $this->assertSame($category, $task->getCategory());
    }

    public function testNotesCollection()
    {
        $task = new Task();
        $note1 = new Note();
        $note1->setTitle('Note 1');
        $note2 = new Note();
        $note2->setTitle('Note 2');

        $this->assertCount(0, $task->getNotes());

        $task->addNote($note1);
        $this->assertCount(1, $task->getNotes());
        $this->assertTrue($task->getNotes()->contains($note1));

        $task->addNote($note2);
        $this->assertCount(2, $task->getNotes());
        $this->assertTrue($task->getNotes()->contains($note2));

        $task->addNote($note1);
        $this->assertCount(2, $task->getNotes());

        $task->removeNote($note1);
        $this->assertCount(1, $task->getNotes());
        $this->assertFalse($task->getNotes()->contains($note1));
    }
}
