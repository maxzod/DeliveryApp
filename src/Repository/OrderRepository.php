<?php

namespace App\Repository;

use App\Entity\Order;
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

    public function getDriverOrders(UserInterface $user, int $page = 1, int $status = null): array|int|string
    {
        $qb = $this->createQueryBuilder('o')
            ->where("o.driver = :user");
        if ($status != null) {
            $qb = $qb->andWhere("o.status = :status")
                ->setParameter("status", $status);
        }
        return $qb
            ->join("o.owner", "owner")
            ->join("owner.image", "image")
            ->join("o.products", "products")
            ->join("o.place", "place")
            ->join("o.dropPlace", "drop_place")
            ->join('o.conversation', 'conversation')
            ->setParameter("user", $user)
            ->select("o, owner, products, place, drop_place, image, conversation.id as conversation_id")
            ->setFirstResult(($page - 1) * 15)
            ->setMaxResults($page * 15)
            ->getQuery()
            ->getArrayResult();
    }

    public function getClientOrders(int $userId, int $page, int $status = Order::STATUS_WAITING)
    {
        $qb = $this->createQueryBuilder('o')
            ->where("o.owner.id = :user");
        if ($status != null) {
            $qb = $qb->andWhere("o.status = :status")
                ->setParameter("status", $status);
        }
        return $qb
            ->join("o.owner", "owner")
            ->join("owner.image", "image")
            ->join("o.products", "products")
            ->join("o.place", "place")
            ->join("o.dropPlace", "drop_place")
            ->join('o.conversation', 'conversation')
            ->setParameter("user", $userId)
            ->select("o, owner, products, place, drop_place, image, conversation.id as conversation_id")
            ->setFirstResult(($page - 1) * 15)
            ->setMaxResults($page * 15)
            ->getQuery()
            ->getArrayResult();
    }

    public function getAvailableOrders(int $page)
    {
        return $this->createQueryBuilder('o')
            ->where("o.status = :status")
            ->andWhere("COUNT(o.offers) < 6")
            ->join("o.owner", "owner")
            ->join("owner.image", "image")
            ->join("o.products", "products")
            ->join("o.place", "place")
            ->join("o.dropPlace", "drop_place")
            ->join('o.conversation', 'conversation')
            ->setParameter("status", Order::STATUS_WAITING)
            ->select("o, owner, products, place, drop_place, image, conversation.id as conversation_id")
            ->setFirstResult(($page - 1) * 15)
            ->setMaxResults($page * 15)
            ->getQuery()
            ->getArrayResult();
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
