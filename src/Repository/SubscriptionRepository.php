<?php

namespace App\Repository;

use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    // /**
    //  * @return Subscription[] Returns an array of Subscription objects
    //  */

    public function getAll()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT s, u.email as email
            FROM App\Entity\Subscription s
            INNER JOIN s.user u WHERE s.been_notified=0"
        );

        // returns an array of Product objects
        return $query->getResult();
    }

    public function updateSubscriptions(array $ids)
    {
        $queryBuilder = $this
            ->createQueryBuilder('s');

        $query = $queryBuilder
            ->update('App\Entity\Subscription', 's')
            ->set('s.been_notified', true)
            ->where(
                $queryBuilder->expr()->In('s.id', $ids)
            )->getQuery();

        // returns an array of Product objects
        return $query->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Subscription
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
