<?php

namespace App\Repository;

use App\Entity\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Season>
 */
class SeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Season::class);
    }

    public function findSeasonsBetweenDates(\DateTime $dateStart, \DateTime $dateEnd): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.date_start <= :dateEnd')
            ->andWhere('s.date_end >= :dateStart')
            ->setParameter('dateStart', $dateStart)
            ->setParameter('dateEnd', $dateEnd)
            ->getQuery()
            ->getResult();
    }

    public function findSeasonsActiveOnCurrentDate(): ?Season
    {
        $currentDate = new \DateTime('now', new \DateTimeZone('UTC'));  // Date actuelle en UTC
        $currentDate->setTime(0, 0, 0);  // Supprime l'heure, pour ne comparer que les dates

        // On recherche la saison qui est active à la date actuelle
        return $this->createQueryBuilder('s')
            ->where('s.date_start <= :currentDate') // La date de début de la saison doit être avant la date actuelle
            ->andWhere('s.date_end >= :currentDate') // La date de fin de la saison doit être après la date actuelle
            ->setParameter('currentDate', $currentDate)
            ->getQuery()
            ->getOneOrNullResult(); // Retourne la saison correspondante ou null si aucune saison n'est trouvée
    }
}
