<?php

namespace App\Repository;

use App\Entity\DropPlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DropPlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method DropPlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method DropPlace[]    findAll()
 * @method DropPlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DropPlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DropPlace::class);
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
