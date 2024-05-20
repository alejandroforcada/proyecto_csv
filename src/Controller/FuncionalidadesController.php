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
use App\Form\CheckboxfalseType;
use App\Form\CheckboxtrueType;
use App\Form\TextoDescargaType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class FuncionalidadesController extends AbstractController
{
    // Ruta de la página principal

    #[Route('/', name: 'index')]
    public function index(Request $request,SluggerInterface $slugger,ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
{
    $arrayforms=[];
    $formType="Verdadero";
   
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
    $csv = array_map(function($row) {
        return str_getcsv($row, ';', '"', '\\');
    }, file('..\public\csv\\' . $campo ));
    
   
    
    
    // Con este foreach descomponemos el arraymap(el csv) en las lineas del mismo
    foreach ($csv as $linea => $item) {
    
    // Separamos cada linea en sus diferentes campos con la función explode, 
    // OJO cada linea tiene dentro un array
    // con un solo unidad así que no podemos poner línea solo, tenemos que poner línea [0]
    
    if (isset($item[13])){
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
        $object->setElegido(false);
        $entityManager->persist($object); 
       
    }
    elseif (isset($item[11])){
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
        $object->setElegido(false);
        $entityManager->persist($object);  
        
     }
    // Insertamos todos los objetos en la base de datos
    $entityManager->flush();
    // Recargamos la página
    
    }
    return $this->redirectToRoute('index');
}
$formularioTexto = $this->createForm(TextoDescargaType::class);
$formularioTexto->handleRequest($request);
if ($formularioTexto->isSubmitted() && $formularioTexto->isValid()) {
    $texto = $formularioTexto->get('texto')->getData();
    return $this->redirectToRoute('exportar',
    ['texto' => $texto]);
}


  //utilizamos Request para acceder a la variable current page
  $currentPage = $request->query->getInt('currentPage', 1);
  //numeros por página
  $limit = 20;
  //llamaos ak metodo getAllpers en el repositorio
  $Ordinadors = $entityManager->getRepository(Ordinadors::class,null)->getAllPers($currentPage, $limit);
  $OrdinadorsResultado = $Ordinadors['paginator'];
  $OrdinadorsQueryCompleta =  $Ordinadors['query'];
  $i=0;
  foreach($OrdinadorsResultado as $OrdinadorCheckbox){
    $elegido = $OrdinadorCheckbox->isElegido();
    $formType = ($elegido == 1) ? "Verdadero" : "Falso";
    //cambiada
    $arrayforms[$i] = $formType;
  $i=$i+1;
}

  //numero máximo de páginas
  $maxPages = ceil($Ordinadors['paginator']->count() / $limit);

  //envimaos los datos a la plantilla
  return $this->render('funcionalidades/index.html.twig', array(
        'ordenadors' => $OrdinadorsResultado,
        'maxPages'=>$maxPages,
        'thisPage' => $currentPage,
        'all_items' => $OrdinadorsQueryCompleta,
        'formulario' => $formularioOrdinadors->createView(),
        'formulariocheckbox'=>$arrayforms,
        'formularioTexto' => $formularioTexto->createView()
    ) );
}

#[Route('/extendido', name: 'extendido')]
    public function extendido(Request $request,ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
{
    $arrayforms=[];
    $formType="Verdadero";
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
    $csv = array_map(function($row) {
        return str_getcsv($row, ';', '"', '\\');
    }, file('..\public\csv\\' . $campo ));
    
    
    
    // Con este foreach descomponemos el arraymap(el csv) en las lineas del mismo
    foreach ($csv as $linea => $item) {

    // Separamos cada linea en sus diferentes campos con la función explode, 
    // OJO cada linea tiene dentro un array
    // con un solo unidad así que no podemos poner línea solo, tenemos que poner línea [0]
   
    if (isset($item[13])){
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
        $object->setElegido(false);
        $entityManager->persist($object); 
       
    }
    elseif (isset($item[11])){
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
        $object->setElegido(false);
        $entityManager->persist($object);  
        
     }
    // Insertamos todos los objetos en la base de datos
    $entityManager->flush();
    // Recargamos la página
    
    }
    return $this->redirectToRoute('index');
}

  //utilizamos Request para acceder a la variable current page
  $currentPage = $request->query->getInt('currentPage', 1);
  //numeros por página
  $limit = 20;
  //llamaos ak metodo getAllpers en el repositorio
  $Ordinadors = $entityManager->getRepository(Ordinadors::class,null)->getAllPers($currentPage, $limit);
  $OrdinadorsResultado = $Ordinadors['paginator'];
  $OrdinadorsQueryCompleta =  $Ordinadors['query'];
  $i=0;
  foreach($OrdinadorsResultado as $OrdinadorCheckbox){
    $elegido = $OrdinadorCheckbox->isElegido();
    $formType = ($elegido == 1) ? "Verdadero" : "Falso";
    //cambiada
    $arrayforms[$i] = $formType;
  $i=$i+1;
}

  //numero máximo de páginas
  $maxPages = ceil($Ordinadors['paginator']->count() / $limit);

  //envimaos los datos a la plantilla
  return $this->render('funcionalidades/extendido.html.twig', array(
        'ordenadors' => $OrdinadorsResultado,
        'maxPages'=>$maxPages,
        'thisPage' => $currentPage,
        'all_items' => $OrdinadorsQueryCompleta,
        'formulario' => $formularioOrdinadors->createView(),
        'formulariocheckbox'=>$arrayforms,
        'formularioTexto' => $formularioTexto->createView()
    ) );
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

    #[Route('/limpiar', name: 'limpiar')]
    public function limpiar(ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Ordinadors::class);
        $ordinadors = $repository->findAll();
        // bucle que los borra
        foreach ($ordinadors as $ordinador){
            $ordinador=$ordinador->setElegido(0);
            
        } 
    
    $entityManager->flush();
    // Recargamos la página
    return $this->redirectToRoute('index');
    }

    #[Route('/verdadero', name: 'verdadero')]
    public function verdadero(Request $request,SluggerInterface $slugger,ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
{
    $tipo = $request->query->getInt('tipo');
    $currentPage = $request->query->getInt('currentPage', 1);
    $repository = $entityManager->getRepository(Ordinadors::class);
    $id = $request->query->getInt('id');
        $ordinador = $repository->find($id);
        $ordinador= $ordinador->setElegido(0);
        $entityManager->persist($ordinador);
    
    $entityManager->flush();
    // Recargamos la página
    if ($tipo==0){
        return $this->redirectToRoute('index',
             ['currentPage' => $currentPage]);
        }
        else{
            return $this->redirectToRoute('extendido',
            ['currentPage' => $currentPage]);
        }

}
#[Route('/falso', name: 'falso')]
public function falso(Request $request,SluggerInterface $slugger,ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
{
$currentPage = $request->query->getInt('currentPage', 1);
$repository = $entityManager->getRepository(Ordinadors::class);
$id = $request->query->getInt('id');
$tipo = $request->query->getInt('tipo');
    $ordinador = $repository->find($id);
    $ordinador= $ordinador->setElegido(1);
    $entityManager->persist($ordinador);

$entityManager->flush();
// Recargamos la página
if ($tipo==0){
return $this->redirectToRoute('index',
     ['currentPage' => $currentPage]);
}
else{
    return $this->redirectToRoute('extendido',
    ['currentPage' => $currentPage]);
}  
}
#[Route('/exportar', name: 'exportar')]
public function exportar(Request $request,SluggerInterface $slugger,ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
{
$filename= $request->query->get('texto');
$filename=$filename . '.csv';
 $options = [
        'csv_delimiter' => ';', 
    ];

$repository = $entityManager->getRepository(Ordinadors::class);
$repository= $repository->findElegidos();

$rootDir = realpath('..\public\csvexportar\\');
$fullPath = realpath($rootDir . '/' . $filename);
$encoders = [new CsvEncoder()];
$normalizers = array(new ObjectNormalizer());
$serializer = new Serializer($normalizers, $encoders);
$csvContent = $serializer->serialize($repository, 'csv',$options);

$response = new Response($csvContent);
$response->headers->set('Content-Encoding', 'UTF-8');
$response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
$response->headers->set('Content-Disposition', 'attachment; filename='. basename($filename));
return $response;


}

}

    



