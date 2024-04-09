<?php

namespace App\Repository;

use App\Entity\Ordinadors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ordinadors>
 *
 * @method Ordinadors|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ordinadors|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ordinadors[]    findAll()
 * @method Ordinadors[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdinadorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ordinadors::class);
    }

//    /**
//     * @return Ordinadors[] Returns an array of Ordinadors objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ordinadors
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
