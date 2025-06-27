<?php

/**
 * Notes data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Note;
use App\Service\NoteServiceInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class NotesDataTransformer.
 *
 * @implements DataTransformerInterface<mixed, mixed>
 */
class NotesDataTransformer implements DataTransformerInterface
{
    /**
     * Constructor.
     *
     * @param NoteServiceInterface $noteService Note service
     */
    public function __construct(private readonly NoteServiceInterface $noteService)
    {
    }

    /**
     * Transform array of notes to string of note titles.
     *
     * @param Collection<int, Note> $value Notes entity collection
     *
     * @return string Result
     */
    public function transform($value): string
    {
        if ($value->isEmpty()) {
            return '';
        }

        $noteTitles = [];

        foreach ($value as $note) {
            $noteTitles[] = $note->getTitle();
        }

        return implode(', ', $noteTitles);
    }

    /**
     * Transform string of note names into array of Note entities.
     *
     * @param string $value String of note names
     *
     * @return array<int, Note> Result
     */
    public function reverseTransform($value): array
    {
        $noteTitles = explode(',', $value);

        $notes = [];

        foreach ($noteTitles as $noteTitle) {
            if ('' !== trim($noteTitle)) {
                $note = $this->noteService->findOneByTitle(strtolower($noteTitle));
                if (null === $note) {
                    $note = new Note();
                    $note->setTitle($noteTitle);

                    $this->noteService->save($note);
                }
                $notes[] = $note;
            }
        }

        return $notes;
    }
}
