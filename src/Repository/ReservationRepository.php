<?php

namespace App\Repository;

use App\Entity\Reservation;
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

    public function save(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllReservations()
    {
        return $this->createQueryBuilder('r')
            ->select('r.id, r.ref, r.customer_id, r.room_id,DATE_FORMAT(r.check_in,\'%Y-%m-%d\') check_in, DATE_FORMAT(r.check_out,\'%Y-%m-%d\') check_out, r.amount, r.created_at')
            ->getQuery()
            ->getArrayResult();
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

    public function findReservationByRef($ref): ?array
    {
        return $this->createQueryBuilder('r')
            ->select('r.id, r.ref, r.customer_id, r.room_id,DATE_FORMAT(r.check_in,\'%Y-%m-%d\') check_in, DATE_FORMAT(r.check_out,\'%Y-%m-%d\') check_out, r.amount, r.created_at')
            ->andWhere('r.ref = :val')
            ->setParameter('val', $ref)
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
