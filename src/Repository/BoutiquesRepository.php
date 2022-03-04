<?php

namespace App\Repository;

use App\Entity\Boutiques;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Boutiques|null find($id, $lockMode = null, $lockVersion = null)
 * @method Boutiques|null findOneBy(array $criteria, array $orderBy = null)
 * @method Boutiques[]    findAll()
 * @method Boutiques[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoutiquesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Boutiques::class);
    }

    // /**
    //  * @return Boutiques[] Returns an array of Boutiques objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Boutiques
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
