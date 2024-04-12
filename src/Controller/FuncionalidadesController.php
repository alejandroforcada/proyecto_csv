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
    public function index(ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        //busqueda de todos los datos en la BB.DD
        $repository = $entityManager->getRepository(Ordinadors::class);
        $ordenadors = $repository->findAll();
        // renderización de la plantilla
            return $this->render('funcionalidades/index.html.twig', [
                    'ordenadors' => $ordenadors,
                ]);
    }

    // Ruta para añadir el csv del tipo ordinador (normal) a la base de datos
    #[Route('/ordinador', name: 'ordinadors')]
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
        $entityManager->persist($object); 
       
    }
    // Insertamos todos los objetos en la base de datos
    $entityManager->flush();
    // Recargamos la página
    return $this->redirectToRoute('index');
    }
    // Ruta para añadir el csv del tipo mifi a la base de datos
    #[Route('/mifi', name: 'mifi')]
    public function mifi(ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {

    // Abrimos el csv y lo matemos en un arraymap
    $csv = array_map('str_getcsv', file('..\public\routersvodafon.csv'));

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
        //estos dos campos se rellenan con comillas y se saltan en el csv al ser más pequeño
        $object->setSistemaOperatiuNom("");
        $object->setSistemaOperatiuVersio("");
        $object->setEspaiDesti($item[10]);
        $object->setDescripcioEspaiDesti($item[11]);
        $entityManager->persist($object); 
       
    }
    // Insertamos todos los objetos en la base de datos
    $entityManager->flush();
    // Recargamos la página
    return $this->redirectToRoute('index');
    }


    //ruta para borrar todos los datos en la base de datos
    #[Route('/borrar', name: 'borrar')]
    public function borrar(ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Ordinadors::class);
        $ordinadors = $repository->findAll();
        // bucle que los borra
        foreach ($ordinadors as $ordinador){
            $entityManager->remove($ordinador);
        }
   
    
    $entityManager->flush();
    // Recargamos la página
    return $this->redirectToRoute('index');
    }

}
    



