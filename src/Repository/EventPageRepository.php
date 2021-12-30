<?php

namespace App\Repository;

use App\Entity\EventPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventPage[]    findAll()
 * @method EventPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventPage::class);
    }

    // Get Events By DESC
    public function orderingEvents(): mixed
    {
        $listEvents = $this->getEntityManager()
            ->createQuery(dql: 'SELECT event_page FROM App\Entity\EventPage event_page ORDER BY event_page.id DESC')
            ->getResult();

        return $listEvents;
    }
    // /**
    //  * @return EventPage[] Returns an array of EventPage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventPage
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
