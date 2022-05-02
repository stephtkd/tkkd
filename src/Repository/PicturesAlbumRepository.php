<?php

namespace App\Repository;

use App\Entity\PicturesAlbum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PicturesAlbum|null find($id, $lockMode = null, $lockVersion = null)
 * @method PicturesAlbum|null findOneBy(array $criteria, array $orderBy = null)
 * @method PicturesAlbum[]    findAll()
 * @method PicturesAlbum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PicturesAlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PicturesAlbum::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PicturesAlbum $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(PicturesAlbum $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return PicturesAlbum[] Returns an array of PicturesAlbum objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PicturesAlbum
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
