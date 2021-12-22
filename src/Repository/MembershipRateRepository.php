<?php

namespace App\Repository;

use App\Entity\MembershipRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MembershipRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method MembershipRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method MembershipRate[]    findAll()
 * @method MembershipRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembershipRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MembershipRate::class);
    }

    // /**
    //  * @return MembershipRate[] Returns an array of MembershipRate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MembershipRate
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
