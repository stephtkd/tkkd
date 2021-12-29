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
