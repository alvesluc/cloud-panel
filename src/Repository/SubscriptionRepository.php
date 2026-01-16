<?php

namespace App\Repository;

use App\Entity\Subscription;
use App\Enum\SubscriptionStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subscription>
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    public function calculateTotalMRR(?string $region): float
    {
        $qb = $this->createQueryBuilder('s')
            ->select('SUM(p.price)')
            ->join('s.plan', 'p')
            ->join('s.client', 'c')
            ->where('s.status = :status')
            ->setParameter('status', SubscriptionStatus::ACTIVE);

        if ($region) {
            $qb->andWhere('c.region = :region')
                ->setParameter('region', $region);
        }

        return (float) $qb->getQuery()->getSingleScalarResult();
    }

    public function findCriticalSubscriptions(int $limit = 5, ?string $region = null): array
    {
        $qb = $this->createQueryBuilder('s')
            ->join('s.plan', 'p')
            ->join('s.client', 'c')
            ->addSelect('p', 'c')
            ->where('s.status = :status')
            ->andWhere('(s.currentUsage / p.limitValue) >= 0.9')
            ->setParameter('status', SubscriptionStatus::ACTIVE);

        if ($region) {
            $qb->andWhere('c.region = :region')
                ->setParameter('region', $region);
        }

        return $qb->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getTopPerformingServices(?string $region = null): array
    {
        $qb = $this->createQueryBuilder('s')
            ->select('serv.name as service_name, SUM(p.price) as revenue')
            ->join('s.plan', 'p')
            ->join('p.service', 'serv')
            ->join('s.client', 'c')
            ->where('s.status = :status')
            ->setParameter('status', SubscriptionStatus::ACTIVE);

        if ($region) {
            $qb->andWhere('c.region = :region')
                ->setParameter('region', $region);
        }

        return $qb->groupBy('serv.id')
            ->orderBy('revenue', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Subscription[] Returns an array of Subscription objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Subscription
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
