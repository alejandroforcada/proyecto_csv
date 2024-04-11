<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ordinadors;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class FuncionalidadesController extends AbstractController
{
    // Ruta de la página principal

    #[Route('/', name: 'index')]
    public function index(): Response
    {
            return $this->render('funcionalidades/index.html.twig', [
            'controller_name' => 'FuncionalidadesController',
        ]);
    }

    // Ruta para añadir el csv del tipo ordinador (normal) a la base de datos
    #[Route('/ordinador', name: 'ordinador')]
    public function ordinador(ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {

    // Abrimos el csv y lo matemos en un arraymap
    $csv = array_map('str_getcsv', file('..\public\ordenadores.csv'));

    // Con este foreach descomponemos el arraymap(el csv) en las lineas del mismo
    foreach ($csv as $linea => $item) {

    // Separamos cada linea en sus diferentes campos con la función explode, 
    // OJO cada linea tiene dentro un array
    // con un solo unidad así que no podemos poner línea solo, tenemos que poner línea [0]
    $item  = explode(";", $item[0]);
       // Por cada ciclo en el foreach creamos un objeto de la clase Ordinador
       $object = new Ordinadors();
       // Añadimos cada campo al campo del csv separado a lade la base de datos
        $object->setNom($item[0]);
        $object->setEntitat($item[1]);
        $object->setCodiInventari($item[2]);
        $object->setEstat($item[3]);
        $object->setTipus($item[4]);
        $object->setCodiArticle($item[5]);
        $object->setDescripcioCodiArticle($item[6]);
        $object->setNumeroSerie($item[7]);
        $object->setFabricant($item[8]);
        $object->setModel($item[9]);
        $object->setSistemaOperatiuNom($item[10]);
        $object->setSistemaOperatiuVersio($item[11]);
        $object->setEspaiDesti($item[12]);
        $object->setDescripcioEspaiDesti($item[13]);
       $entityManager->persist($object); // meter en tabla 1
       
    }
    // Insertamos todos los objetos en la base de datos
    $entityManager->flush();
    // Cargamos la plantilla con el html y css
            return $this->render('funcionalidades/index.html.twig', [
            'controller_name' => 'FuncionalidadesController',
        ]);
    }

}
    



