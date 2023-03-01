<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 *
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function save(Room $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Room $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Room[] Returns an array of Room objects
     */
    public function findAvailableRooms(Room $entity): ?array
    {
        $qbReservations = $this->_em->createQueryBuilder();
        $qbReservations->select('r.room_id')
            ->from('App\Entity\Reservation', 'r')
            ->where('(r.check_in >= :check_in or r.check_out > :check_in) and (r.check_out < :check_out or r.check_in < :check_out)')
            ->setParameter('check_in', $entity->getCheckIn())
            ->setParameter('check_out', $entity->getCheckOut())
            ->orderBy('r.id', 'ASC');

        $qbRooms = $this->_em->createQueryBuilder();
        $availableRoomsQuery = $qbRooms->select('rr')
            ->from('App\Entity\Room', 'rr')
            ->where('rr.available = 1 and  rr.capacity >= :guest')
            ->andWhere($qbRooms->expr()->notIn('rr.id', $qbReservations->getDQL()))
            ->orderBy('rr.id', 'ASC');

        $availableRoomsQuery->setParameter('check_in', $entity->getCheckIn());
        $availableRoomsQuery->setParameter('check_out', $entity->getCheckOut());
        $availableRoomsQuery->setParameter('guest', $entity->getGuest());
        $availableRooms = $availableRoomsQuery->getQuery()->getArrayResult();

        return $availableRooms;
    }

    public function findOneByRoomId($roomId): ?array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :val')
            ->setParameter('val', $roomId)
            ->getQuery()
            ->getArrayResult();
    }
}
