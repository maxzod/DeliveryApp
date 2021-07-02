<?php

namespace App\Repository;

use App\Entity\OrderPlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderPlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderPlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderPlace[]    findAll()
 * @method OrderPlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderPlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderPlace::class);
    }

    // /**
    //  * @return Place[] Returns an array of Place objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Place
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
