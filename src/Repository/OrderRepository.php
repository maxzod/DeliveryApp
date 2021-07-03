<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function getDriverOrders(UserInterface $user, ?int $page = 1, ?int $status = Order::STATUS_PROCESSING): array|int|string
    {
        $qb = $this->createQueryBuilder('o');
        return $qb
            ->leftJoin("o.owner", "owner")
            ->leftJoin("o.products", "products")
            ->leftJoin("o.place", "place")
            ->leftJoin("o.dropPlace", "drop_place")
            ->leftJoin('o.conversation', 'conversation')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('o.driver', ':user'),
                $qb->expr()->eq('o.status', ':status')
            ))
            ->setParameter("user", $user)
            ->setParameter("status", $status)
            ->setFirstResult(($page - 1) * 15)
            ->setMaxResults($page * 15)
            ->getQuery()
            ->getResult();
    }

    public function getClientOrders(User $user, ?int $page = 1, ?int $status = 1)
    {
        $qb = $this->createQueryBuilder('o');

        return $qb
            ->leftJoin("o.owner", "owner")
            ->leftJoin("o.products", "products")
            ->leftJoin("o.place", "place")
            ->leftJoin("o.dropPlace", "drop_place")
            ->leftJoin('o.conversation', 'conversation')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('o.owner', ':user'),
                $qb->expr()->eq('o.status', ':status')
            ))
            ->setParameter('user', $user)
            ->setParameter('status', $status)
            ->setFirstResult(($page - 1) * 15)
            ->setMaxResults($page * 15)
            ->getQuery()
            ->getResult();
    }

    public function getAvailableOrders(?int $page = 1)
    {
        return $this->createQueryBuilder('o')
            ->where("o.status = :status")
            ->andWhere("COUNT(o.offers) < 6")
            ->leftJoin("o.owner", "owner")
            ->leftJoin("owner.image", "image")
            ->leftJoin("o.products", "products")
            ->leftJoin("o.place", "place")
            ->leftJoin("o.dropPlace", "drop_place")
            ->leftJoin('o.conversation', 'conversation')
            ->setParameter("status", Order::STATUS_WAITING)
            ->setFirstResult(($page - 1) * 15)
            ->setMaxResults($page * 15)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
