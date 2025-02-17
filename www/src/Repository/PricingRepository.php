<?php

namespace App\Repository;

use App\Entity\Accommodation;
use App\Entity\Pricing;
use App\Entity\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pricing>
 */
class PricingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pricing::class);
    }

    public function findTarif(Accommodation $accommodation, Season $season): ?Pricing
    {
        return $this->createQueryBuilder('t')
            ->where('t.accommodation = :accommodation')
            ->andWhere('t.season = :season')
            ->setParameter('accommodation', $accommodation)
            ->setParameter('season', $season)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
