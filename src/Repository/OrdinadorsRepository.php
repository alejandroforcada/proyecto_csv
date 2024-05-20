<?php

namespace App\Repository;

use App\Entity\Ordinadors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

    //query de los 20 primeros resultados segun la pagina
    public function paginate($dql,$page, $limit )
{
    $paginator = new Paginator($dql);

    $paginator->getQuery()
    // a partir del numero de pagina y el limite crea las paginas
        ->setFirstResult($limit * ($page - 1)) // Offset
        ->setMaxResults($limit); // Limit

    return $paginator;
}

//creamos la query
public function getAllPers($currentPage, $limit )
{
    
    $query = $this->createQueryBuilder('p')
        ->getQuery();


    $paginator = $this->paginate($query, $currentPage, $limit);

    return array('paginator' => $paginator, 'query' => $query);
}

  
   
    public function findElegidos(): array
    {
      return $this->createQueryBuilder('o')
          ->select('o.Nom, o.Entitat, o.Codi_inventari, o.Estat, o.Tipus, o.Codi_article, o.Descripcio_codi_article, o.Numero_serie, o.Fabricant, o.Model, o.Sistema_operatiu_nom, o.Sistema_operatiu_versio, o.Espai_desti, o.Descripcio_espai_desti')
          ->andWhere('o.elegido = :val')
          ->setParameter('val', 1)
          ->orderBy('o.id', 'ASC')
          ->getQuery()
          ->getResult()
       ;
   }

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
