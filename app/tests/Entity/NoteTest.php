<?php

namespace App\Tests\Entity;

use App\Entity\Note;
use PHPUnit\Framework\TestCase;

class NoteTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $note = new Note();

        $note->setTitle('Test note title');
        $this->assertSame('Test note title', $note->getTitle());

        $createdAt = new \DateTimeImmutable('2025-06-26 12:00:00');
        $note->setCreatedAt($createdAt);
        $this->assertSame($createdAt, $note->getCreatedAt());

        $updatedAt = new \DateTimeImmutable('2025-06-26 13:00:00');
        $note->setUpdatedAt($updatedAt);
        $this->assertSame($updatedAt, $note->getUpdatedAt());

        $note->setSlug('test-note-slug');
        $this->assertSame('test-note-slug', $note->getSlug());
    }

    public function testDefaultIdIsNull()
    {
        $note = new Note();
        $this->assertNull($note->getId());
    }
}
