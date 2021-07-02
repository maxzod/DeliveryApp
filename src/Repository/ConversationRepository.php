<?php

namespace App\Repository;

use App\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    /**
     * @param int $id
     * @param int $page
     * @return array|int|string
     */
    public function getConversationMessages(int $id, int $page): array|int|string
    {
        $qb =  $this->createQueryBuilder('conv');
        return $qb->where($qb->expr()->eq('conv.id', ':id'))
            ->join('conv.driver', 'driver')
            ->join('conv.client', 'client')
            ->join('conv.messages', 'messages')
            ->join('messages.sender', 'owner')
            ->select('conv, messages, owner, driver, client')
            ->setParameter('id', $id)
            ->orderBy('messages.id', 'desc')
            ->setFirstResult(($page - 1) * 15)
            ->setMaxResults($page * 15)
            ->getQuery()->getArrayResult();
    }


    /**
     * @param UserInterface $client
     * @param UserInterface $driver
     * @return mixed
     */
    public function findConversationByUsers(UserInterface $client, UserInterface $driver): mixed
    {
        $qb = $this->createQueryBuilder('c');
        return $qb
            ->where($qb->expr()->andX(
                $qb->expr()->eq('c.driver', ':driver'),
                $qb->expr()->eq('c.client', ':client')
            ))
            ->setParameter('driver', $driver)
            ->setParameter('client', $client)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
