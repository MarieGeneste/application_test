<?php

namespace App\Repository;

use App\Entity\FrontUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FrontUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method FrontUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method FrontUser[]    findAll()
 * @method FrontUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FrontUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FrontUser::class);
    }

    // /**
    //  * @return FrontUser[] Returns an array of FrontUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FrontUser
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
