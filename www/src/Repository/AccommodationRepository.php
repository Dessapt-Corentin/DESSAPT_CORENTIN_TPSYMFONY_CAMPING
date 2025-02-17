<?php

namespace App\Repository;

use App\Entity\Accommodation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Accommodation>
 */
class AccommodationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Accommodation::class);
    }

    /**
     * Méthode qui retourne la liste des hébergements avec leurs informations
     */
    public function getAccommodationWithInfo(int $id)
    {
        $entityManager = $this->getEntityManager();

        $qb = $entityManager->createQueryBuilder();

        $query = $qb->select(
            'a.id',
            'a.label',
            'a.location_number',
            'a.size',
            'a.description',
            'a.image',
            'a.capacity',
            'a.availability',
            't.label as type',
            'p.price',
            'e.label as equipments'
        )
            ->from(Accommodation::class, 'a')
            ->join('a.type', 't')
            ->join('a.pricings', 'p')
            ->join('p.season', 's')
            ->join('a.equipments', 'e')
            ->where('a.id = :id')
            ->andWhere(':today BETWEEN s.date_start AND s.date_end')
            ->setParameter('id', $id)
            ->setParameter('today', new \DateTime())
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Méthode qui retourne tout les hébergements avec leurs informations, équipements associés, et tarifs
     */
    public function getAccommodationsForIndex()
    {
        $entityManager = $this->getEntityManager();

        $qb = $entityManager->createQueryBuilder();

        $query = $qb->select(
            'a.id',
            'a.size',
            'a.image',
            'a.capacity',
            'a.availability',
            't.label as type',
            'p.price',
        )
            ->from(Accommodation::class, 'a')
            ->join('a.type', 't')
            ->join('a.pricings', 'p')
            ->join('p.season', 's')
            ->where(':today BETWEEN s.date_start AND s.date_end')
            ->setParameter('today', new \DateTime())
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Méthode qui retourne les prix pour un hébergement donné
     */
    public function getPricesForAccommodation(int $id)
    {
        $entityManager = $this->getEntityManager();

        $qb = $entityManager->createQueryBuilder();

        $query = $qb->select(
            'p.price'
        )
            ->from('App\Entity\Pricing', 'p')
            ->join('p.season', 's')
            ->where('p.accommodation = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * méthode qui retourne la liste des types avec le nombre de accommodation associés
     * @return array
     */
    public function getCountAccommodationByType(): array
    {
        $entityManager = $this->getEntityManager();

        $qb = $entityManager->createQueryBuilder();

        $query = $qb->select([
            't.id',
            't.label',
            'COUNT(a.id) as total'

        ])->from(Accommodation::class, 'a')
            ->join('a.type', 't')
            ->groupBy('t.id')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * méthode qui recupère les accomodations par type
     * @param int $id
     * @return array
     */
    public function getAccommodationsByType(int $id): array
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $query = $qb->select(
            'a.id',
            'a.label',
            'a.size',
            'a.image',
            'a.capacity',
            'a.availability',
            't.label as type'
        )
            ->from(Accommodation::class, 'a')
            ->join('a.type', 't')
            ->join('a.pricings', 'p')
            ->join('p.season', 's')
            ->where('t.id = :id')
            ->andWhere(':today BETWEEN s.date_start AND s.date_end')
            ->setParameter('id', $id)
            ->setParameter('today', new \DateTime())
            ->getQuery();

        return $query->getResult();
    }

    public function getTypeLabelById(int $id): string
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $query = $qb->select('t.label')
            ->from('App\Entity\TypeAccommodation', 't')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getSingleScalarResult();
    }
}
