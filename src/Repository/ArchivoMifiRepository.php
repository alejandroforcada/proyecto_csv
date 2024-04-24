<?php

namespace App\Repository;

use App\Entity\ArchivoMifi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArchivoMifi>
 *
 * @method ArchivoMifi|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArchivoMifi|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArchivoMifi[]    findAll()
 * @method ArchivoMifi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchivoMifiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArchivoMifi::class);
    }

//    /**
//     * @return ArchivoMifi[] Returns an array of ArchivoMifi objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

public function findOneFileMifi($value): ?ArchivoMifi
{
    return $this->createQueryBuilder('a')
       ->andWhere('a.file = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getOneOrNullResult()
    ;
}
}
