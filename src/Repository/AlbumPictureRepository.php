<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\AlbumPicture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AlbumPicture|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlbumPicture|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlbumPicture[]    findAll()
 * @method AlbumPicture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumPictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlbumPicture::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(AlbumPicture $entity, bool $flush = true): void
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
    public function remove(AlbumPicture $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    /**
     * Requête pour récupérer les albums en fonction de la recherche de l'utilisateur (a = albumPicture, T = tags)
     * @return AlbumPicture[]
     */
    public function findWithSearch(Search $search)
    {
        $query = $this
            ->createQueryBuilder('a')
            ->orderBy('a.Tag', 'DESC')
            ->select('T', 'a')
            ->join('a.Tag', 'T');

        if (!empty($search->Tags)) { //recherche par tags
            $query = $query
                ->andWhere('T.id IN (:Tag)')
                ->setParameter('Tag', $search->Tags);
        }

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return AlbumPicture[] Returns an array of AlbumPicture objects
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
    public function findOneBySomeField($value): ?AlbumPicture
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
