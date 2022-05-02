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
     * Requête pour récupérer les albums en fonction de la recherche de l'utilisateur (a = albumPicture, c = CategoriesAlbum, t = tags)
     * @return AlbumPicture[]
     */
    public function findWithSearch(Search $search)
    {
        $query = $this //ajouter t = tag
            ->createQueryBuilder('a')
            ->orderBy('a.categoryAlbum', 'DESC')
            ->select('c', 'a')
            ->join('a.categoryAlbum', 'c');

        if (!empty($search->categoriesAlbum)) { // recherche des catégories
            $query = $query
                ->andWhere('c.id IN (:CategoriesAlbum)')
                ->setParameter('CategoriesAlbum', $search->CategoriesAlbum);
        }

        if (!empty($search->Tags)) { //recherche par tags
            $query = $query
                ->andWhere('t.id IN (:Tags)')
                ->setParameter('Tags', $search->Tags);
        }

        if (!empty($search->string)) {
            $query = $query
                ->andWhere('a.title LIKE :string')
                ->setParameter('string', "%{$search->string}%");
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
