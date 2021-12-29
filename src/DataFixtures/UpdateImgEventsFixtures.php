<?php

namespace App\DataFixtures;

use App\Entity\EventPage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UpdateImgEventsFixtures extends Fixture implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $repEvents = $em->getRepository(EventPage::class);
        $listEvents = $repEvents->findAll();

        foreach ($listEvents as $listEvent) {
            switch ($listEvent->getName()) {
                case 'taekwonkido':
                    $listEvent->setLinkImage('arbitre-taekwondo.jpg');
                    break;
                case 'karate':
                    $listEvent->setLinkImage('Discipline_Taekwonkido.jpg');
                    break;
                case 'Kunfu':
                    $listEvent->setLinkImage('Taekwondo-scaled.jpg');
                    break;
            }

            $manager->persist($listEvent);
        }

        $manager->flush();
    }
}
