<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }


    public function findActualAdhesion()
    {
        $currentDate = new \DateTime();

        return $this->createQueryBuilder('e')
            ->andWhere('e.season IS NOT NULL')
            ->andWhere('e.startDate < :currentDate AND e.endDate > :currentDate')
            ->setParameter('currentDate', $currentDate)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findNextAdhesion()
    {
        $nextYear = (new \DateTime())->modify('+1 year');

        return $this->createQueryBuilder('e')
            ->andWhere('e.season IS NOT NULL')
            ->andWhere('e.startDate < :nextYear AND e.endDate > :nextYear')
            ->setParameter('nextYear', $nextYear)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    /*
    public function findOneBySomeField($value): ?Event
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
