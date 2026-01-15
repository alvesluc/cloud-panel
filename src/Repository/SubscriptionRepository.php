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

    public function calculateTotalMRR(?string $region)
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

    public function findCriticalSubscriptions(int $limit = 5): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.plan', 'p')
            ->join('s.client', 'c')
            ->addSelect('p', 'c')
            ->where('s.status = :status')
            ->andWhere('(s.currentUsage / p.limitValue) >= 0.9')
            ->setParameter('status', SubscriptionStatus::ACTIVE)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getTopPerformingServices(): array
    {
        return $this->createQueryBuilder('s')
            ->select('service.name as service_name, SUM(p.price) as revenue')
            ->join('s.plan', 'p')
            ->join('p.service', 'serv')
            ->where('s.status = :status')
            ->setParameter('status', SubscriptionStatus::ACTIVE)
            ->groupBy('service.id')
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
