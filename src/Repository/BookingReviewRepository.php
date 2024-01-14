<?php

namespace App\Repository;

use App\Entity\BookingReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookingReview>
 *
 * @method BookingReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingReview[]    findAll()
 * @method BookingReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookingReview::class);
    }

    public function save(BookingReview $bookingReview): void
    {
        $this->getEntityManager()->persist($bookingReview);
        $this->getEntityManager()->flush();
    }

    public function delete(BookingReview $review): void
    {
        $this->getEntityManager()->remove($review);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return BookingReview[] Returns an array of BookingReview objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BookingReview
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
