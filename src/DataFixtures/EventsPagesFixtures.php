<?php

namespace App\DataFixtures;

use App\Data\ListEvents;
use App\Entity\EventPage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventsPagesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach (ListEvents::$theEvents as $theEvent) {
            $events = new EventPage();

            $events->setName($theEvent['name']);
            $events->setPrice($theEvent['price']);

            $manager->persist($events);
        }
        $manager->flush();
    }
}
