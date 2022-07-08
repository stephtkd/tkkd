<?php


namespace App\Form\DataTransformer;

use App\Entity\EventOption;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NumberToEventOptionTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (eventOption) to a int (number).
     *
     * @param  EventOption|null $eventOption
     */
    public function transform($eventOption): array
    {
        if (null === $eventOption /*|| !$eventOption instanceof EventOption*/) {
            return [];
        }

        $listEventOption = [];
        $listEventOption[] = $eventOption->getId();

        return $listEventOption;
    }

    /**
     * Transforms a int (number) to an object (eventOption).
     *
     * @param  int $eventOptionNumber
     * @throws TransformationFailedException if object (eventOption) is not found.
     */
    public function reverseTransform($eventOptionNumbers): ?ArrayCollection
    {

        if (!$eventOptionNumbers) {
            return null;
        }
        $listEventOption = new ArrayCollection();

        foreach($eventOptionNumbers as $value){
            $eventOption = $this->entityManager->getRepository(EventOption::class)->findOneById($value);

            if (!$listEventOption->contains($value)) {
                $listEventOption[] = $eventOption;
            }
        }
        


        if (null === $eventOption) {
            throw new TransformationFailedException(sprintf(
                'An eventOption with number "%s" does not exist!',
                $eventOptionNumbers
            ));
        }

        return $listEventOption;
    }
}