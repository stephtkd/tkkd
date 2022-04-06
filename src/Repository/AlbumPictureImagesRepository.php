<?php

namespace App\Repository;

use App\Entity\AlbumPictureImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AlbumPictureImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlbumPictureImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlbumPictureImages[]    findAll()
 * @method AlbumPictureImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumPictureImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlbumPictureImages::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(AlbumPictureImages $entity, bool $flush = true): void
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
    public function remove(AlbumPictureImages $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return AlbumPictureImages[] Returns an array of AlbumPictureImages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AlbumPictureImages
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
