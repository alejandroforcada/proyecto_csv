<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ordinadors;
use App\Entity\Archivo;
use App\Entity\ArchivoMifi;
use App\Form\CsvType;
use App\Form\CsvMifiType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class FuncionalidadesController extends AbstractController
{
    // Ruta de la página principal

    #[Route('/', name: 'index')]
    public function index(Request $request,SluggerInterface $slugger,ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
{
    $archivoOrdinadors = new Archivo();
$formularioOrdinadors = $this->createForm(CsvType::class, $archivoOrdinadors);
$formularioOrdinadors->handleRequest($request);
if ($formularioOrdinadors->isSubmitted() && $formularioOrdinadors->isValid()) {
    $archivoOrdinadors = $formularioOrdinadors->getData();
    $file = $formularioOrdinadors->get('file')->getData();
    if ($file) {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        // Move the file to the directory where images are stored
        try {

            $file->move(
                $this->getParameter('csv_directory'), $newFilename
            );                      
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            return new Response($e->getMessage());
        }

        // updates the 'file$filename' property to store the PDF file name
        // instead of its contents
        $archivoOrdinadors->setFile($newFilename);
    }
    $entityManager = $doctrine->getManager();
    $entityManager->persist($archivoOrdinadors);
    $entityManager->flush();
    // Abrimos el csv y lo matemos en un arraymap
    $archivoOrdinadors = $entityManager->getRepository(Archivo::class);
    $archivoOrdinadors= $archivoOrdinadors->findOneFile($newFilename);
    $campo = $archivoOrdinadors->getFile();
    $csv = array_map('str_getcsv', file('..\public\csv\\'. $campo ));
    
 
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
    $archivoMifi = new ArchivoMifi();
$formularioMifi = $this->createForm(CsvMifiType::class, $archivoMifi);
$formularioMifi->handleRequest($request);
if ($formularioMifi->isSubmitted() && $formularioMifi->isValid()) {
    $archivoMifi = $formularioMifi->getData();
    $file = $formularioMifi->get('file')->getData();
    if ($file) {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        // Move the file to the directory where images are stored
        try {

            $file->move(
                $this->getParameter('csv_directory'), $newFilename
            );                      
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            return new Response($e->getMessage());
        }

        // updates the 'file$filename' property to store the PDF file name
        // instead of its contents
        $archivoMifi->setFile($newFilename);
    }
    $entityManager = $doctrine->getManager();
    $entityManager->persist($archivoMifi);
    $entityManager->flush();
    // Abrimos el csv y lo matemos en un arraymap
    $archivoMifi = $entityManager->getRepository(ArchivoMifi::class);
    $archivoMifi= $archivoMifi->findOneFileMifi($newFilename);
    $campo = $archivoMifi->getFile();
    $csv = array_map('str_getcsv', file('..\public\csv\\'. $campo ));
    

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
  //utilizamos Request para acceder a la variable current page
  $currentPage = $request->query->getInt('currentPage', 1);
  //numeros por página
  $limit = 20;
  //llamaos ak metodo getAllpers en el repositorio
  $Ordinadors = $entityManager->getRepository(Ordinadors::class)->getAllPers($currentPage, $limit);
  $OrdinadorsResultado = $Ordinadors['paginator'];
  $OrdinadorsQueryCompleta =  $Ordinadors['query'];

  //numero máximo de páginas
  $maxPages = ceil($Ordinadors['paginator']->count() / $limit);

  //envimaos los datos a la plantilla
  return $this->render('funcionalidades/index.html.twig', array(
        'ordenadors' => $OrdinadorsResultado,
        'maxPages'=>$maxPages,
        'thisPage' => $currentPage,
        'all_items' => $OrdinadorsQueryCompleta,
        'formulario' => $formularioOrdinadors->createView(),
        'formularioMifi' => $formularioMifi->createView()
    ) );
}

#[Route('/extendido', name: 'extendido')]
    public function extendido(Request $request,ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
{
    $archivo = new Archivo();
    $formularioOrdinadors = $this->createForm(CsvType::class, $archivo);
    $formularioOrdinadors->handleRequest($request);
    if ($formularioOrdinadors->isSubmitted() && $formularioOrdinadors->isValid()) {
        $archivo = $formularioOrdinadors->getData();
        $entityManager = $doctrine->getManager();
        $entityManager->persist($archivo);
        $entityManager->flush();
        // Abrimos el csv y lo matemos en un arraymap
        $archivo = $entityManager->getRepository(Archivo::class);
        $csv = array_map('str_getcsv', file('..\public\\'. $archivo ));
        
    
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
        $archivoMifi = new ArchivoMifi();
    $formularioMifi = $this->createForm(CsvType::class, $archivoMifi);
    $formularioMifi->handleRequest($request);
    if ($formularioMifi->isSubmitted() && $formularioMifi->isValid()) {
        $archivoMifi = $formularioMifi->getData();
        $entityManager = $doctrine->getManager();
        $entityManager->persist($archivoMifi);
        $entityManager->flush();
        // Abrimos el csv y lo matemos en un arraymap
        $archivoMifi = $entityManager->getRepository(ArchivoMifi::class);
        $csv = array_map('str_getcsv', file('..\public\\'. $archivoMifi ));
        
    
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
      //utilizamos Request para acceder a la variable current page
      $currentPage = $request->query->getInt('currentPage', 1);
      //numeros por página
      $limit = 20;
      //llamaos ak metodo getAllpers en el repositorio
      $Ordinadors = $entityManager->getRepository(Ordinadors::class)->getAllPers($currentPage, $limit);
      $OrdinadorsResultado = $Ordinadors['paginator'];
      $OrdinadorsQueryCompleta =  $Ordinadors['query'];
    
      //numero máximo de páginas
      $maxPages = ceil($Ordinadors['paginator']->count() / $limit);

  //envimaos los datos a la plantilla
  return $this->render('funcionalidades/extendido.html.twig', array(
        'ordenadors' => $OrdinadorsResultado,
        'maxPages'=>$maxPages,
        'thisPage' => $currentPage,
        'all_items' => $OrdinadorsQueryCompleta,
        'formulario' => $formulario->createView(),
        'formularioMifi' => $formularioMifi->createView()
    ) );
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
        $repository = $entityManager->getRepository(Archivo::class);
        $archivos = $repository->findAll();
        // bucle que los borra
        foreach ($archivos as $archivo){
            $entityManager->remove($archivo);
        }$repository = $entityManager->getRepository(ArchivoMifi::class);
        $archivosMifi = $repository->findAll();
        // bucle que los borra
        foreach ($archivosMifi as $archivoMifi){
            $entityManager->remove($archivoMifi);
        }
   
    
    $entityManager->flush();
    // Recargamos la página
    return $this->redirectToRoute('index');
    }

}
    



