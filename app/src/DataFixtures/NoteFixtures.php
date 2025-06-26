<?php

namespace App\DataFixtures;

use App\Entity\Note;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

/**
 * Class NoteFixtures.
 */
class NoteFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    protected function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker instanceof Generator) {
            return;
        }

        $this->createMany(20, 'note', function (int $i) {
            $note = new Note();
            $note->setTitle($this->faker->unique()->sentence());

            return $note;
        });
    }
}
