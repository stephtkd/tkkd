<?php

namespace App\Repository;

use App\Entity\EventSubscription;
use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Member|null find($id, $lockMode = null, $lockVersion = null)
 * @method Member|null findOneBy(array $criteria, array $orderBy = null)
 * @method Member[]    findAll()
 * @method Member[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function findAllMembersFromEvent($event)
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.eventSubscriptions', 'es')
            ->andWhere('es.event = :event AND es.status = :status')
            ->setParameter('event', $event)
            ->setParameter('status', 'Payé')
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findForEventSubscription($options){
        $qb = null;
        $not = $this->createQueryBuilder('mb1')
                    ->select('mb1.id')
                    ->innerJoin(EventSubscription::class,'eventSubscription','WITH','eventSubscription.member = mb1.id')
                    ->where('mb1.responsibleAdult =:responsibleAdult ')
                    ->setParameter('responsibleAdult', $options['responsibleAdult'])
                    ->andWhere('eventSubscription.event =:event')
                    ->setParameter('event',$options['event'])
                    ;

        if(count($not->getQuery()->getResult()) > 0){
            $qb= $this->createQueryBuilder('memberAs');
            $qb->where('memberAs.responsibleAdult =:responsibleAdult')
            ->setParameter('responsibleAdult', $options['responsibleAdult'])
            ->andWhere($qb->expr()->notIn('memberAs.id', $not->getQuery()->getResult()[0]))
            ;
        }else{
            $qb= $this->createQueryBuilder('memberAs');
            $qb->where('memberAs.responsibleAdult =:responsibleAdult')
            ->setParameter('responsibleAdult', $options['responsibleAdult']);
        }
        
        return $qb;
    }


    /*
    public function findOneBySomeField($value): ?Member
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


