<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $category = new Category();

        $category->setTitle('Test category');
        $this->assertSame('Test category', $category->getTitle());

        $createdAt = new \DateTimeImmutable('2025-06-26 12:00:00');
        $category->setCreatedAt($createdAt);
        $this->assertSame($createdAt, $category->getCreatedAt());

        $updatedAt = new \DateTimeImmutable('2025-06-26 13:00:00');
        $category->setUpdatedAt($updatedAt);
        $this->assertSame($updatedAt, $category->getUpdatedAt());

        $category->setSlug('test-category');
        $this->assertSame('test-category', $category->getSlug());
    }

    public function testDefaultIdIsNull()
    {
        $category = new Category();
        $this->assertNull($category->getId());
    }
}
