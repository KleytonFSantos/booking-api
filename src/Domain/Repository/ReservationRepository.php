<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Reservation;
use App\Domain\Enum\ReservationStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function save(Reservation $reservation): void
    {
        $manager = $this->getEntityManager();
        $manager->persist($reservation);
        $manager->flush();
    }

    public function destroy(?Reservation $reservation): void
    {
        $manager = $this->getEntityManager();
        $manager->remove($reservation);
        $manager->flush();
    }

    /**
     * @return Reservation[]
     */
    public function findReservationsToRemind(): array
    {
        $qb = $this->createQueryBuilder('reservation');

        return $qb
            ->andWhere($qb->expr()->between('reservation.start_date', ':start', ':end'))
            ->andWhere($qb->expr()->eq('reservation.status', ':reservationStatus'))
            ->setParameter('start', new \DateTime('-3 days'))
            ->setParameter('end', new \DateTime('+1 day'))
            ->setParameter('reservationStatus', ReservationStatusEnum::RESERVED->value)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
