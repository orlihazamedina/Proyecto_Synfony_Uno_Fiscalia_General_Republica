<?php

namespace UnoMainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UnoMainBundle\Util\Util;
use UnoMainBundle\Util\Excel;
use UnoMainBundle\Entity\Expediente;
use Symfony\Component\HttpFoundation\Response;
use UnoMainBundle\Entity\Tramitacion;
use UnoMainBundle\Entity\Traza;

error_reporting(0);
class DefaultController extends Controller
{
    
    public function estadisticaAction(){
        $em = $this->getDoctrine()->getManager();
         $cantidad = $em->getRepository('UnoMainBundle:Traza')->findAll();  
        $posi=3;
        
        
       $d=count($cantidad)-1;
       
        $municipios=array('hvieja','centrohabana','plaza','cerro','playa','marianao','lisa','boyeros','arroyo','10Octubre','sanmiguel','cotorro','regla','guanabacoa','h.este','divico') ;       
       
        for($i=0;$i<=15;$i++){
         $trami = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$i]));   
         $canti[$i]=count($trami);   
            
     
            
        }
       $val[0]=0;
         $val[1]=0;
          $val[2]=0;
           $val[3]=0;
             $val[4]=0;
           $munic[0]='';
       
        for($j=0;$j<=4;$j++){
        for($i=0;$i<=15;$i++){
             $trami = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$i]));   
      
             if(count($trami)>$val[$j]){
             $val[$j]=  count($trami);
             $munic[$j]=$municipios[$i];
             $posi=$i;
        }
             
             
         }  
            
        $municipios[$posi]="";   
            
            
        }
        
       
       
        
        return $this->render('UnoMainBundle:Default:estadistica.html.twig',array('d' => $d,'cantidad'=>$cantidad,'canti'=>$canti,'val'=>$val,'munic'=>$munic));  
        
        
    }
    public function indexAction($name)
    {
        return $this->render('UnoMainBundle:Default:index.html.twig', array('name' => $name));
    }
 
     public function homeAction()
    {
          $response = $this->forward('UnoMainBundle:Default:orli', array(
        
        'index' => '1'
    ));

    // ... adicionalmente modifica la respuesta o la devuelve
    //     directamente

    return $response;
        // DefaultController::orliAction(1);  
    }
    
public function usuariosAction(){
    
    $em = $this->getDoctrine()->getManager();
		$productos = $em->getRepository('UnoMainBundle:User')->findAll(); 
    
    return $this->render('UnoMainBundle:User:usuario.html.twig', array("productos"=>$productos));    
    
    
}
public function orliAction($index)
    {
    $termi[0]="";
      $em = $this->getDoctrine()->getManager();
	//	$productos = $em->getRepository('UnoMainBundle:Expediente')->findBytipoExpediente("Tramitacion");
   $repository = $em->getRepository('UnoMainBundle:Expediente'); 
   $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
   $us=$this->get('security.context')->getToken()->getUser()->getUsername();
   if($usuario=="ROLE_USER"){
    $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio')->setParameter('expediente', 'Tramitacion')->setParameter('municipio',$us)  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $producto = $query->getResult();  
   }else
       {
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente')->setParameter('expediente', 'Tramitacion')  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productog = $query->getResult();    
   }
    $c=0;
      $conteo=0;
      $d=0;
      
      if($usuario=="ROLE_ADMIN"){
          $r=0;
          foreach ($productog as $item){
            if(Util::dias($item->getFechaInicial())>60){
         $producto[$r]=$item;
         $r++;
          
      } 
      
      
      }}
      
      
       foreach ( $producto as $item){
         
           $conteo++;
           
           
           
           if(Util::mayor($item->getPronostico(),date('Y-m-d'))){
               
               	return $this->redirect($this->generateUrl('uno_main_homeall'));
               
               
               
           }
       }
      
      $productos= Util::segmento($producto,$index);
      if($productos){          
      foreach ( $productos as $item){
                    
                  $termi[$c]=Util::dias($item->getFechaInicial()); 
                   
                  $c++;
                }
        $d=floor( $conteo/15);
        if($d*15<$conteo)
            $d+=1;
      }
      $active="active";
      
        $tipo="trami";
        $k=0;
    return $this->render('UnoMainBundle:User:home.html.twig', array("k"=>$k,"productos"=>$productos,"termi"=>$termi,'d'=>$d,'index'=>$index,'active'=>$active,'conteo'=>$conteo,'tipo'=>$tipo));   
       
        
        
    }
     public function filtroAction($muni,$donde){
          $termi[0]="";
            $entre[0]="";
    
   $repository = $this->getDoctrine() ->getRepository('UnoMainBundle:Expediente'); 
         if($donde=='trami'){
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio' )->setParameter('expediente', 'Tramitacion')->setParameter('municipio',$muni)   ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productos = $query->getResult();   
     
     
     
   
    $c=0;
      $conteo=0;
      $d=0;
       foreach ( $productos as $item){
             if(Util::dias($item->getFechaInicial())>60){
           $conteo++;
           
             }
       }
      
      
      if($productos){          
      foreach ( $productos as $item){
                    
                  $termi[$c]=Util::dias($item->getFechaInicial()); 
                   
                  $c++;
      }
      $active="active";  
       $tipo="trami";
       $filtro="llklklkl";
        return $this->render('UnoMainBundle:User:homeall.html.twig', array("filtro"=>$filtro,"productos"=>$productos,"termi"=>$termi,'active'=>$active,'conteo'=>$conteo,'tipo'=>$tipo)); 
      
      }else{
         $successMessage = $this->get('translator')->trans('No se encuentra ningun expediente del municipio '.$muni);
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_homepage')); 
         }}
     else
         {
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio' )->setParameter('expediente', 'Despacho')->setParameter('municipio',$muni)  ->orderBy('p.fechainicial', 'ASC')->getQuery();
     $productos= $query->getResult();    
                                                                                                                                                                          
      $c=0;
      $conte=0;
      $d=0;
       foreach ( $productos as $item){
           $conte++;
       }
      
      
      if($productos){          
      foreach ( $productos as $item){
                    
                  $termi[$c]=Util::dias($item->getFechaInicial()); 
                   $entre[$c]=Util::dias($item->getFechaEntrega()); 
                  $c++;
                }
      $actived='active';
      $tipo="despa";
     return $this->render('UnoMainBundle:User:despall.html.twig', array("productos"=>$productos,"termi"=>$termi,"entre"=>$entre,'actived'=>$actived,'conte'=>$conte,'tipo'=>$tipo));   
     }else{
         
     $successMessage = $this->get('translator')->trans('No se ha encontrado ningun expediente del municipio '.$muni);
                $this->addFlash('mensaje', $successMessage);
                  return DefaultController::despaAction(1);       
         
     }
      
        
       
            
     }
         
     }
     public function homeallAction(){
          $termi[0]="";
     // $em = $this->getDoctrine()->getManager();
	//	$productos = $em->getRepository('UnoMainBundle:Expediente')->findBytipoExpediente("Tramitacion");
   $repository = $this->getDoctrine() ->getRepository('UnoMainBundle:Expediente'); 
   $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
   $us=$this->get('security.context')->getToken()->getUser()->getUsername();
   if($usuario=="ROLE_USER"){
    $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio')->setParameter('expediente', 'Tramitacion')->setParameter('municipio',$us)  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productos = $query->getResult();  
   }else
       {
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente')->setParameter('expediente', 'Tramitacion')  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productog = $query->getResult();    
   }
   
   
    if($usuario=="ROLE_ADMIN"){
          $r=0;
          foreach ($productog as $item){
            if(Util::dias($item->getFechaInicial())>60){
         $productos[$r]=$item;
         $r++;
          
      } 
      
      
      }}
   
   
    $c=0;
      $conteo=0;
      $d=0;$nj=0;$k=0;
       foreach ( $productos as $item){
           if(Util::mayor($item->getPronostico(),date('Y-m-d'))){
               
               $mayor[$nj]=1;
               $nj++;
               $k++;
               
               
               
               
           }else
           {
               $mayor[$nj]=0;
               
              $nj++; 
               
               
           }
           
           
           
           $conteo++;
       }
      
      
      if($productos){          
      foreach ( $productos as $item){
                    
                  $termi[$c]=Util::dias($item->getFechaInicial()); 
                   
                  $c++;
      }}
       $active="active";  
      $tipo="trami";$filtro='filtro';
        return $this->render('UnoMainBundle:User:homeall.html.twig', array("mayor"=>$mayor,"k"=>$k,"nj"=>$nj,"filtro"=>$filtro,"productos"=>$productos,"termi"=>$termi,'active'=>$active,'conteo'=>$conteo,'tipo'=>$tipo));   
       
       
     }
      public function despallAction(){
       $termi[0]="";
     $entre[0]="";
      $repository = $this->getDoctrine() ->getRepository('UnoMainBundle:Expediente'); 
   $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
   $us=$this->get('security.context')->getToken()->getUser()->getUsername();
   if($usuario=="ROLE_USER"){
    $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio')->setParameter('expediente', 'Despacho')->setParameter('municipio',$us)  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productos = $query->getResult();  
     
       
   }else
       {
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente')->setParameter('expediente', 'Despacho')  ->orderBy('p.fechainicial', 'ASC')->getQuery();
     $productog= $query->getResult();    
   }     
   
    if($usuario=="ROLE_ADMIN"){
          $r=0;
          foreach ($productog as $item){
            if(Util::dias($item->getFechaInicial())>60){
         $productos[$r]=$item;
         $r++;
          
      } 
      
      
      }}
   
   
      $c=0;
      $conte=0;
      $d=0;$nj=0;$ky=0;
       foreach ( $productos as $item){
             if(Util::mayor($item->getPronostico(),date('Y-m-d'))){
               
               $mayor[$nj]=1;
               $nj++;
               $ky++;
               
               
               
               
           }else
           {
               $mayor[$nj]=0;
               
              $nj++; 
               
               
           }
           $conte++;
       }
      
      
      if($productos){          
      foreach ( $productos as $item){
                    
                  $termi[$c]=Util::dias($item->getFechaInicial()); 
                   $entre[$c]=Util::dias($item->getFechaEntrega()); 
                  $c++;
                }
      
      }
      $actived='active';
      $tipo="despa";
     return $this->render('UnoMainBundle:User:despall.html.twig', array("mayor"=>$mayor,"ky"=>$ky,"nj"=>$nj,"productos"=>$productos,"termi"=>$termi,"entre"=>$entre,'actived'=>$actived,'conte'=>$conte,'tipo'=>$tipo));   
        
       
        
            
          
      }
        public function findAction(){
            
            $entre[0]='';
            $nombre=$this->get('request')->request->get('expe');
            $repository = $this->getDoctrine() ->getRepository('UnoMainBundle:Expediente'); 
            
   $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
   $us=$this->get('security.context')->getToken()->getUser()->getUsername();
   if($usuario=="ROLE_USER"){
    $query = $repository->createQueryBuilder('p') ->where('p.numExpediente LIKE :expediente and p.municipio=:municipio')->setParameter('expediente',$nombre)->setParameter('municipio',$us)  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productos = $query->getResult();  
   }else
       {
      $query = $repository->createQueryBuilder('p') ->where('p.numExpediente LIKE :expediente ')->setParameter('expediente',$nombre)  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productog = $query->getResult();    
   }
    if($productos){     
        
        
         if($usuario=="ROLE_ADMIN"){
          $r=0;
          foreach ($productog as $item){
            if(Util::dias($item->getFechaInicial())>60){
         $productos[$r]=$item;
         $r++;
          
      } 
      
      
      }}
      
                    
                  $termi[0]=Util::dias($productos[0]->getFechaInicial()); 
                  if($productos[0]->getTipoExpediente()){
                  $entre[0]=Util::dias($productos[0]->getFechaEntrega()); }
                  
    
   $d=0;
   $index=1;
       if($productos[0]->getTipoExpediente()=="Tramitacion"){
           $tipo='trami';
            $conteo=1;
     return $this->render('UnoMainBundle:User:home.html.twig', array("productos"=>$productos,"termi"=>$termi,'d'=>$d,'index'=>$index,'tipo'=>$tipo,"conteo"=>$conteo));   
                        } 
                             $tipo='despa';
                        $conte=1;
                           return $this->render('UnoMainBundle:User:despa.html.twig', array("productos"=>$productos,"termi"=>$termi,"entre"=>$entre,'d'=>$d,'index'=>$index,'tipo'=>$tipo,"conte"=>$conte));        
		}
     $successMessage = $this->get('translator')->trans('No existe ningun expediente con nombre '.$nombre);
                $this->addFlash('mensaje', $successMessage);
		return $this->render('UnoMainBundle:User:view.html.twig');        
                }
            
                
       
    
    public function orderAction($parametro,$index)
    {
        $c=0;
$termi[0]='';

        // $em = $this->getDoctrine()->getManager();
	//	$productos = $em->getRepository('UnoMainBundle:Expediente')->findBytipoExpediente("Tramitacion");
   $repository = $this->getDoctrine() ->getRepository('UnoMainBundle:Expediente'); 
   $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
   $us=$this->get('security.context')->getToken()->getUser()->getUsername();
   if($usuario=="ROLE_USER"){
    $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio')->setParameter('expediente', 'Tramitacion')->setParameter('municipio',$us)  ->orderBy('p.'.$parametro, 'ASC') ->getQuery();
     $producto = $query->getResult();  
   }else
       {
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente')->setParameter('expediente', 'Tramitacion')  ->orderBy('p.'.$parametro, 'ASC') ->getQuery();
     $productog = $query->getResult();    
   }
   
   
    if($usuario=="ROLE_ADMIN"){
          $r=0;
          foreach ($productog as $item){
            if(Util::dias($item->getFechaInicial())>60){
         $producto[$r]=$item;
         $r++;
          
      } 
      
      
      }}
    $c=0;
      $conteo=0;
      $d=0;
       foreach ( $producto as $item){
           $conteo++;
       }
      
      $productos= Util::segmento($producto,$index);
      if($productos){          
      foreach ( $productos as $item){
                    
                  $termi[$c]=Util::dias($item->getFechaInicial()); 
                   
                  $c++;
                }
        $d=floor( $conteo/15);
        if($d*15<$conteo)
            $d+=1;
      }
      $active="active";
       $tipo='trami';
    return $this->render('UnoMainBundle:User:homev.html.twig', array("productos"=>$productos,"termi"=>$termi,'d'=>$d,'index'=>$index,'active'=>$active,'parametro'=>$parametro,'tipo'=>$tipo,'conteo'=>$conteo));   
       
        
        
    }
    public function orderdAction($parametro,$index)
    {
        $c=0;
$termi[0]="";
$entre[0]="";

        // $em = $this->getDoctrine()->getManager();
	//	$productos = $em->getRepository('UnoMainBundle:Expediente')->findBytipoExpediente("Tramitacion");
   $repository = $this->getDoctrine() ->getRepository('UnoMainBundle:Expediente'); 
   $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
   $us=$this->get('security.context')->getToken()->getUser()->getUsername();
   if($usuario=="ROLE_USER"){
    $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio')->setParameter('expediente', 'Despacho')->setParameter('municipio',$us)  ->orderBy('p.'.$parametro, 'ASC') ->getQuery();
     $producto = $query->getResult();  
   }else
       {
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente')->setParameter('expediente', 'Despacho')  ->orderBy('p.'.$parametro, 'ASC') ->getQuery();
     $productog = $query->getResult();    
   }    
    if($usuario=="ROLE_ADMIN"){
          $r=0;
          foreach ($productog as $item){
            if(Util::dias($item->getFechaInicial())>60){
         $producto[$r]=$item;
         $r++;
          
      } 
      
      
      }}
   
      $c=0;
      $conte=0;
      $d=0;
       foreach ( $producto as $item){
           $conte++;
       }
      
      $productos= Util::segmento($producto,$index);
      if($productos){          
      foreach ( $productos as $item){
                    
                  $termi[$c]=Util::dias($item->getFechaInicial()); 
                   $entre[$c]=Util::dias($item->getFechaEntrega()); 
                  $c++;
                }
        $d=floor( $conte/15);
        if($d*15<$conte)
            $d+=1;
      }
      $actived='active';
      $tipo="despa";
     return $this->render('UnoMainBundle:User:despav.html.twig', array("productos"=>$productos,"termi"=>$termi,"entre"=>$entre,'d'=>$d,'index'=>$index,'actived'=>$actived,"parametro"=>$parametro,'tipo'=>$tipo,'conte'=>$conte));
        
       
        
        
    }
    
public function despaAction($index)
        
    {
    
    $termi[0]="";
     $entre[0]="";
      $repository = $this->getDoctrine() ->getRepository('UnoMainBundle:Expediente'); 
   $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
   $us=$this->get('security.context')->getToken()->getUser()->getUsername();
   if($usuario=="ROLE_USER"){
    $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio')->setParameter('expediente', 'Despacho')->setParameter('municipio',$us)  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $producto = $query->getResult();  
     
       
   }else
       {
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente')->setParameter('expediente', 'Despacho')  ->orderBy('p.fechainicial', 'ASC')->getQuery();
     $productog= $query->getResult();    
   }     
   
    if($usuario=="ROLE_ADMIN"){
          $r=0;
          foreach ($productog as $item){
            if(Util::dias($item->getFechaInicial())>60){
         $producto[$r]=$item;
         $r++;
          
      } 
      
      
      }}
      $c=0;
      $conte=0;
      $d=0;
       foreach ( $producto as $item){
           $conte++;
           
             if(Util::mayor($item->getPronostico(),date('Y-m-d'))){
               
               	return $this->redirect($this->generateUrl('uno_main_despall'));
               
               
               
           }
       }
      
      $productos= Util::segmento($producto,$index);
      if($productos){          
      foreach ( $productos as $item){
                    
                  $termi[$c]=Util::dias($item->getFechaInicial()); 
                   $entre[$c]=Util::dias($item->getFechaEntrega()); 
                  $c++;
                }
        $d=floor( $conte/15);
        if($d*15<$conte)
            $d+=1;
      }
      $actived='active';
      $tipo="despa";
      $ky=0;
     return $this->render('UnoMainBundle:User:despa.html.twig', array("ky"=>$ky,"productos"=>$productos,"termi"=>$termi,"entre"=>$entre,'d'=>$d,'index'=>$index,'actived'=>$actived,'conte'=>$conte,"tipo"=>$tipo));   
        
       
        
        
    }
  
   
    public function excelAction(Request $request){
       
        
        
       // solicitamos el servicio 'phpexcel' y creamos el objeto vacío...
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       
        // ...y le asignamos una serie de propiedades
        $phpExcelObject->getProperties()
            ->setCreator("Vabadus")
            ->setLastModifiedBy("Vabadus")
            ->setTitle("Ejemplo de exportación")
            ->setSubject("Ejemplo")
            ->setDescription("Listado de ejemplo.")
            ->setKeywords("vabadus exportar excel ejemplo");
         

      // establecemos como hoja activa la primera, y le asignamos un título
        $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet()->setTitle('Tramitacion mas 60 dias');
     $horaactual=strval(date('d-m-Y'));
         $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
        $fiscalia=$this->get('security.context')->getToken()->getUser()->getNombre();
        // escribimos en distintas celdas del documento el título de los campos que vamos a exportar
       
              
                if( $usuario=="ROLE_ADMIN")
              $phpExcelObject->setActiveSheetIndex(0)    ->setCellValue('B1', 'FISCALIA PROVINCIAL DE LA HABANA');
                        else
                $phpExcelObject->setActiveSheetIndex(0)     ->setCellValue('B1', 'Fiscalia Municipal '.$fiscalia) ; 
               $phpExcelObject->setActiveSheetIndex(0)         
                ->setCellValue('C4', 'FECHA:')
                ->setCellValue('D4', $horaactual)
                 ->setCellValue('G3', 'EXPEDIENTES EN TRAMITACION')
                 ->setCellValue('B6', 'No.')
            ->setCellValue('C6', 'No-EFP')
            ->setCellValue('D6', 'MUNICIPIO')
                ->setCellValue('E6', 'UNIDAD')
           
            ->setCellValue('F6', 'ACUSADOS')
                  ->setCellValue('G6', 'DELITO')
            ->setCellValue('H6', 'FECHA/I')
            
            ->setCellValue('I6', 'TERM-TRAMITACION')
          ->setCellValue('J6', 'DEV-TTP')
            ->setCellValue('K6', 'SIS')
         ->setCellValue('L6', 'DEV-FISCAL')
            
          ->setCellValue('M6', 'PRONOSTICO')
         ->setCellValue('N6', 'OBSERVACIONES');
   

        // fijamos un ancho a las distintas columnas
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('A')
            ->setWidth(2);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('B')
            ->setWidth(5);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('C')
            ->setWidth(15);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('D')
            ->setWidth(12);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('E')
            ->setWidth(8);
       
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('F')
            ->setWidth(12);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('G')
            ->setWidth(25);
         $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('H')
            ->setWidth(11);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('I')
            ->setWidth(19);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('J')
            ->setWidth(11);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('K')
            ->setWidth(11);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('L')
            ->setWidth(12);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('M')
            ->setWidth(13);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('N')
            ->setWidth(28);
        
       
           $repository = $this->getDoctrine() ->getRepository('UnoMainBundle:Expediente'); 
   $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
   $us=$this->get('security.context')->getToken()->getUser()->getUsername();
   if($usuario=="ROLE_USER"){
    $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio')->setParameter('expediente', 'Tramitacion')->setParameter('municipio',$us)  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productos = $query->getResult();  
   }else
       {
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente')->setParameter('expediente', 'Tramitacion')  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productog = $query->getResult();    
   }
	 if($usuario=="ROLE_ADMIN"){
          $r=0;$productos=NULL;
          foreach ($productog as $item){
            if(Util::dias($item->getFechaInicial())>60){
         $productos[$r]=$item;
         $r++;
          
      } 
      
      
      }}
                  
          $row =7;
          $cont=1;
        foreach ($productos as $item) {
            
$dias= Util::dias($item->getFechaInicial());

if(60<$dias&&$dias<=80)
    $phpExcelObject->getActiveSheet(0)
        ->getStyle('I'.$row)
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd'); 
   if(80<$dias&&$dias<=90)
    $phpExcelObject->getActiveSheet(0)
        ->getStyle('I'.$row)
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd'); 
        if(90<$dias)
    $phpExcelObject->getActiveSheet(0)
        ->getStyle('I'.$row)
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd'); 
            $a= date_create($item->getFechaInicial());
        $b= date_create($item->getDEVTTP());
           $c= date_create($item->getPronostico()); 
          $d= date_create($item->getSIS());
                  $e= date_create($item-> getDEVFISCAL());
            $phpExcelObject->setActiveSheetIndex(0)
                 ->setCellValue('B'.$row, $cont)    
               ->setCellValue('C'.$row, $item->getNumExpediente())
                ->setCellValue('D'.$row, $item->getMunicipio())
                ->setCellValue('E'.$row, $item->getUnidad());
                    if($item->getAcusadosp() and $item->getAcusados())
                 $phpExcelObject->setActiveSheetIndex(0)->setCellValue('F'.$row,'PP-'.$item->getAcusados().'/ A-'.$item->getAcusadosp());
                            elseif($item->getAcusados())
                 $phpExcelObject->setActiveSheetIndex(0) ->setCellValue('F'.$row,'PP-'.$item->getAcusados());  
                             elseif($item->getAcusadosp())
                     $phpExcelObject->setActiveSheetIndex(0) ->setCellValue('F'.$row,'A-'.$item->getAcusadosp());              
        $phpExcelObject->setActiveSheetIndex(0) ->setCellValue('G'.$row, $item->getDelito())
                    
                             ->setCellValue('H'.$row,date_format(date_create($item->getFechaInicial()),"d-m-Y" ))
                 ->setCellValue('I'.$row,$dias) ;
                    if ($item->getDEVTTP()){
               $phpExcelObject->setActiveSheetIndex(0) ->setCellValue('J'.$row,date_format(date_create($item->getDEVTTP()),"d-m-Y" ));
                    }
                      if ($item->getSIS()){
                      $phpExcelObject->setActiveSheetIndex(0) ->setCellValue('K'.$row,date_format($d, "d-m-Y"));
                      
                      }
                      if ($item-> getDEVFISCAL()){
                      $phpExcelObject->setActiveSheetIndex(0)   ->setCellValue('L'.$row,date_format($e, "d-m-Y") )  ;}
                      
                      $phpExcelObject->setActiveSheetIndex(0) ->setCellValue('M'.$row,date_format($c, "d-m-Y"))
                ->setCellValue('N'.$row, $item->getObservaciones());
            $cont++;  
            $row++;
        }
     $row--;

        // recorremos los registros obtenidos de la consulta a base de datos escribiéndolos en las celdas correspondientes
    
    
     
           $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
     $phpExcelObject->getActiveSheet(0)->getStyle('B1')->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(18)
            
                                ->getColor()->setRGB('3F51B5');
    $phpExcelObject->getActiveSheet(0)->getStyle('G3')->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(12)
            
                                ->getColor()->setRGB('FFFFFFFF');
     $borders = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thin',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
     
     $phpExcelObject->getActiveSheet(0)
        ->getStyle('B6:N6')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('90caf9');
     $phpExcelObject->getActiveSheet(0)
        ->getStyle('C4:D4')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
     $phpExcelObject->getActiveSheet(0)
        ->getStyle('G3')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
     
     
        $phpExcelObject->getActiveSheet(0)->getStyle('B6:N6')->applyFromArray($styleArray); 
        $phpExcelObject->getActiveSheet()->getStyle('G3')->applyFromArray($styleArray); 
        
        $phpExcelObject->getActiveSheet(0)->getStyle('C4:D4')->applyFromArray($styleArray); 
    $phpExcelObject->getActiveSheet()->getStyle('B6:N'.$row)->applyFromArray($borders);
        $phpExcelObject->getActiveSheet(0)->getStyle('B6:N'.$row)->getAlignment()->applyFromArray(
    array('horizontal' => 'center')); 
   $phpExcelObject->setActiveSheetIndex(0)->mergeCells('B1:N1');
    $phpExcelObject->setActiveSheetIndex(0)->mergeCells('G3:L3');
       
    //$phpExcelObject->getActiveSheet()->getStyle('B6:N6')->getAlignment()->applyFromArray(
   // array('horizontal' => 'center')); 
                $phpExcelObject->getActiveSheet(0)->getStyle('C4')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
                              $phpExcelObject->getActiveSheet(0)->getStyle('G3')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
        $phpExcelObject->getActiveSheet(0)->getStyle('B1')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')); 
        
          if($usuario=="ROLE_USER"){
          $phpExcelObject->createSheet(1);
        $phpExcelObject->setActiveSheetIndex(1);
        $phpExcelObject->getActiveSheet(1)->setTitle('Despacho');
    
        
        $phpExcelObject->setActiveSheetIndex(1)->mergeCells('A1:H1');
        $phpExcelObject->setActiveSheetIndex(1)->mergeCells('A3:H3');
        $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B7:G7');
          $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B8:F8');
            $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B8:F8');
             $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B9:F9');
              $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B10:F10');
               $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B11:F11');
                $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B12:F12');
                 $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B13:F13');
                  $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B14:F14');
                   $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B15:F15');
                    $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B16:F16');
                     $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B17:F17');
                      $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B18:F18');
                       $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B19:F19');
                        $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B21:G21');
                         $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B22:F22');
         $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B23:F23');
          $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B24:F24');
           $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B25:F25');
            $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B26:F26');
             $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B27:F27');
             
           $de=$this->get('security.context')->getToken()->getUser()->getUsername();   
    $cont60=0;$cont90=0;$cont120=0;$cont150=0;$cont180=0;$ca=0;$term10dias=0;$recitribu=0; $sis60dias=0;
      $cont60o=0;
         $em = $this->getDoctrine()->getManager();
		$trami = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('tipoExpediente'=>'Despacho','municipio'=>$de));
                
             
    foreach ($trami as $item) {
        
        if($item->getacusados())
        {
         $ca++;   
            
        }
              if (Util::dias($item->getFechaInicial())<=60) {  
               $cont60o++;
           }  
       
           if (Util::dias($item->getFechaInicial())>60 && Util::dias($item->getFechaInicial())<= 90 ) {  
               $cont60++;
           }
           if (Util::dias($item->getFechaInicial())>90 && Util::dias($item->getFechaInicial())<= 120 ) {  
               $cont90++;
            }
            if (Util::dias($item->getFechaInicial())>120 && Util::dias($item->getFechaInicial())<= 150 ) {  
               $cont120++;
            }
             if (Util::dias($item->getFechaInicial())>150 && Util::dias($item->getFechaInicial())<= 180 ) {  
               $cont150++;
            }
          if (Util::dias($item->getFechaInicial())>180  ) {  
               $cont180++;
            }
  
     }
                             
               $ro=0;
       
    $tramin = $em->getRepository('UnoMainBundle:Despacho')->findByMunicipio($de);
    $totalpendientedespacho=0;$prision=0;$despachadosdia=0; $concluciones=0;$sobreseimientos=0;$inhibitorias=0;$ocho=0; $otros=0;  $remi=0;
   foreach ($tramin as $itemf) {
   
    $dia=$itemf->getdespachadosDia();
     $despachadosdia+=$itemf->getdespachadosDia();
   $ro=$itemf->getTermmas10dias();
    $recitribu+=$itemf-> getdevtrirecibidas60dias();
      
    $concluciones+=$itemf-> getconcluciones();
    $sobreseimientos+=$itemf-> getsobreseimientos();
         $inhibitorias+= $itemf-> getinhibitorias();
            $ocho+=$itemf->getochotres();
         $otros+=   $itemf->getotros();
           $remi+= $itemf->getremitidosFactura();
$sis60dias=$itemf->getTotalSIS60dias();
   }
   
   $phpExcelObject->setActiveSheetIndex(1)
                 ->setCellValue('G9',$ca)   ;
   $phpExcelObject->setActiveSheetIndex(1)
                 ->setCellValue('G10',$cont60o)   ;
   
   $totalpendientedespacho+=$cont60o+$cont60+$cont90+$cont120+$cont150+$cont180;
     $phpExcelObject->setActiveSheetIndex(1)
                 ->setCellValue('G16',$despachadosdia)   
       ->setCellValue('G8',$totalpendientedespacho)  
             
                 ->setCellValue('G17',$ro)   
      ->setCellValue('G18',$recitribu)   
      ->setCellValue('G19',$sis60dias)   
      ->setCellValue('G22',$concluciones)   
     ->setCellValue('G23', $sobreseimientos)   
      ->setCellValue('G24',$inhibitorias)   
      ->setCellValue('G25',$ocho)   
      ->setCellValue('G26',$otros)   
      ->setCellValue('G27',$remi) ;  
     
    
    
    
    
    $phpExcelObject->setActiveSheetIndex(1)
                 ->setCellValue('G11',$cont60)   
    
                 ->setCellValue('G12',$cont90)   
      ->setCellValue('G13',$cont120)   
      ->setCellValue('G14',$cont150)   
      ->setCellValue('G15',$cont180)   ;
    
       
             
             
             
             
        
        $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('A1', 'Fiscalia Municipal '.$fiscalia)
                ->setCellValue('F5', 'FECHA')
                ->setCellValue('G5',date('d-m-Y'))
                ->setCellValue('A3', 'PARTE DIARIO DE LA RESOLUCION 1/15 DEL FISCAL GENERAL')
                ->setCellValue('B7', 'DESPACHO')
                 ->setCellValue('B8', 'Total de EFP Pendiente de Despacho ')
                ->setCellValue('B9', 'Con acusados en Prisión Provisional ')
                   ->setCellValue('B10', 'Hasta 60 días ')
                   ->setCellValue('B11', 'De 61 a 90 días ')
                 ->setCellValue('B12', 'De 91 a 120 días ')
                 ->setCellValue('B13', 'De 121 a 150 días ')
                  ->setCellValue('B14', 'De 151 a 180 días ')
                ->setCellValue('B15', 'Mas de 180 dias ')
                ->setCellValue('B16', 'Total de despachados en el día ')
                    ->setCellValue('B16', 'Total de despachados en el día ')
                    ->setCellValue('B16', 'Total de despachados en el día ')
                    ->setCellValue('B17', 'En el término superior a 10 días')
                ->setCellValue('B18', 'Devoluciones del Tribunal Recibidas')
                ->setCellValue('B19', ' Total de SIS recibidas')
                   ->setCellValue('B21', ' TIPOS DE DESPACHO')
                   ->setCellValue('B22', ' Conclusiones')
                   ->setCellValue('B23', ' Sobreseimientos')
                   ->setCellValue('B24', ' Inhibitorias')
                 ->setCellValue('B25', ' 8.3')
                   ->setCellValue('B26', ' Otros')
                   ->setCellValue('B27', ' Remitidos al Tribunal según factura');
     
                $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('G')
            ->setWidth(10);
        

                
                
         $borders = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thin',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
           $bordeizquierdo = array(
      'borders' => array(
        'left' => array(
          'style' => 'thick',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
        
     $bordederecho = array(
      'borders' => array(
        'right' => array(
          'style' => 'thick',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
      $bordeup = array(
      'borders' => array(
        'top' => array(
          'style' => 'thick',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
    $down = array(
      'borders' => array(
        'bottom' => array(
          'style' => 'thick',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
        $gordo = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thick',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
        $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
        
        $phpExcelObject->getActiveSheet(1)->getStyle('A1')->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(18);
        $phpExcelObject->getActiveSheet(1)->getStyle('A3')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
         $phpExcelObject->getActiveSheet(1)->getStyle('F5')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
         $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
         $phpExcelObject->getActiveSheet(1)->getStyle('A3')->applyFromArray($styleArray); 
              $phpExcelObject->getActiveSheet(1)->getStyle('F5:G5')->applyFromArray($styleArray); 
            $phpExcelObject->getActiveSheet(1)->getStyle('A1')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')); 
    $phpExcelObject->getActiveSheet(1)->getStyle('B7')->applyFromArray($styleArray); 
        $phpExcelObject->getActiveSheet(1)
        ->getStyle('B7')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('90caf9');
         $phpExcelObject->getActiveSheet(1)->getStyle('B21')->applyFromArray($styleArray); 
          $phpExcelObject->getActiveSheet(1)->getStyle('B8:B19')->applyFromArray($styleArray); 
           $phpExcelObject->getActiveSheet(1)->getStyle('B22:B27')->applyFromArray($styleArray); 
        $phpExcelObject->getActiveSheet(1)
        ->getStyle('B21')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('90caf9');
        $phpExcelObject->getActiveSheet(1)
        ->getStyle('F5:G5')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
          $phpExcelObject->getActiveSheet(1)->getStyle('B8:G19')->applyFromArray($borders);
            $phpExcelObject->getActiveSheet(1)->getStyle('B22:G27')->applyFromArray($borders);
              $phpExcelObject->getActiveSheet(1)->getStyle('B8:B19')->applyFromArray($bordeizquierdo);
               $phpExcelObject->getActiveSheet(1)->getStyle('B22:B27')->applyFromArray($bordeizquierdo);
               $phpExcelObject->getActiveSheet(1)->getStyle('G8:G19')->applyFromArray($bordederecho);
                $phpExcelObject->getActiveSheet(1)->getStyle('G22:G27')->applyFromArray($bordederecho);
                     $phpExcelObject->getActiveSheet(1)->getStyle('B7:G7')->applyFromArray($gordo);
                       $phpExcelObject->getActiveSheet(1)->getStyle('B21:G21')->applyFromArray($gordo);
                         $phpExcelObject->getActiveSheet(1)->getStyle('B19:G19')->applyFromArray($down);
                             $phpExcelObject->getActiveSheet(1)->getStyle('B27:G27')->applyFromArray($down);
        
                
 
          }
 else {
     $phpExcelObject->createSheet(1);
        $phpExcelObject->setActiveSheetIndex(1);
        $phpExcelObject->getActiveSheet(1)->setTitle('Despacho');
        
         $phpExcelObject->setActiveSheetIndex(1)->mergeCells('D4:D8');
           $phpExcelObject->setActiveSheetIndex(1)->mergeCells('E4:E8');
             $phpExcelObject->setActiveSheetIndex(1)->mergeCells('F4:F8');
               $phpExcelObject->setActiveSheetIndex(1)->mergeCells('G4:G8');
               $phpExcelObject->setActiveSheetIndex(1)->mergeCells('H4:H8');
               $phpExcelObject->setActiveSheetIndex(1)->mergeCells('I4:I8');
               $phpExcelObject->setActiveSheetIndex(1)->mergeCells('J4:J8');
               $phpExcelObject->setActiveSheetIndex(1)->mergeCells('K4:K8');
               $phpExcelObject->setActiveSheetIndex(1)->mergeCells('L4:L8');
                $phpExcelObject->setActiveSheetIndex(1)->mergeCells('M4:M8');
               $phpExcelObject->setActiveSheetIndex(1)->mergeCells('N4:N8');
               $phpExcelObject->setActiveSheetIndex(1)->mergeCells('O4:O8');
               $phpExcelObject->setActiveSheetIndex(1)->mergeCells('P4:P8');
                $phpExcelObject->setActiveSheetIndex(1)->mergeCells('Q4:Q8');
               $phpExcelObject->setActiveSheetIndex(1)->mergeCells('R4:R8');
               $phpExcelObject->setActiveSheetIndex(1)->mergeCells('S4:S8');
                $phpExcelObject->setActiveSheetIndex(1)->mergeCells('T4:T8');
                $phpExcelObject->setActiveSheetIndex(1)->mergeCells('U4:U8');
               $phpExcelObject->setActiveSheetIndex(1)->mergeCells('C4:C8');
             $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B4:B8');
             $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B1:U1');
              $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B25:C25'); 
               
         $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('B4', 'No')
                   ->setCellValue('B1', 'DESPACHO FISCALIA PROVINCIAL')
                ->setCellValue('B9', '1')
                ->setCellValue('B10', '2')
                ->setCellValue('B11', '3')
                 ->setCellValue('B12', '4')
                 ->setCellValue('B13', '5')
            ->setCellValue('B14', '6')
            ->setCellValue('B15', '7')
              
            ->setCellValue('B16', '8')
                
            ->setCellValue('B17', '9')
            
            ->setCellValue('B18', '10')
                 ->setCellValue('B19', '11')
            
            ->setCellValue('B20', '12')
                
          ->setCellValue('B21', '13')
            ->setCellValue('B22', '14')
         ->setCellValue('B23', '15')
            
          ->setCellValue('B24', '16')
         ->setCellValue('C4', 'Municipio')
        ->setCellValue('C9', 'H/Vieja')
            
          ->setCellValue('C10', 'C.Hab')
         ->setCellValue('C11', 'Plaza')
    ->setCellValue('C12', 'Cerro')
                ->setCellValue('C13', 'Playa')
                ->setCellValue('C14','Marianao' )
                 ->setCellValue('C15', 'La Lisa')
                 ->setCellValue('C16', 'Boyeros')
            ->setCellValue('C17', 'Arroyo N')
            ->setCellValue('C18', '10-Oct')
              
            ->setCellValue('C19', 'San MP')
                
            ->setCellValue('C20', 'Cotorro')
            
            ->setCellValue('C21', 'Regla')
                 ->setCellValue('C22', 'Guanabacoa')
            
            ->setCellValue('C23', 'H.Este')
                 ->setCellValue('B25', 'Total')
                
          ->setCellValue('C24', 'Divico')
     ->setCellValue('D4', 'Total Pendientes de Despacho ')
          ->setCellValue('E4', 'Con acusados Prisión Provisional ')        
  ->setCellValue('F4', 'Hasta 60 días  ')
         ->setCellValue('G4', 'De 61 a 90 días  ')         
         ->setCellValue('H4', 'De 91 a 120 días  ')
          ->setCellValue('I4', 'De 121 a 150 días  ')        
  ->setCellValue('J4', 'De 151 a 180 días   ')
         ->setCellValue('K4', 'Mas de 180 dias   ')          
          ->setCellValue('L4', 'Despachados en el día   ')        
  ->setCellValue('M4', 'Término superior a 10 días  ')
         ->setCellValue('N4', 'Devoluciones del Tribunal Recibidas  ')             
            ->setCellValue('O4', ' Total de SIS recibidas  ')        
  ->setCellValue('P4', ' Conclusiones  ')
         ->setCellValue('Q4', ' Sobreseimientos ')        
                    ->setCellValue('R4', '  Inhibitorias ')        
  ->setCellValue('S4', '  8.3 ')
         ->setCellValue('T4', ' Otros')        
          ->setCellValue('U4', '  Remitidos al Tribunal según factura') 
            ->setCellValue('Q2', 'FECHA')   
               ->setCellValue('R2',date('d-m-Y')) ;  
                $phpExcelObject->getActiveSheet(0)->getStyle('B1')->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(15) ->getColor()->setRGB('3F51B5');
              $phpExcelObject->getActiveSheet(1)->getStyle('B1')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')); 
        $phpExcelObject->getActiveSheet(1)
        ->getStyle('Q2:R2')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
        
        
         $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
        
        $phpExcelObject->getActiveSheet(1)->getStyle('Q2:R2')->applyFromArray($styleArray); 
              $phpExcelObject->getActiveSheet(1)
        ->getStyle('B4:U8')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('4db6ac');   
                 $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
                 $borders = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thin',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
               $phpExcelObject->getActiveSheet(1)->getStyle('B4:U8')->applyFromArray($styleArray); 
                $phpExcelObject->getActiveSheet(1)->getStyle('B25:U25')->applyFromArray($styleArray);  
               $phpExcelObject->getActiveSheet(1)->getStyle('B9:C24')->applyFromArray($styleArray);  
             $phpExcelObject->getActiveSheet(1)->getStyle('B4:U25')->applyFromArray($borders);     
                 
               $phpExcelObject->getActiveSheet()->getStyle('B4:U8')->getAlignment()->applyFromArray(
    array('horizontal' => 'distributed')  );  
                $phpExcelObject->getActiveSheet()->getStyle('B9:U25')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')  );  
                 
                 
                 
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('B')
            ->setWidth(4);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('C')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('D')
            ->setWidth(11);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('E')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('F')
            ->setWidth(4,50);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('G')
            ->setWidth(4,50);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('H')
            ->setWidth(4,50);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('I')
            ->setWidth(5);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('J')
            ->setWidth(5);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('K')
            ->setWidth(5);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('L')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('M')
            ->setWidth(8,50);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('N')
            ->setWidth(14);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('O')
            ->setWidth(9);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('P')
            ->setWidth(9);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('Q')
            ->setWidth(10);
             $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('R')
            ->setWidth(11);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('S')
            ->setWidth(5);
               $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('T')
            ->setWidth(6);   
              $phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('U')
            ->setWidth(11);         
                 
                 $totf18=0; $totf1=0;$totf2=0;$totf3=0;$totf4=0;$totf5=0;$totf6=0;$totf7=0;$totf8=0;$totf9=0;$totf10=0;$totf11=0;$totf12=0;
                 $totf13=0;$totf14=0;$totf15=0;$totf16=0;$totf17=0;$cont60o=0;$e=0;
               $municipios=array('hvieja','centrohabana','plaza','cerro','playa','marianao','lisa','boyeros','arroyo','10Octubre','sanmiguel','cotorro','regla','guanabacoa','h.este','divico') ;       
 $totalexpe=0; $sis=''; $dev='';$termino=''; $cont60='';$cont90='';$cont120='';$cont150='';$cont180='';$ca=0;$totalexpe=0;$j=0;$acusados=0;
       for($i=9;$i<=24;$i++){
         $em = $this->getDoctrine()->getManager();
		$trami = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$j],"tipoExpediente"=>"Despacho"));
                
             
    foreach ($trami as $item) {
        
        $totalexpe++;
    
        
        if($item->getacusados())
        {
         $acusados++;   
            
        }
            
               
          if (Util::dias($item->getFechaInicial())<=60  ) {  
               $cont60o++;
           }
           if (Util::dias($item->getFechaInicial())>60 && Util::dias($item->getFechaInicial())<= 90 ) {  
               $cont60++;
           }
           if (Util::dias($item->getFechaInicial())>90 && Util::dias($item->getFechaInicial())<= 120 ) {  
               $cont90++;
            }
            if (Util::dias($item->getFechaInicial())>120 && Util::dias($item->getFechaInicial())<= 150 ) {  
               $cont120++;
            }
             if (Util::dias($item->getFechaInicial())>150 && Util::dias($item->getFechaInicial())<= 180 ) {  
               $cont150++;
            }
          if (Util::dias($item->getFechaInicial())>180  ) {  
               $cont180++;
            }
   
          
            
    }
    
    if($cont60o>0){
       $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('F'.$i, $cont60o);
    $totf18+=$cont60o;
    $cont60o=0;
    }
    $em = $this->getDoctrine()->getManager();
		 $busqueda= $em->getRepository('UnoMainBundle:Despacho')->findOneBymunicipio($municipios[$j]);
    if($busqueda){
        $dev+=$busqueda->getDevtrirecibidas60dias();
        
    
       
    
      
      
      
       $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('L'.$i, $busqueda->getDespachadosDia()); 
       $totf8+=$busqueda->getDespachadosDia();
       $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('P'.$i, $busqueda->getConcluciones()); 
             $totf12+=$busqueda->getConcluciones();
        $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('Q'.$i, $busqueda->getSobreseimientos()); 
         $totf13+=$busqueda->getSobreseimientos();
        $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('R'.$i, $busqueda->getInhibitorias()); 
        
        $totf14+=$busqueda->getInhibitorias();
       $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('S'.$i, $busqueda->getochotres()); 
          $totf15+=$busqueda->getochotres();
       
        $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('T'.$i, $busqueda->getOtros());
             $totf16+=$busqueda->getOtros();
         $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('U'.$i, $busqueda->getRemitidosFactura());
            $totf17+=$busqueda->getRemitidosFactura();
           $e= $busqueda->getTermmas10dias();
    }
    
     $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('G'.$i,$cont60); 
      $totf3+=$cont60;
      $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('H'.$i,$cont90); 
      $totf4+=$cont90;
       $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('I'.$i,$cont120); 
       $totf5+=$cont120;
        $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('J'.$i,$cont150); 
        $totf6+=$cont150;
         $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('K'.$i,$cont180); 
       $totf7+=$cont180;  
    
     $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('D'.$i, $totalexpe); 
     $totf1+=$totalexpe;
      $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('E'.$i, $acusados); 
      $totf2+=$acusados;
      if($e>0){
        $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('M'.$i,$e); 
      $totf9+=$e;$e=0;}
       $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('N'.$i,$dev); 
       $totf10+=$dev;
       $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('O'.$i,$sis); 
      $totf11+=$sis;
      
    
    $j++;
    $acusados='';
    $totalexpe='';$cont60='';$cont90='';$cont120='';$cont150='';$termino='';$dev='';$sis='';$cont180='';
            }
                             
        $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('D25',$totf1); 
       $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('E25',$totf2); 
      $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('G25',$totf3); 
        $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('H25',$totf4); 
          $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('I25',$totf5); 
            $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('J25',$totf6); 
              $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('K25',$totf7); 
                $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('L25',$totf8); 
              $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('M25',$totf9); 
              $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('N25',$totf10); 
                $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('O25',$totf11);    
                 $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('P25',$totf12); 
              $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('Q25',$totf13); 
                $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('R25',$totf14);       
                $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('S25',$totf15); 
              $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('T25',$totf16); 
                $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('U25',$totf17);       
                   $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('F25',$totf18);       
                      
                 
                 
                  $phpExcelObject->getActiveSheet(2)
        ->getStyle('B25:U25')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('b2dfdb');
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                 
                  }
        
          $phpExcelObject->createSheet(2);

 $phpExcelObject->setActiveSheetIndex(2);
        $phpExcelObject->getActiveSheet()->setTitle('Despacho mas de 60 dias');
     $horaactual=strval(date('d-m-Y'));
       
        // escribimos en distintas celdas del documento el título de los campos que vamos a exportar
   
     
      if( $usuario=="ROLE_ADMIN")
              $phpExcelObject->setActiveSheetIndex(2)    ->setCellValue('B1', 'FISCALIA PROVINCIAL DE LA HABANA');
                        else
                $phpExcelObject->setActiveSheetIndex(2)     ->setCellValue('B1', 'Fiscalia Municipal '.$fiscalia) ; 
     $phpExcelObject->setActiveSheetIndex(2)
              
                ->setCellValue('C4', 'FECHA:')
                ->setCellValue('D4', $horaactual)
                 ->setCellValue('G3', 'EXPEDIENTES PENDIENTES A DESPACHO')
                 ->setCellValue('B6', 'No.')
            ->setCellValue('C6', 'No-EFP')
            ->setCellValue('D6', 'MUNICIPIO')
              
            ->setCellValue('E6', 'ACUSADOS')
                
            ->setCellValue('G6', 'FECHA/I')
              ->setCellValue('F6', 'DELITO')
            ->setCellValue('H6', 'TERM-TRAMI')
                 ->setCellValue('I6', 'FECHA/E')
            
            ->setCellValue('J6', 'TERM-DESPA')
                
          ->setCellValue('K6', 'DEV-TTP')
            ->setCellValue('L6', 'SIS')
         ->setCellValue('M6', 'DEV-FISCAL')
            
          ->setCellValue('N6', 'PRONOSTICO')
         ->setCellValue('O6', 'OBSERVACIONES');
   

        // fijamos un ancho a las distintas columnas
      $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('A')
            ->setWidth(1);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('B')
            ->setWidth(5);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('C')
            ->setWidth(15);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('D')
            ->setWidth(12);
       
          
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('E')
            ->setWidth(12);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('F')
            ->setWidth(27);
         $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('G')
            ->setWidth(11);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('H')
            ->setWidth(15);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('I')
            ->setWidth(11);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('J')
            ->setWidth(13);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('K')
            ->setWidth(11);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('L')
            ->setWidth(11);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('M')
            ->setWidth(11);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('N')
            ->setWidth(13);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('O')
            ->setWidth(20);
        
 $TipoExpedient="despacho";

//$repository = $this->getDoctrine()->getRepository('UnoMainBundle:Expediente');
		//$producto = $repository->findByNombre($nombre);
	//	$producto = $repository->findByTipoExpediente($TipoExpediente);
		//$producto = $repository->findBy(array("nombre"=>$nombre), 20, 0);
	
	
         $repository = $this->getDoctrine() ->getRepository('UnoMainBundle:Expediente'); 
   $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
   $us=$this->get('security.context')->getToken()->getUser()->getUsername();
   
   $productos=NULL; $productosg=NULL;
   if($usuario=="ROLE_USER"){
    $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio')->setParameter('expediente', 'Despacho')->setParameter('municipio',$us)  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productos = $query->getResult();  
   }else
       {
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente')->setParameter('expediente', 'Despacho')  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productos = $query->getResult();  
     
          
      } 
      
      
      
        
         

  
	
          $row =7;
          $cont=1;
        foreach ($productos as $item) {
            if (  Util::dias($item->getFechaInicial())>60){
            
$dias= Util::dias($item->getFechaInicial());
$despacho=Util::dias($item->getFechaEntrega());

if(60<$dias&&$dias<=80)
    $phpExcelObject->getActiveSheet(2)
        ->getStyle('H'.$row)
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd'); 
   if(80<$dias&&$dias<=90)
    $phpExcelObject->getActiveSheet(2)
        ->getStyle('H'.$row)
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd'); 
        if(90<$dias)
    $phpExcelObject->getActiveSheet(2)
        ->getStyle('H'.$row)
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd'); 
                   
            
        $a1= date_create($item->getFechaInicial());
        $b1= date_create($item->getDEVTTP());
           $c1= date_create($item->getPronostico()); 
          $d1= date_create($item->getSIS());
                  $e1= date_create($item-> getDEVFISCAL());
    
            $f1=date_create($item->getFechaEntrega());

             
            $phpExcelObject->setActiveSheetIndex(2)
                 ->setCellValue('B'.$row, $cont)    
               ->setCellValue('C'.$row, $item->getNumExpediente())
                ->setCellValue('D'.$row, $item->getMunicipio());
              
                if($item->getAcusadosp() and $item->getAcusados())
                 $phpExcelObject->setActiveSheetIndex(2)->setCellValue('E'.$row,'PP-'.$item->getAcusados().'/ A-'.$item->getAcusadosp());
                            elseif($item->getAcusados())
                 $phpExcelObject->setActiveSheetIndex(2) ->setCellValue('E'.$row,'PP-'.$item->getAcusados());  
                             elseif($item->getAcusadosp())
                     $phpExcelObject->setActiveSheetIndex(2) ->setCellValue('E'.$row,'A-'.$item->getAcusadosp());  
                $phpExcelObject->setActiveSheetIndex(2) ->setCellValue('G'.$row,date_format($a1, "d-m-Y"))
                        
                 ->setCellValue('F'.$row,$item->getDelito())     
                 ->setCellValue('H'.$row,$dias)
                     ->setCellValue('N'.$row, date_format($c1, "d-m-Y"))
                ->setCellValue('O'.$row, $item->getObservaciones())
        ->setCellValue('J'.$row,$despacho) 
                        ->setCellValue('I'.$row, date_format($f1, "d-m-Y")); 

 
                if ($item->getDEVTTP()){
              $phpExcelObject->setActiveSheetIndex(2)  ->setCellValue('K'.$row,date_format($b1, "d-m-Y"));}
if($item->getSIS()){
               $phpExcelObject->setActiveSheetIndex(2)->setCellValue('L'.$row,date_format($d1, "d-m-Y"));}
if($item-> getDEVFISCAL()){
               $phpExcelObject->setActiveSheetIndex(2) ->setCellValue('M'.$row,date_format($e1, "d-m-Y"));  }

               
            $cont++;  
            $row++;

        }else{
           
            
          $dias= Util::dias($item->getFechaInicial());
$despacho=Util::dias($item->getFechaEntrega());

if(60<$dias&&$dias<=80)
    $phpExcelObject->getActiveSheet(2)
        ->getStyle('H'.$row)
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd'); 
   if(80<$dias&&$dias<=90)
    $phpExcelObject->getActiveSheet(2)
        ->getStyle('H'.$row)
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd'); 
        if(90<$dias)
    $phpExcelObject->getActiveSheet(2)
        ->getStyle('H'.$row)
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd'); 
                   
            
        $a1= date_create($item->getFechaInicial());
        $b1= date_create($item->getDEVTTP());
           $c1= date_create($item->getPronostico()); 
          $d1= date_create($item->getSIS());
                  $e1= date_create($item-> getDEVFISCAL());
    
            $f1=date_create($item->getFechaEntrega());

             
            $phpExcelObject->setActiveSheetIndex(2)
                 ->setCellValue('B'.$row, $cont)    
               ->setCellValue('C'.$row, $item->getNumExpediente())
                ->setCellValue('D'.$row, $item->getMunicipio());
              
                if($item->getAcusadosp() and $item->getAcusados())
                 $phpExcelObject->setActiveSheetIndex(2)->setCellValue('E'.$row,'PP-'.$item->getAcusados().'/ A-'.$item->getAcusadosp());
                            elseif($item->getAcusados())
                 $phpExcelObject->setActiveSheetIndex(2) ->setCellValue('E'.$row,'PP-'.$item->getAcusados());  
                             elseif($item->getAcusadosp())
                     $phpExcelObject->setActiveSheetIndex(2) ->setCellValue('E'.$row,'A-'.$item->getAcusadosp());  
                $phpExcelObject->setActiveSheetIndex(2) ->setCellValue('G'.$row,date_format($a1, "d-m-Y"))
                        
                 ->setCellValue('F'.$row,$item->getDelito())     
                 ->setCellValue('H'.$row,$dias)
                     ->setCellValue('N'.$row, date_format($c1, "d-m-Y"))
                ->setCellValue('O'.$row, $item->getObservaciones())
        ->setCellValue('J'.$row,$despacho) 
                        ->setCellValue('I'.$row, date_format($f1, "d-m-Y")); 

 
                if ($item->getDEVTTP()){
              $phpExcelObject->setActiveSheetIndex(2)  ->setCellValue('K'.$row,date_format($b1, "d-m-Y"));}
if($item->getSIS()){
               $phpExcelObject->setActiveSheetIndex(2)->setCellValue('L'.$row,date_format($d1, "d-m-Y"));}
if($item-> getDEVFISCAL()){
               $phpExcelObject->setActiveSheetIndex(2) ->setCellValue('M'.$row,date_format($e1, "d-m-Y"));  }

               
            $cont++;  
            $row++;
  
            
            
            
            
            
            
            
            
          }  
            
}
     $row--;

        // recorremos los registros obtenidos de la consulta a base de datos escribiéndolos en las celdas correspondientes
     
           $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
     $phpExcelObject->getActiveSheet(2)->getStyle('B1')->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(18)
            
                                ->getColor()->setRGB('3F51B5');
    $phpExcelObject->getActiveSheet(2)->getStyle('G3')->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(12)
            
                                ->getColor()->setRGB('FFFFFFFF');
     $borders = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thin',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
     
     $phpExcelObject->getActiveSheet(2)
        ->getStyle('B6:O6')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('4db6ac');
     $phpExcelObject->getActiveSheet(2)
        ->getStyle('C4:D4')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('b2dfdb');
     $phpExcelObject->getActiveSheet(2)
        ->getStyle('G3')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('b2dfdb');
     
     
        $phpExcelObject->getActiveSheet(2)->getStyle('B6:O6')->applyFromArray($styleArray); 
        $phpExcelObject->getActiveSheet(2)->getStyle('G3')->applyFromArray($styleArray); 
        
        $phpExcelObject->getActiveSheet(2)->getStyle('C4:D4')->applyFromArray($styleArray); 
    $phpExcelObject->getActiveSheet(2)->getStyle('B6:O'.$row)->applyFromArray($borders);
   $phpExcelObject->setActiveSheetIndex(2)->mergeCells('B1:O1');
    $phpExcelObject->setActiveSheetIndex(2)->mergeCells('G3:L3');
        $phpExcelObject->getActiveSheet(2)->getStyle('B6:O'.$row)->getAlignment()->applyFromArray(
    array('horizontal' => 'center')); 
                $phpExcelObject->getActiveSheet(2)->getStyle('C4')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
                              $phpExcelObject->getActiveSheet(2)->getStyle('G3')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
        $phpExcelObject->getActiveSheet(2)->getStyle('B1')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')); 
        
        
        if($usuario=="ROLE_ADMIN") {
     
      $phpExcelObject->createSheet(3);
        $phpExcelObject->setActiveSheetIndex(3);
        $phpExcelObject->getActiveSheet(3)->setTitle('Fuerza');
        
         $phpExcelObject->setActiveSheetIndex(3)->mergeCells('D4:D8');
           $phpExcelObject->setActiveSheetIndex(3)->mergeCells('E4:E8');
             $phpExcelObject->setActiveSheetIndex(3)->mergeCells('F4:F8');
               $phpExcelObject->setActiveSheetIndex(3)->mergeCells('G4:G8');
               $phpExcelObject->setActiveSheetIndex(3)->mergeCells('H4:H8');
               $phpExcelObject->setActiveSheetIndex(3)->mergeCells('I4:I8');
               $phpExcelObject->setActiveSheetIndex(3)->mergeCells('J4:J8');
        $phpExcelObject->setActiveSheetIndex(3)->mergeCells('K4:K8');
               $phpExcelObject->setActiveSheetIndex(3)->mergeCells('L4:L8');
                $phpExcelObject->setActiveSheetIndex(3)->mergeCells('M4:M8');
               $phpExcelObject->setActiveSheetIndex(3)->mergeCells('N4:N8');
               
               $phpExcelObject->setActiveSheetIndex(3)->mergeCells('C4:C8');
             $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B4:B8');
             $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B1:N1');
              $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B25:C25');
              
               
         $phpExcelObject->setActiveSheetIndex(3)
                ->setCellValue('B4', 'No')
                   ->setCellValue('B1', 'FUERZA FISCALIA PROVINCIAL')
                ->setCellValue('B9', '1')
                ->setCellValue('B10', '2')
                ->setCellValue('B11', '3')
                 ->setCellValue('B12', '4')
                 ->setCellValue('B13', '5')
            ->setCellValue('B14', '6')
            ->setCellValue('B15', '7')
              
            ->setCellValue('B16', '8')
                
            ->setCellValue('B17', '9')
            
            ->setCellValue('B18', '10')
                 ->setCellValue('B19', '11')
            
            ->setCellValue('B20', '12')
                
          ->setCellValue('B21', '13')
            ->setCellValue('B22', '14')
         ->setCellValue('B23', '15')
            
          ->setCellValue('B24', '16')
         ->setCellValue('C4', 'Municipio')
        ->setCellValue('C9', 'H/Vieja')
            
          ->setCellValue('C10', 'C.Hab')
         ->setCellValue('C11', 'Plaza')
    ->setCellValue('C12', 'Cerro')
                ->setCellValue('C13', 'Playa')
                ->setCellValue('C14','Marianao' )
                 ->setCellValue('C15', 'La Lisa')
                 ->setCellValue('C16', 'Boyeros')
            ->setCellValue('C17', 'Arroyo N')
            ->setCellValue('C18', '10-Oct')
              
            ->setCellValue('C19', 'San MP')
                
            ->setCellValue('C20', 'Cotorro')
            
            ->setCellValue('C21', 'Regla')
                 ->setCellValue('C22', 'Guanabacoa')
            
            ->setCellValue('C23', 'H.Este')
              
            ->setCellValue('B25', 'TOTAL')   
          ->setCellValue('C24', 'Divico')
     ->setCellValue('D4', 'Total de fiscales de la especialidad ')
          ->setCellValue('E4', 'Dedicados al control')        
  ->setCellValue('F4', 'Dedicados al despacho ')
         ->setCellValue('G4', 'Dedicados a juicios  ')         
         ->setCellValue('H4', 'Otras actividades ')
          ->setCellValue('I4', 'Total de juicios en los que se participó  ')        
  ->setCellValue('J4', 'Sumario  ')
         ->setCellValue('K4', 'Ordinario TMP  ')          
          ->setCellValue('L4', 'Tribunal Provincial ordinario  ')        
  ->setCellValue('M4', 'Tribunal Provincial apelaciones ')
         ->setCellValue('N4', 'Total de Incidencias en Juicio Oral Reportadas ')             
        ->setCellValue('M2',"FECHA:") 
               ->setCellValue('N2',date('d-m-Y')) ;  
                $phpExcelObject->getActiveSheet(0)->getStyle('B1')->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(15) ->getColor()->setRGB('3F51B5');
              $phpExcelObject->getActiveSheet(3)->getStyle('B1')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')); 
        $phpExcelObject->getActiveSheet(3)
        ->getStyle('M2:N2')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
        
        
         $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
        
        $phpExcelObject->getActiveSheet(3)->getStyle('M2:N2')->applyFromArray($styleArray); 
              $phpExcelObject->getActiveSheet(3)
        ->getStyle('B4:N8')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('90caf9');   
                 $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
                 $borders = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thin',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
               $phpExcelObject->getActiveSheet(3)->getStyle('B4:N8')->applyFromArray($styleArray);  
               $phpExcelObject->getActiveSheet(3)->getStyle('B9:C24')->applyFromArray($styleArray);  
                $phpExcelObject->getActiveSheet(3)->getStyle('B25:N25')->applyFromArray($styleArray);  
             $phpExcelObject->getActiveSheet(3)->getStyle('B4:N25')->applyFromArray($borders);     
                 
               $phpExcelObject->getActiveSheet()->getStyle('B4:N8')->getAlignment()->applyFromArray(
    array('horizontal' => 'distributed')  );  
                $phpExcelObject->getActiveSheet()->getStyle('B9:N25')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')  );  
                 
                 
                 
               $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('B')
            ->setWidth(4);
               $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('C')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('D')
            ->setWidth(15);
               $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('E')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('F')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('G')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('H')
            ->setWidth(13);
               $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('I')
            ->setWidth(15);
               $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('J')
            ->setWidth(15);
               $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('K')
            ->setWidth(15);
               $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('L')
            ->setWidth(15);
               $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('M')
            ->setWidth(15);
               $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('N')
            ->setWidth(18);
         $municipios=array('hvieja','centrohabana','plaza','cerro','playa','marianao','lisa','boyeros','arroyo','10Octubre','sanmiguel','cotorro','regla','guanabacoa','h.este','divico') ; 
         $j=0;
         $contf1=0;$contf2=0;$contf3=0;$contf4=0;$contf5=0;$contf6=0;$contf7=0;$contf8=0;$contf9=0;$contf10=0;$contf11=0;
         for($i=9;$i<=24;$i++){
         $em = $this->getDoctrine()->getManager();
		$trami = $em->getRepository('UnoMainBundle:Fuerza')->findBy(array('municipio'=> $municipios[$j]));
                         
                foreach ($trami as $item){
                 $phpExcelObject->setActiveSheetIndex(3)
                ->setCellValue('D'.$i,$item->getTotalEspecialidad())   
                ->setCellValue('E'.$i,$item->getDedControl())   
                      ->setCellValue('F'.$i,$item->getDedDespacho())
                 ->setCellValue('G'.$i,$item->getDedJuicios()) 
                   ->setCellValue('H'.$i,$item->getOtrasActividades())
                  ->setCellValue('I'.$i,$item-> getTotaljuicios())
                 ->setCellValue('J'.$i,$item->getSumario())
                  ->setCellValue('K'.$i,$item->getOrdinario())
             ->setCellValue('L'.$i,$item->getTribProvOrd())
                  
                   ->setCellValue('M'.$i,$item->getTribProvApel())
                   ->setCellValue('N'.$i,$item->getIncidenciasReport());
                  
               $contf1+= $item->getTotalEspecialidad();  
                   $contf2+=  $item->getDedControl();
                          $contf3+=  $item->getDedDespacho();
                          $contf4+=  $item->getDedJuicios();
                          $contf5+= $item->getOtrasActividades(); 
                          $contf6+= $item-> getTotaljuicios();    $contf7+= $item->getSumario() ;   $contf8+= $item->getOrdinario()  ;
                       $contf9+= $item->getTribProvOrd();    $contf10+= $item->getTribProvApel()  ;  $contf11+=$item->getIncidenciasReport()   ;  
                       
                       
                       
                       
                       
                       
                       
                  
                  
                }
     
     $j++;
         }
          $phpExcelObject->setActiveSheetIndex(3)
                ->setCellValue('D25',$contf1)   
       ->setCellValue('E25',$contf2)   
      ->setCellValue('F25',$contf3)               
        ->setCellValue('G25',$contf4)             
        ->setCellValue('H25',$contf5)             
        ->setCellValue('I25',$contf6)             
         ->setCellValue('J25',$contf7)            
          ->setCellValue('K25',$contf8)           
          ->setCellValue('L25',$contf9)           
           ->setCellValue('M25',$contf10)          
           ->setCellValue('N25',$contf11)  ;        
             
                  
                $phpExcelObject->getActiveSheet(3)
        ->getStyle('B25:N25')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
        
                  
                  
                  
                  
                  
                  
                  
                  
                  
   }else
   {
    
        $phpExcelObject->createSheet(3);
 $phpExcelObject->setActiveSheetIndex(3);
        $phpExcelObject->getActiveSheet(3)->setTitle('Fuerza');
    
        
        $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B9:G9');
        $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B10:F10');
        $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B11:F11');
          $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B12:F12');
            $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B13:F13');
             $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B14:F14');
              $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B17:F17');
               $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B18:F18');
                $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B19:F19');
                 $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B20:F20');
                  $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B21:F21');
                   $phpExcelObject->setActiveSheetIndex(3)->mergeCells('B22:F22');
                   
                     $phpExcelObject->setActiveSheetIndex(3)
                ->setCellValue('B9', 'CONTROL DE LAS FUERZAS')
                              ->setCellValue('A1', 'Fiscalia Municipal '.$fiscalia)
                ->setCellValue('F5', 'FECHA')
                ->setCellValue('G5',date('d-m-Y'))
                ->setCellValue('A3', 'PARTE DIARIO DE LA RESOLUCION 1/15 DEL FISCAL GENERAL')
                                   ->setCellValue('B10', 'Total de fiscales de la especialidad')
                                   ->setCellValue('B11', 'Dedicados al control')
                                   ->setCellValue('B12', 'Dedicados al despacho')
                                   ->setCellValue('B13', 'Dedicados a juicios')
                                   ->setCellValue('B14', 'Otras actividades')
                                   ->setCellValue('B17', 'Total de juicios en los que se participó')
                                   ->setCellValue('B18', 'Sumario')
                                   ->setCellValue('B19', 'Ordinario TMP')
                             ->setCellValue('B20', 'Tribunal Provincial ordinario')
                                   ->setCellValue('B21', 'Tribunal Provincial apelaciones')
                               ->setCellValue('B22', 'Total de Incidencias en Juicio Oral Reportadas');
         $em = $this->getDoctrine()->getManager();                    
       $de=$this->get('security.context')->getToken()->getUser()->getUsername();            
        $trami = $em->getRepository('UnoMainBundle:Fuerza')->findByMunicipio($de);
       $totalEspecialidad=0;$dedControl=0;$dedDespacho=0;$dedJuicios=0;$otrasActividades=0;$totaljuicios=0;$sumario=0;$ordinario=0;$tribProvOrd=0;
      $tribProvApel=0;$incidenciasReport=0;
   foreach ($trami as $item) {
     $totalEspecialidad+=$item->gettotalEspecialidad();
     $dedControl+=$item->getdedControl();
     $dedDespacho+=$item->getdedDespacho();
    $dedJuicios+=$item-> getdedJuicios();
    $otrasActividades+=$item-> getotrasActividades();
     $totaljuicios+=$item-> gettotaljuicios();  
    $sumario+=$item-> getsumario();
    $ordinario+=$item-> getordinario();
         $tribProvOrd+= $item-> gettribProvOrd();
            $tribProvApel+=$item->gettribProvApel();
         $incidenciasReport+=$item->getincidenciasReport();
      
   }
 
     $phpExcelObject->setActiveSheetIndex(3)
                 ->setCellValue('G10',   $totalEspecialidad)   
       ->setCellValue('G11',$dedControl)  
                 ->setCellValue('G12',$dedDespacho)   
      ->setCellValue('G13',$dedJuicios)   
      ->setCellValue('G14',$otrasActividades)   
      ->setCellValue('G17',$totaljuicios)   
     ->setCellValue('G18',  $sumario)   
      ->setCellValue('G19',$ordinario)   
      ->setCellValue('G20',$tribProvOrd)   
      ->setCellValue('G21',$tribProvApel)
             
       ->setCellValue('G22',$incidenciasReport)   ;
    
     
    
  $phpExcelObject->getActiveSheet(3)->getStyle('A1')->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(18);
        $phpExcelObject->getActiveSheet(3)->getStyle('A3')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
         $phpExcelObject->getActiveSheet(3)->getStyle('F5')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
         $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
          $phpExcelObject->setActiveSheetIndex(3)
            ->getColumnDimension('G')
            ->setWidth(10);
         $phpExcelObject->getActiveSheet(3)->getStyle('A3')->applyFromArray($styleArray); 
              $phpExcelObject->getActiveSheet(3)->getStyle('F5:G5')->applyFromArray($styleArray); 
            $phpExcelObject->getActiveSheet(3)->getStyle('A1')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')); 
    
     $phpExcelObject->getActiveSheet(3)->getStyle('B9')->applyFromArray($styleArray); 
        $phpExcelObject->getActiveSheet(3)
        ->getStyle('B9')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('90caf9');
         $phpExcelObject->getActiveSheet(3)->getStyle('B17')->applyFromArray($styleArray); 
          $phpExcelObject->getActiveSheet(3)->getStyle('B10:B14')->applyFromArray($styleArray); 
           $phpExcelObject->getActiveSheet(3)->getStyle('B18:B22')->applyFromArray($styleArray); 
        
    $phpExcelObject->getActiveSheet(3)
        ->getStyle('F5:G5')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
       
         $borders = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thin',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
           $bordeizquierdo = array(
      'borders' => array(
        'left' => array(
          'style' => 'thick',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
        
     $bordederecho = array(
      'borders' => array(
        'right' => array(
          'style' => 'thick',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
      $bordeup = array(
      'borders' => array(
        'top' => array(
          'style' => 'thick',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
    $down = array(
      'borders' => array(
        'bottom' => array(
          'style' => 'thick',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
        $gordo = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thick',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
     $phpExcelObject->setActiveSheetIndex(3)->mergeCells('A1:H1');
     $phpExcelObject->setActiveSheetIndex(3)->mergeCells('A3:H3');
        
        
        
        
        
        
    
          $phpExcelObject->getActiveSheet(3)->getStyle('B10:G14')->applyFromArray($borders);
           $phpExcelObject->getActiveSheet(3)->getStyle('B17:G22')->applyFromArray($borders);
             $phpExcelObject->getActiveSheet(3)->getStyle('B17:B22')->applyFromArray($bordeizquierdo);
              $phpExcelObject->getActiveSheet(3)->getStyle('B10:B14')->applyFromArray($bordeizquierdo);
              $phpExcelObject->getActiveSheet(3)->getStyle('G17:G22')->applyFromArray($bordederecho);
               $phpExcelObject->getActiveSheet(3)->getStyle('G10:G14')->applyFromArray($bordederecho);
                   $phpExcelObject->getActiveSheet(3)->getStyle('B9:G9')->applyFromArray($gordo);
                     
                        $phpExcelObject->getActiveSheet(3)->getStyle('B14:G14')->applyFromArray($down);
                            $phpExcelObject->getActiveSheet(3)->getStyle('B22:G22')->applyFromArray($down);
               $phpExcelObject->getActiveSheet(3)->getStyle('B17:G17')->applyFromArray( $bordeup);
               
    }
$phpExcelObject->setActiveSheetIndex(0);
        // se crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        // se crea el response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // y por último se añaden las cabeceras
        
        
        
        
        
        
        
        
        
        
        
        
        
       
         $response->headers->set('Content-Disposition', 'attachment; filename="Hoja_Dos_'.$fiscalia.'_'.$horaactual.'.xlsx"');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
         $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        
        
        
        
        return $response;
    
        
    }
   
    
    
    
    
    
    
    
     public function hojaunoAction(Request $request)
    {
     $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       
        // ...y le asignamos una serie de propiedades
        $phpExcelObject->getProperties()
            ->setCreator("Vabadus")
            ->setLastModifiedBy("Vabadus")
            ->setTitle("Ejemplo de exportación")
            ->setSubject("Ejemplo")
            ->setDescription("Listado de ejemplo.")
            ->setKeywords("vabadus exportar excel ejemplo");
        

 $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet(0)->setTitle('Tramitacion al Cierre del dia');
      
  $phpExcelObject->setActiveSheetIndex(0)->mergeCells('B4:B8');
        $phpExcelObject->setActiveSheetIndex(0)->mergeCells('C4:C8');
        $phpExcelObject->setActiveSheetIndex(0)->mergeCells('D4:E7');
         $phpExcelObject->setActiveSheetIndex(0)->mergeCells('F4:G7');
           $phpExcelObject->setActiveSheetIndex(0)->mergeCells('H4:N4');
           $phpExcelObject->setActiveSheetIndex(0)->mergeCells('O4:U4');
              $phpExcelObject->setActiveSheetIndex(0)->mergeCells('V4:W7');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('X4:X8');
                    $phpExcelObject->setActiveSheetIndex(0)->mergeCells('Y4:Z7');
                        $phpExcelObject->setActiveSheetIndex(0)->mergeCells('AA4:AB7');
        $phpExcelObject->setActiveSheetIndex(0)->mergeCells('AC4:AD7');
         $phpExcelObject->setActiveSheetIndex(0)->mergeCells('H5:H8');
         $phpExcelObject->setActiveSheetIndex(0)->mergeCells('I5:I8');
           $phpExcelObject->setActiveSheetIndex(0)->mergeCells('J5:J8');
           $phpExcelObject->setActiveSheetIndex(0)->mergeCells('K5:K8');
           $phpExcelObject->setActiveSheetIndex(0)->mergeCells('L5:L8');
             $phpExcelObject->setActiveSheetIndex(0)->mergeCells('M5:M8');
             $phpExcelObject->setActiveSheetIndex(0)->mergeCells('N5:N8');
              $phpExcelObject->setActiveSheetIndex(0)->mergeCells('O5:O8');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('P5:P8');
                  $phpExcelObject->setActiveSheetIndex(0)->mergeCells('Q5:Q8');
                   $phpExcelObject->setActiveSheetIndex(0)->mergeCells('R5:R8');
                   $phpExcelObject->setActiveSheetIndex(0)->mergeCells('S5:S8');
                   $phpExcelObject->setActiveSheetIndex(0)->mergeCells('T5:T8');
                     $phpExcelObject->setActiveSheetIndex(0)->mergeCells('U5:U8');
                    $phpExcelObject->setActiveSheetIndex(0)->mergeCells('L25:M25');
                     $phpExcelObject->setActiveSheetIndex(0)->mergeCells('R25:T25');
                   $phpExcelObject->setActiveSheetIndex(0)->mergeCells('D2:R2');
                          $phpExcelObject->setActiveSheetIndex(0)->mergeCells('Z2:AA2');
                                 $phpExcelObject->setActiveSheetIndex(0)->mergeCells('AB2:AD2');
        $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
        $fiscalia=$this->get('security.context')->getToken()->getUser()->getNombre();
        // escribimos en distintas celdas del documento el título de los campos que vamos a exportar
        if( $usuario=="ROLE_ADMIN")
              $phpExcelObject->setActiveSheetIndex(0)    ->setCellValue('D2', 'PARTE TRAMITACION FISCALIA PROVINCIAL');
                        else
                $phpExcelObject->setActiveSheetIndex(0)     ->setCellValue('D2', 'Parte Tramitacion Fiscalia Municipal  '.$fiscalia) ; 
                   
                   
                   $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('B4', 'No')
                ->setCellValue('B9', '1')
                ->setCellValue('B10', '2')
                ->setCellValue('B11', '3')
                 ->setCellValue('B12', '4')
                 ->setCellValue('B13', '5')
            ->setCellValue('B14', '6')
            ->setCellValue('B15', '7')
              
            ->setCellValue('B16', '8')
                
            ->setCellValue('B17', '9')
            
            ->setCellValue('B18', '10')
                 ->setCellValue('B19', '11')
            
            ->setCellValue('B20', '12')
                
          ->setCellValue('B21', '13')
            ->setCellValue('B22', '14')
         ->setCellValue('B23', '15')
            
          ->setCellValue('B24', '16')
         ->setCellValue('C4', 'Municipio')
        ->setCellValue('C9', 'H/Vieja')
            
          ->setCellValue('C10', 'C.Hab')
         ->setCellValue('C11', 'Plaza')
    ->setCellValue('C12', 'Cerro')
                ->setCellValue('C13', 'Playa')
                ->setCellValue('C14','Marianao' )
                 ->setCellValue('C15', 'La Lisa')
                 ->setCellValue('C16', 'Boyeros')
            ->setCellValue('C17', 'Arroyo N')
            ->setCellValue('C18', '10-Oct')
              
            ->setCellValue('C19', 'San MP')
                
            ->setCellValue('C20', 'Cotorro')
            
            ->setCellValue('C21', 'Regla')
                 ->setCellValue('C22', 'Guanabacoa')
            
            ->setCellValue('C23', 'H.Este')
                
          ->setCellValue('C24', 'Divico')
            ->setCellValue('D4', 'Total EFP en tramitacion al inicio del dia')
         ->setCellValue('F4', 'Total EFP en tramitacion al cierre del dia')
            
          ->setCellValue('H5', 'Hast 60 dias')
         ->setCellValue('I5', '61 a 90')
        ->setCellValue('J5', '91 a 120')
            
          ->setCellValue('K5', '121 a 150')
         ->setCellValue('L5', '151 a 180')
         ->setCellValue('Z8', 'Dtico')
                ->setCellValue('AA8', 'PNR')
                ->setCellValue('AB8', 'Dtico')
                 ->setCellValue('AC8', 'PNR')
                 ->setCellValue('AD8', 'Dtic')
            ->setCellValue('L25', 'Total PNR')
            ->setCellValue('N25', 'num')
              
            ->setCellValue('R25', 'Total Dtico')
                
            ->setCellValue('U25', 'num')
            
            ->setCellValue('M5', 'Mas de 180')
                 ->setCellValue('N5', 'Tot')
            
            ->setCellValue('O5', 'Hasta 60 dias')
                
          ->setCellValue('P5', '61 a 90')
            ->setCellValue('Q5', '91 a 120')
         ->setCellValue('R5', '121 a 150')
            
          ->setCellValue('S5', '151 a 180')
         ->setCellValue('T5', 'Mas de 180')
        ->setCellValue('U5', 'Tot')
               
          ->setCellValue('V4', 'EFP con acusados PP')
            ->setCellValue('X4', 'Total EFP cont en el dia')
         ->setCellValue('Y4', 'Total de EFP Iniciados')
            
          ->setCellValue('AA4', 'Total recibidos concluid')
         ->setCellValue('AC4', 'Total Devuelt a la Inst')
            
                 ->setCellValue('D8', 'PNR')  
                 ->setCellValue('E8', 'DTICO')  
                 ->setCellValue('F8', 'PNR')  
                 ->setCellValue('G8', 'Dtico')  
                           ->setCellValue('H4', 'PNR') 
                             ->setCellValue('O4', 'DTICO')  
                 ->setCellValue('V8', 'PNR')  
                 ->setCellValue('W8', 'Dtic')  
                                ->setCellValue('Z2', 'FECHA :') 
                                ->setCellValue('AB2', date('d-m-Y')) 
                 ->setCellValue('Y8', 'PNR') ; 
       $municipios=array('hvieja','centrohabana','plaza','cerro','playa','marianao','lisa','boyeros','arroyo','10Octubre','sanmiguel','cotorro','regla','guanabacoa','h.este','divico') ; 
    
        //consultas base datos
     $j=0;
    $cont60=0;
    $cont90=0;
    $cont120=0;
    $cont150=0;
    $cont180=0;
    $sum=0;
    $contfila=0;
    $sumd=0;
    $cont60d=0;$cont60od=0;$cont60o=0;
    $cont120d=0;
    $cont150d=0;$cont180d=0;$cont90d=0;$contfilad=0;
        $acupnr=0;  $acudtico=0;
     $usuari=$this->get('security.context')->getToken()->getUser()->getRole();
           
            
    
    if($usuari=="ROLE_ADMIN"){
        for($i=9;$i<=24;$i++){
         $em = $this->getDoctrine()->getManager();
		$trami = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$j],"tipoExpediente"=>"tramitacion", "unidad"=>"PNR"));
                
             
    foreach ($trami as $item) {
         if (Util::dias($item->getFechaInicial())<=60  ) {  
               $cont60o++;
           }
       if($item->getAcusados()>0)
           $acupnr++;
           if (Util::dias($item->getFechaInicial())>60 && Util::dias($item->getFechaInicial())<= 90 ) {  
               $cont60++;
           }
           if (Util::dias($item->getFechaInicial())>90 && Util::dias($item->getFechaInicial())<= 120 ) {  
               $cont90++;
            }
            if (Util::dias($item->getFechaInicial())>120 && Util::dias($item->getFechaInicial())<= 150 ) {  
               $cont120++;
            }
             if (Util::dias($item->getFechaInicial())>150 && Util::dias($item->getFechaInicial())<= 180 ) {  
               $cont150++;
            }
          if (Util::dias($item->getFechaInicial())>180  ) {  
               $cont180++;
            }
    }
        $dtc = $this->getDoctrine()->getManager();
		$tram = $dtc->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$j],"tipoExpediente"=>"tramitacion", "unidad"=>"DTICO"));
         
                foreach ($tram as $ite) {
                    if (Util::dias($ite->getFechaInicial())<=60 ) {  
               $cont60od++;
           }
           
            if($item->getAcusados()>0)
           $acudtico++;
       
           if (Util::dias($ite->getFechaInicial())>60 && Util::dias($ite->getFechaInicial())<= 90 ) {  
               $cont60d++;
           }
           if (Util::dias($ite->getFechaInicial())>90 && Util::dias($ite->getFechaInicial())<= 120 ) {  
               $cont90d++;
            }
            if (Util::dias($ite->getFechaInicial())>120 && Util::dias($ite->getFechaInicial())<= 150 ) {  
               $cont120d++;
            }
             if (Util::dias($ite->getFechaInicial())>150 && Util::dias($ite->getFechaInicial())<= 180 ) {  
               $cont150d++;
            }
          if (Util::dias($ite->getFechaInicial())>180  ) {  
               $cont180d++;
            }
          
           }
           
           
         if($cont60o>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('H'.$i, $cont60o);
       $contfila+=$cont60o;  $sum+=$cont60o;$cont60o=0;
         }   
        
         if($cont60>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('I'.$i, $cont60);
       $contfila+=$cont60;  $sum+=$cont60;$cont60=0;
         }
         
         if($cont90>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('J'.$i, $cont90);
          $contfila+=$cont90;$sum+=$cont90;$cont90=0;
         } 
         if($cont120>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('K'.$i, $cont120);
         $contfila+=$cont120; $sum+=$cont120;$cont120=0;
         } 
          if($cont150>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('L'.$i, $cont150);
         $contfila+=$cont150; $sum+=$cont150;$cont150=0;
         } 
         if($cont180>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('M'.$i, $cont180);
               $contfila+=$cont180; $sum+=$cont180;$cont180=0;
         } 
         if($cont60od>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('O'.$i, $cont60od);
       $contfilad+=$cont60od;  $sumd+=$cont60od;$cont60od=0;
         }
     if($cont60d>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('P'.$i, $cont60d);
       $contfilad+=$cont60d;  $sumd+=$cont60d;$cont60d=0;
         }
         
         if($cont90d>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('Q'.$i, $cont90d);
          $contfilad+=$cont90d;$sumd+=$cont90d;$cont90d=0;
         } 
         if($cont120d>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('R'.$i, $cont120d);
         $contfilad+=$cont120d; $sumd+=$cont120d;$cont120d=0;
         } 
          if($cont150d>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('S'.$i, $cont150d);
         $contfilad+=$cont150d; $sumd+=$cont150d;$cont150d=0;
         } 
         if($cont180d>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('T'.$i, $cont180d);
               $contfilad+=$cont180d; $sumd+=$cont180d;$cont180d=0;
         } 
    
		
   
  $productos = $em->getRepository('UnoMainBundle:Tramitacion')->findByMunicipio($municipios[$j]);
  
 
           
         
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('N'.$i, $contfila);     
            $contfila=0;
          $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('U'.$i, $contfilad);     
            $contfilad=0;  
            
            if($acupnr>0)
               $phpExcelObject->setActiveSheetIndex(0)
          ->setCellValue('V'.$i, $acupnr);
            $acupnr=0;
            if($acudtico>0)
                       $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('W'.$i, $acudtico)   ; 
            $acudtico=0;
            
          
             $repository = $em->getRepository('UnoMainBundle:Traza'); 
        $query = $repository->createQueryBuilder('p') ->where(' p.municipio=:municipio' )->setParameter('municipio',$municipios[$j])->orderBy('p.fecha', 'DESC') ->getQuery();
     $pnru = $query->getResult(); 
     
      
     $fecha=  date('Y-m-d');
     if($pnru){
     if($fecha==$pnru[0]->getFecha()){
              $phpExcelObject->setActiveSheetIndex(0)  ->setCellValue('D'.$i, $pnru[1]->getTpnr())
               ->setCellValue('E'.$i, $pnru[1]->getTdtico()); 
              
              $p=$pnru[1]->getTpnr();
               $d=$pnru[1]->getTdtico();
     } else{
       $phpExcelObject->setActiveSheetIndex(0)  ->setCellValue('D'.$i, $pnru[0]->getTpnr())
               ->setCellValue('E'.$i, $pnru[0]->getTdtico()); 
        $p=$pnru[0]->getTpnr();
               $d=$pnru[0]->getTdtico();
     }}
             
             $repository = $em->getRepository('UnoMainBundle:Expediente'); 
        $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni')->setParameter('expediente','Tramitación')->setParameter('municipio',$municipios[$j]) ->setParameter('uni','PNR')->getQuery();
     $pnr = $query->getResult(); 
       $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni')->setParameter('expediente','Tramitación')->setParameter('municipio',$municipios[$j]) ->setParameter('uni','DTICO')->getQuery();
     $dtico = $query->getResult(); 
     if($pnr)
         $phpExcelObject->setActiveSheetIndex(0)  ->setCellValue('F'.$i,count($pnr));
       if($dtico)
                $phpExcelObject->setActiveSheetIndex(0)   ->setCellValue('G'.$i, count($dtico));      
            $hoy=date('Y-m-d');
           $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni and p.insertn=:inser')->setParameter('expediente','Tramitación')->setParameter('municipio',$municipios[$j]) ->setParameter('uni','PNR')->setParameter('inser',$hoy)->getQuery();
     $inipnr = $query->getResult(); 
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni and p.insertn=:inser')->setParameter('expediente','Tramitación')->setParameter('municipio',$municipios[$j]) ->setParameter('uni','DTICO')->setParameter('inser',$hoy)->getQuery();
     $inidtico = $query->getResult(); 
         if($inidtico)
          $phpExcelObject->setActiveSheetIndex(0)  ->setCellValue('Z'.$i, count($inidtico) );
             if($inipnr)      
         $phpExcelObject->setActiveSheetIndex(0)->setCellValue('Y'.$i,count($inipnr));
         
          
         
          $repository = $em->getRepository('UnoMainBundle:Cambio'); 
        $query = $repository->createQueryBuilder('p') ->where('p.dia =:expediente and p.municipio=:municipio and p.unidad=:uni and p.paso=:paso')->setParameter('expediente',$fecha)->setParameter('municipio',$municipios[$j])->setParameter('uni','PNR')->setParameter('paso','Despacho') ->getQuery();
     $concluidospnr = $query->getResult();  
     $query = $repository->createQueryBuilder('p') ->where('p.dia =:expediente and p.municipio=:municipio and p.unidad=:uni and p.paso=:paso')->setParameter('expediente',$fecha)->setParameter('municipio',$municipios[$j])->setParameter('uni','DTICO')->setParameter('paso','Despacho') ->getQuery();
     $concluidosdtico = $query->getResult();  
      $query = $repository->createQueryBuilder('p') ->where('p.dia =:expediente and p.municipio=:municipio and p.unidad=:uni and p.paso=:paso')->setParameter('expediente',$fecha)->setParameter('municipio',$municipios[$j])->setParameter('uni','PNR')->setParameter('paso','Tramitación') ->getQuery();
     $devueltospnr = $query->getResult();  
     $query = $repository->createQueryBuilder('p') ->where('p.dia =:expediente and p.municipio=:municipio and p.unidad=:uni and p.paso=:paso')->setParameter('expediente',$fecha)->setParameter('municipio',$municipios[$j])->setParameter('uni','DTICO')->setParameter('paso','Tramitación') ->getQuery();
     $devueltosdtico = $query->getResult();  
		
         if($concluidospnr)
           $phpExcelObject->setActiveSheetIndex(0)->setCellValue('AA'.$i, count( $concluidospnr));
         if($concluidosdtico)
           $phpExcelObject->setActiveSheetIndex(0)->setCellValue('AB'.$i,count($concluidosdtico));
         if($devueltospnr)
           $phpExcelObject->setActiveSheetIndex(0)->setCellValue('AC'.$i,count($devueltospnr));
         if($devueltosdtico)
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('AD'.$i,count($devueltosdtico))  ;
         
    if($productos){
          $phpExcelObject->setActiveSheetIndex(0)
               
             
           
          
            // ->setCellValue('X'.$i, $productos[0]->gettotexpdientesinidia())
         
        
         ->setCellValue('X'.$i, $productos[0]->getTotalContdia());
   
         
         
          
    }
               $j++;
          
          
        }
        
        
    
        
    }
        
        else
        
        {
        $acusid=0;$acusir=0;
        $munici=$this->get('security.context')->getToken()->getUser()->getUsername();
        $res=0;
            for($i=0;$i<=15;$i++){
             if($municipios[$i]==$munici)
                 $res=$i;
                
            }
            
                $res+=9;
            
             
          $em = $this->getDoctrine()->getManager();
		$trami = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=>$munici ,"tipoExpediente"=>"tramitacion", "unidad"=>"PNR"));
                
             
    foreach ($trami as $item) {
         if($item->getAcusados())
                        $acusir++;
         if (Util::dias($item->getFechaInicial())<=60 ) {  
               $cont60o++;
           }
       
           if (Util::dias($item->getFechaInicial())>60 && Util::dias($item->getFechaInicial())<= 90 ) {  
               $cont60++;
           }
           if (Util::dias($item->getFechaInicial())>90 && Util::dias($item->getFechaInicial())<= 120 ) {  
               $cont90++;
            }
            if (Util::dias($item->getFechaInicial())>120 && Util::dias($item->getFechaInicial())<= 150 ) {  
               $cont120++;
            }
             if (Util::dias($item->getFechaInicial())>150 && Util::dias($item->getFechaInicial())<= 180 ) {  
               $cont150++;
            }
          if (Util::dias($item->getFechaInicial())>180  ) {  
               $cont180++;
            }
    }
        $dtc = $this->getDoctrine()->getManager();
		$tram = $dtc->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $munici,"tipoExpediente"=>"tramitacion", "unidad"=>"DTICO"));
         
                foreach ($tram as $ite) {
                    if($ite->getAcusados())
                        $acusid++;
                     if (Util::dias($ite->getFechaInicial())<=60  ) {  
               $cont60od++;
           }
           
           
       
           if (Util::dias($ite->getFechaInicial())>60 && Util::dias($ite->getFechaInicial())<= 90 ) {  
               $cont60d++;
           }
           if (Util::dias($ite->getFechaInicial())>90 && Util::dias($ite->getFechaInicial())<= 120 ) {  
               $cont90d++;
            }
            if (Util::dias($ite->getFechaInicial())>120 && Util::dias($ite->getFechaInicial())<= 150 ) {  
               $cont120d++;
            }
             if (Util::dias($ite->getFechaInicial())>150 && Util::dias($ite->getFechaInicial())<= 180 ) {  
               $cont150d++;
            }
          if (Util::dias($ite->getFechaInicial())>180  ) {  
               $cont180d++;
            }
          
           }
           
           
           
        
         if($cont60o>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('H'.$res, $cont60o);
       $contfila+=$cont60o;  $sum+=$cont60o;$cont60o=0;
         }
         
          if($cont60>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('I'.$res, $cont60);
       $contfila+=$cont60;  $sum+=$cont60;$cont60=0;
         }
         
         if($cont90>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('J'.$res, $cont90);
          $contfila+=$cont90;$sum+=$cont90;$cont90=0;
         } 
         if($cont120>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('K'.$res, $cont120);
         $contfila+=$cont120; $sum+=$cont120;$cont120=0;
         } 
          if($cont150>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('L'.$res, $cont150);
         $contfila+=$cont150; $sum+=$cont150;$cont150=0;
         } 
         if($cont180>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('M'.$res, $cont180);
               $contfila+=$cont180; $sum+=$cont180;$cont180=0;
         } 
     if($cont60d>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('P'.$res, $cont60d);
       $contfilad+=$cont60d;  $sumd+=$cont60d;$cont60d=0;
         }
         
         if($cont60od>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('O'.$res, $cont60od);
       $contfilad+=$cont60od;  $sumd+=$cont60od;$cont60od=0;
         }
         
         if($cont90d>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('Q'.$res, $cont90d);
          $contfilad+=$cont90d;$sumd+=$cont90d;$con90d=0;
         } 
         if($cont120d>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('R'.$res, $cont120d);
         $contfilad+=$cont120d; $sumd+=$cont120d;$cont120d=0;
         } 
          if($cont150d>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('S'.$res, $cont150d);
         $contfilad+=$cont150d; $sumd+=$cont150d;$cont150d=0;
         } 
         if($cont180d>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('T'.$res, $cont180d);
               $contfilad+=$cont180d; $sumd+=$cont180d;$cont180d=0;
         } 
     if($acusid>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('W'.$res, $acusid);
              $acusid=0;
         } 
         
            if($acusir>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('V'.$res, $acusir);
              $acusir=0;
         } 
		
   
  $productos = $em->getRepository('UnoMainBundle:Tramitacion')->findByMunicipio($munici);
  
 
            
         
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('N'.$res, $contfila);     
            $contfila=0;
          $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('U'.$res, $contfilad);     
            $contfilad=0;  
       
             $repository = $em->getRepository('UnoMainBundle:Traza'); 
        $query = $repository->createQueryBuilder('p') ->where(' p.municipio=:municipio' )->setParameter('municipio',$munici)->orderBy('p.fecha', 'DESC') ->getQuery();
     $pnr = $query->getResult();
     $fecha=  date('Y-m-d');
     if($pnr){
     if($fecha==$pnr[0]->getFecha()){
              $phpExcelObject->setActiveSheetIndex(0)  ->setCellValue('D'.$res, $pnr[1]->getTpnr())
               ->setCellValue('E'.$res, $pnr[1]->getTdtico());  
              $p=$pnr[1]->getTpnr();
                      $d=$pnr[1]->getTdtico();
              
     } else{
       $phpExcelObject->setActiveSheetIndex(0)  ->setCellValue('D'.$res, $pnr[0]->getTpnr())
               ->setCellValue('E'.$res, $pnr[0]->getTdtico());  
       
        $p=$pnr[0]->getTpnr();
                      $d=$pnr[0]->getTdtico();
        }}
     
      $repository = $em->getRepository('UnoMainBundle:Expediente'); 
        $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni')->setParameter('expediente','Tramitación')->setParameter('municipio',$munici) ->setParameter('uni','PNR')->getQuery();
     $pnrr = $query->getResult(); 
       $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni')->setParameter('expediente','Tramitación')->setParameter('municipio',$munici) ->setParameter('uni','DTICO')->getQuery();
     $dtico = $query->getResult(); 
         $phpExcelObject->setActiveSheetIndex(0)  ->setCellValue('F'.$res,count($pnrr))
                   ->setCellValue('G'.$res, count($dtico));      
            
         
           $hoy=date('Y-m-d');
           $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni and p.insertn=:inser')->setParameter('expediente','Tramitación')->setParameter('municipio',$munici) ->setParameter('uni','PNR')->setParameter('inser',$hoy)->getQuery();
     $inipnr = $query->getResult(); 
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni and p.insertn=:inser')->setParameter('expediente','Tramitación')->setParameter('municipio',$munici) ->setParameter('uni','DTICO')->setParameter('inser',$hoy)->getQuery();
     $inidtico = $query->getResult(); 
         if($inidtico)
          $phpExcelObject->setActiveSheetIndex(0)  ->setCellValue('Z'.$res, count($inidtico) );
             if($inipnr)      
         $phpExcelObject->setActiveSheetIndex(0)->setCellValue('Y'.$res,count($inipnr));
         
          
         
           $repository = $em->getRepository('UnoMainBundle:Cambio'); 
        $query = $repository->createQueryBuilder('p') ->where('p.dia =:expediente and p.municipio=:municipio and p.unidad=:uni and p.paso=:paso  ')->setParameter('expediente',$fecha)->setParameter('municipio',$munici)->setParameter('uni','PNR')->setParameter('paso','Despacho') ->getQuery();
     $concluidospnr = $query->getResult();  
     $query = $repository->createQueryBuilder('p') ->where('p.dia =:expediente and p.municipio=:municipio and p.unidad=:uni and p.paso=:paso')->setParameter('expediente',$fecha)->setParameter('municipio',$munici)->setParameter('uni','DTICO')->setParameter('paso','Despacho') ->getQuery();
     $concluidosdtico = $query->getResult();  
      $query = $repository->createQueryBuilder('p') ->where('p.dia =:expediente and p.municipio=:municipio and p.unidad=:uni and p.paso=:paso  ')->setParameter('expediente',$fecha)->setParameter('municipio',$munici)->setParameter('uni','PNR')->setParameter('paso','Tramitación') ->getQuery();
     $devueltospnr = $query->getResult();  
     $query = $repository->createQueryBuilder('p') ->where('p.dia =:expediente and p.municipio=:municipio and p.unidad=:uni and p.paso=:paso')->setParameter('expediente',$fecha)->setParameter('municipio',$munici)->setParameter('uni','DTICO')->setParameter('paso','Tramitación') ->getQuery();
     $devueltosdtico = $query->getResult();  
		
         
           $phpExcelObject->setActiveSheetIndex(0)->setCellValue('AA'.$res, count( $concluidospnr))
          ->setCellValue('AB'.$res, count($concluidosdtico))
           ->setCellValue('AC'.$res,count($devueltospnr))
            ->setCellValue('AD'.$res,count($devueltosdtico) ) ;
     
         
    if($productos){
          $phpExcelObject->setActiveSheetIndex(0)
               
             
           
                   
             ->setCellValue('X'.$res, $productos[0]->getTotalContdia());
         
        
         
          
    }
          
        
        
    }
    
        
        
        
        
        
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('N25', $sum);
      $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('U25', $sumd);
         
         $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('B')
            ->setWidth(3,50);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('C')
            ->setWidth(11,50);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('D')
            ->setWidth(6.50);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('E')
            ->setWidth(6);
       
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('F')
            ->setWidth(5,50);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('G')
            ->setWidth(5);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('H')
            ->setWidth(5,70);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('I')
            ->setWidth(4,30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('J')
            ->setWidth(5);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('K')
            ->setWidth(4,30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('L')
            ->setWidth(5);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('M')
            ->setWidth(5);
         $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('N')
            ->setWidth(5);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('O')
            ->setWidth(5,70);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('P')
            ->setWidth(4,50);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('Q')
            ->setWidth(4,50);
       
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('R')
            ->setWidth(4);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('S')
            ->setWidth(4);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('T')
            ->setWidth(4);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('U')
            ->setWidth(5);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('V')
            ->setWidth(4);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('W')
            ->setWidth(4);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('X')
            ->setWidth(5);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('Y')
            ->setWidth(4,60);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('Z')
            ->setWidth(5);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('AA')
            ->setWidth(4);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('AB')
            ->setWidth(5);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('AC')
            ->setWidth(4,20);
         $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('AD')
            ->setWidth(4);
          $phpExcelObject->getActiveSheet(0)
        ->getStyle('D2')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
       $phpExcelObject->getActiveSheet(0)->getStyle('D2')->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(12)  ;
                $phpExcelObject->getActiveSheet(0)
        ->getStyle('Z2:AB2')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
                 $phpExcelObject->getActiveSheet(0)
        ->getStyle('N9:N25')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
                 $phpExcelObject->getActiveSheet(0)
        ->getStyle('U9:U25')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
       $phpExcelObject->getActiveSheet(0)->getStyle('Z2:AB2')->getFont()->setBold(true)
                                
   ->getColor()->setRGB('FFFFFFFF');
        $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
       $phpExcelObject->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray); 
     $phpExcelObject->getActiveSheet(0)
        ->getStyle('B4:AD8')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('90caf9');
     $phpExcelObject->getActiveSheet(0)
        ->getStyle('L25')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('90caf9');
     $phpExcelObject->getActiveSheet(0)
        ->getStyle('R25')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('90caf9');
     
        $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            
              
      
    ));               
                      
        $phpExcelObject->getActiveSheet(0)->getStyle('B4:AD8')->applyFromArray($styleArray);                
          $phpExcelObject->getActiveSheet(0)->getStyle('B9:C24')->applyFromArray($styleArray);  
          $phpExcelObject->getActiveSheet(0)->getStyle('N9:N25')->applyFromArray($styleArray); 
          $phpExcelObject->getActiveSheet(0)->getStyle('U9:U25')->applyFromArray($styleArray); 
                $phpExcelObject->getActiveSheet(0)->getStyle('L25')->applyFromArray($styleArray); 
                      $phpExcelObject->getActiveSheet(0)->getStyle('R25')->applyFromArray($styleArray); 
        $borders = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thin',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
      
    $phpExcelObject->getActiveSheet()->getStyle('B4:AD24')->applyFromArray($borders); 
     $phpExcelObject->getActiveSheet()->getStyle('L25:N25')->applyFromArray($borders);   
     $phpExcelObject->getActiveSheet()->getStyle('R25:U25')->applyFromArray($borders);   
    
    $phpExcelObject->getActiveSheet(0)->getStyle('D2')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
    $phpExcelObject->getActiveSheet(0)->getStyle('H4:O4')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
     $phpExcelObject->getActiveSheet()->getStyle('H5:U5')->getAlignment()->applyFromArray(
    array('horizontal' => 'distributed')  );
            $phpExcelObject->getActiveSheet()->getStyle('D4')->getAlignment()->applyFromArray(
    array('horizontal' => 'distributed')  );
              $phpExcelObject->getActiveSheet()->getStyle('F4')->getAlignment()->applyFromArray(
    array('horizontal' => 'distributed')  );
                $phpExcelObject->getActiveSheet()->getStyle('V4:AC4')->getAlignment()->applyFromArray(
    array('horizontal' => 'distributed')  );
                  $phpExcelObject->getActiveSheet()->getStyle('C4')->getAlignment()->applyFromArray(
    array('horizontal' => 'distributed')  );
                    $phpExcelObject->getActiveSheet()->getStyle('V4')->getAlignment()->applyFromArray(
    array('horizontal' => 'distributed')  );
        
    
        
         
   // se crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        // se crea el response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // y por último se añaden las cabeceras
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        $fiscalia=$this->get('security.context')->getToken()->getUser()->getNombre();
    
      
        
         $response->headers->set('Content-Disposition', 'attachment; filename="Hoja_Uno_'.$fiscalia.'_'.  date('d-m-Y').'.xlsx"');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
         $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        
        
        
        
        return $response;   

    }
public function tramiconAction(Request $request){
 
$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       
        // ...y le asignamos una serie de propiedades
        $phpExcelObject->getProperties()
            ->setCreator("Vabadus")
            ->setLastModifiedBy("Vabadus")
            ->setTitle("Ejemplo de exportación")
            ->setSubject("Ejemplo")
            ->setDescription("Listado de ejemplo.")
            ->setKeywords("vabadus exportar excel ejemplo");
        

 $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet(0)->setTitle('Consolidado Despacho');
      
   $phpExcelObject->setActiveSheetIndex(0)->mergeCells('B2:H2');
      $phpExcelObject->setActiveSheetIndex(0)->mergeCells('B4:B5');
         $phpExcelObject->setActiveSheetIndex(0)->mergeCells('C4:H4');
            $phpExcelObject->setActiveSheetIndex(0)->mergeCells('D23:G23');
              $phpExcelObject->setActiveSheetIndex(0)->mergeCells('I4:J4');
                    $phpExcelObject->setActiveSheetIndex(0)->mergeCells('I23:J23');
            
            $municipios=array('hvieja','centrohabana','plaza','cerro','playa','marianao','lisa','boyeros','arroyo','10Octubre','sanmiguel','cotorro','regla','guanabacoa','h.este','divico') ; 
         $j=0;$TOTALPNR=0;$cont60=0;$cont90=0;$cont120=0;$cont150=0;$cont180=0;$sum=0;$contfila=0;$contfilaD=0;$sumD=0;$toti=0;
         $sum60o=0; $sum180=0;$sum120=0;$sum90=0;$sum150=0; $contnoven=0;$con60t=0; $cont60o=0; $sum60=0;
            
            for($i=6;$i<=21;$i++){
         $em = $this->getDoctrine()->getManager();
		$trami = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$j],"tipoExpediente"=>"despacho"));
                
             
    foreach ($trami as $item) {
       
         if (Util::dias($item->getFechaInicial())<=60 ) {  
               $cont60o++;
            }
            
            
           if (Util::dias($item->getFechaInicial())>90 && Util::dias($item->getFechaInicial())<= 120 ) {  
               $cont90++;
            }
            if (Util::dias($item->getFechaInicial())>120 && Util::dias($item->getFechaInicial())<= 150 ) {  
               $cont120++;
            }
             if (Util::dias($item->getFechaInicial())>150 && Util::dias($item->getFechaInicial())<= 180 ) {  
               $cont150++;
            }
          if (Util::dias($item->getFechaInicial())>180  ) {  
               $cont180++;
            }




          if (Util::dias($item->getFechaInicial())>60 && Util::dias($item->getFechaInicial())<91  ) {  
               $cont60++;
            }

          
           }
        
         
         if($cont90>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('D'.$i, $cont90);
          $contfila+=$cont90;$sum90+=$cont90;$cont90=0;
         } 
         
         if($cont60o>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('I'.$i, $cont60o);
          $sum60o+=$cont60o;
         } 
         
         if($cont60>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('J'.$i, $cont60);
          $sum60+=$cont60;
         } 
         
         if($cont120>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('E'.$i, $cont120);
         $contfila+=$cont120; $sum120+=$cont120;$cont120=0;
         } 
          if($cont150>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('F'.$i, $cont150);
         $contfila+=$cont150; $sum150+=$cont150;$cont150=0;
         } 
         if($cont180>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('G'.$i, $cont180);
               $contfila+=$cont180; $sum180+=$cont180;$cont180=0;
         } 
    $productos = $em->getRepository('UnoMainBundle:Despacho')->findByMunicipio($municipios[$j]);
    if($trami&&$contfila>0){
    $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('H'.$i, $contfila);    
         
    }
    $contnoven+=$contfila;
    
$contfila+=$cont60o;
            $contfila+=$cont60;
         if($contfila>0)
      $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('C'.$i, $contfila);   
         $toti+=$contfila;
         $contfila=0;
         $cont60=0;
          $cont60o=0;
            $j++; 
          }
           $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('H22', $contnoven); 
   $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('D23', $contnoven); 
       $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('C22', $toti);        
       $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('D22', $sum90);        
       $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('E22', $sum120);        
       $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('F22', $sum150);        
       $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('G22', $sum180);   
       
       
       $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('I22', $sum60o); 
       $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('J22', $sum60); 
       $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('I23', $sum60+$sum60o); 
           
            
$borders = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thin',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
$phpExcelObject->getActiveSheet(0)->getStyle('B4:J22')->applyFromArray($borders);
$phpExcelObject->getActiveSheet(0)->getStyle('D23:G23')->applyFromArray($borders);
$phpExcelObject->getActiveSheet(0)->getStyle('I23:J23')->applyFromArray($borders);






 $phpExcelObject->setActiveSheetIndex(0)
         ->setCellValue('C4', 'PENDIENTE A DESPACHO')
          ->setCellValue('L4', 'FECHA: '.  date('d-m-Y'))
         ->setCellValue('C5', 'TOTAL')
         ->setCellValue('I4', 'OTROS')
          ->setCellValue('I5', '60')
         ->setCellValue('J5', '61-90')
         ->setCellValue('B2', 'CONSOLIDADO DESPACHO')
         ->setCellValue('D5', '91-120')
         ->setCellValue('E5', '121-150')
         ->setCellValue('F5', '151-180')
         ->setCellValue('G5', '+180')
         ->setCellValue('H5', 'TOTAL>90')
         ->setCellValue('B4', 'La Habana')
                ->setCellValue('B6', 'H.  Vieja')
                ->setCellValue('B7', 'C. Habana')
                ->setCellValue('B8', 'Plaza')
                ->setCellValue('B9', 'Cerro')
                 ->setCellValue('B10', 'Playa')
                 ->setCellValue('B11', 'Marianao')
            ->setCellValue('B12', 'Lisa')
            ->setCellValue('B13', 'Boyeros')
              
            ->setCellValue('B14', 'Arroyo N.')
                    ->setCellValue('B15', '10 de Octubre')
                 ->setCellValue('B16', 'San Miguel')
            ->setCellValue('B17', 'Cotorro')
            ->setCellValue('B18', 'Regla')
              
            ->setCellValue('B19', 'Guanabacoa') 
 ->setCellValue('B20', 'H.Este')
            ->setCellValue('B21', 'Divico')
              
            ->setCellValue('B22', 'Total') ;
 
 
 $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('B')
            ->setWidth(15);
 $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
 $phpExcelObject->getActiveSheet(0)->getStyle('B4:J5')->applyFromArray($styleArray); 
 $phpExcelObject->getActiveSheet(0)->getStyle('B6:B22')->applyFromArray($styleArray); 
  $phpExcelObject->getActiveSheet(0)->getStyle('C22:J22')->applyFromArray($styleArray); 
  $phpExcelObject->getActiveSheet(0)->getStyle('L4')->applyFromArray($styleArray); 
  $phpExcelObject->getActiveSheet(0)->getStyle('D23')->applyFromArray($styleArray); 
  $phpExcelObject->getActiveSheet(0)->getStyle('I23')->applyFromArray($styleArray); 
   $phpExcelObject->getActiveSheet(0)->getStyle('B4:J23')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
   $phpExcelObject->getActiveSheet(0)->getStyle('B2')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
    $phpExcelObject->getActiveSheet(0)->getStyle('I4')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
     $phpExcelObject->getActiveSheet(0)->getStyle('B2')->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(12)
            
                                ->getColor()->setRGB('FFFFFFFF');
   $phpExcelObject->getActiveSheet(0)
        ->getStyle('C22:J22')
           
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
    $phpExcelObject->getActiveSheet(0)
        ->getStyle('L4')
           
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
      $phpExcelObject->getActiveSheet(0)
        ->getStyle('C22:J22')
           
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
    $phpExcelObject->getActiveSheet(0)
        ->getStyle('I23')
           
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
    
      $phpExcelObject->getActiveSheet(0)
        ->getStyle('H6:H21')
           
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
    $phpExcelObject->getActiveSheet(0)
        ->getStyle('D23')
           
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
    $phpExcelObject->getActiveSheet(0)
        ->getStyle('B4:J5')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('90caf9');
     $phpExcelObject->getActiveSheet(0)
        ->getStyle('B4:B22')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('90caf9');
      
    $phpExcelObject->setActiveSheetIndex(0)->mergeCells('L4:M4');
    
  $phpExcelObject->createSheet(1);

  $phpExcelObject->setActiveSheetIndex(1);
        $phpExcelObject->getActiveSheet(1)->setTitle('Consolidado Tramitacion');
      
   $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B2:H2');
      $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B4:B5');
         $phpExcelObject->setActiveSheetIndex(1)->mergeCells('C4:H4');
            $phpExcelObject->setActiveSheetIndex(1)->mergeCells('D23:G23');
           $phpExcelObject->setActiveSheetIndex(1)->mergeCells('I23:J23');
                      $phpExcelObject->setActiveSheetIndex(1)->mergeCells('L4:M4');
            
            
$borders = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thin',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
$phpExcelObject->getActiveSheet(1)->getStyle('B4:J22')->applyFromArray($borders);
$phpExcelObject->getActiveSheet(1)->getStyle('D23:G23')->applyFromArray($borders);
$phpExcelObject->getActiveSheet(1)->getStyle('I23:J23')->applyFromArray($borders);



  $municipios=array('hvieja','centrohabana','plaza','cerro','playa','marianao','lisa','boyeros','arroyo','10Octubre','sanmiguel','cotorro','regla','guanabacoa','h.este','divico') ; 
         $colume=0;$j=0;$cont60t=0;$sum60t=0;$cont90t=0;$cont120t=0;$cont150t=0;$cont180t=0;$sumt=0;$contfilat=0;$contfilaDt=0;$sumDt=0;$totil=0;
          $sum180t=0;$sum120t=0;$sum90t=0;$sum150t=0; $contnovent=0; $tramitacion=0;$cont60to=0;$sum60to=0;
            
            for($i=6;$i<=21;$i++){
         $em = $this->getDoctrine()->getManager();
		$trami = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$j],"tipoExpediente"=>"tramitacion"));
                
             
    foreach ($trami as $item) {
         if (Util::dias($item->getFechaInicial())<=60  ) {  
               $cont60to++;
            }
       
           if (Util::dias($item->getFechaInicial())>90 && Util::dias($item->getFechaInicial())<= 120 ) {  
               $cont90t++;
            }
            if (Util::dias($item->getFechaInicial())>120 && Util::dias($item->getFechaInicial())<= 150 ) {  
               $cont120t++;
            }
             if (Util::dias($item->getFechaInicial())>150 && Util::dias($item->getFechaInicial())<= 180 ) {  
               $cont150t++;
            }
          if (Util::dias($item->getFechaInicial())>180  ) {  
               $cont180t++;
            }
            
             if (Util::dias($item->getFechaInicial())>60 && Util::dias($item->getFechaInicial())<91  ) {  
               $cont60t++;
            }
          
           }
       
         if($cont90t>0){
         $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('D'.$i, $cont90t);
          $contfilat+=$cont90t;$sum90t+=$cont90t;
          $cont90t=0;
         } 
         if($cont120t>0){
         $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('E'.$i, $cont120t);
         $contfilat+=$cont120t; $sum120t+=$cont120t;$cont120t=0;
         } 
          if($cont150t>0){
         $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('F'.$i, $cont150t);
         $contfilat+=$cont150t; $sum150t+=$cont150t;$cont150t=0;
         } 
         if($cont180t>0){
         $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('G'.$i, $cont180t);
               $contfilat+=$cont180t; $sum180t+=$cont180t;$cont180t=0;
         } 
 if($cont60to>0){
         $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('I'.$i, $cont60to);
                $sum60to+=$cont60to;
         } 
         
          if($cont60t>0){
         $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('J'.$i, $cont60t);
                $sum60t+=$cont60t;
         } 

     
$tramitacion=$contfilat;
 $tramitacion+=$cont60t;
 $tramitacion+=$cont60to;
             if($tramitacion>0)
          $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('C'.$i, $tramitacion);     
        $totil+=$tramitacion;
        $tramitacion=0;
       
        
        
              
         $cont60t=0;$cont60to=0;
         
if($contfilat>0){
    $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('H'.$i, $contfilat);    
         $colume+=$contfilat;
    $contfilat=0;   }     
    
         
         
$j++;

            }
            
            $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('C22', $totil);  
    $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('H22', $colume);  
    $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('D22', $sum90t) 
       ->setCellValue('E22', $sum120t)
        ->setCellValue('F22', $sum150t)
         ->setCellValue('G22', $sum180t)
               ->setCellValue('I22', $sum60to)
                 ->setCellValue('J22', $sum60t)
             ->setCellValue('I23', $sum60t + $sum60to)
            ->setCellValue('D23', $colume);    
            

 $phpExcelObject->setActiveSheetIndex(1)
         ->setCellValue('C4', 'PENDIENTE A TRAMITACION')
         
         
           ->setCellValue('I5', '60')
           ->setCellValue('J5', '61-90')
         
         
         
         ->setCellValue('C5', 'TOTAL')
         ->setCellValue('D5', '91-120')
         ->setCellValue('E5', '121-150')
         ->setCellValue('F5', '151-180')
         ->setCellValue('L4', 'FECHA: '.date('d-m-Y'))
         ->setCellValue('G5', '+180')
          ->setCellValue('B2', 'CONSOLIDADO TRAMITACION')
         ->setCellValue('H5', 'TOTAL>90')
         ->setCellValue('B4', 'La Habana')
                ->setCellValue('B6', 'H.  Vieja')
                ->setCellValue('B7', 'C. Habana')
                ->setCellValue('B8', 'Plaza')
                ->setCellValue('B9', 'Cerro')
                 ->setCellValue('B10', 'Playa')
                 ->setCellValue('B11', 'Marianao')
            ->setCellValue('B12', 'Lisa')
            ->setCellValue('B13', 'Boyeros')
              
            ->setCellValue('B14', 'Arroyo N.')
                    ->setCellValue('B15', '10 de Octubre')
                 ->setCellValue('B16', 'San Miguel')
            ->setCellValue('B17', 'Cotorro')
            ->setCellValue('B18', 'Regla')
              
            ->setCellValue('B19', 'Guanabacoa') 
 ->setCellValue('B20', 'H.Este')
            ->setCellValue('B21', 'Divico')
         
          ->setCellValue('I4', 'Otros')
              
            ->setCellValue('B22', 'Total') ;

$styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
$phpExcelObject->setActiveSheetIndex(1)
            ->getColumnDimension('B')
            ->setWidth(15);
 $phpExcelObject->getActiveSheet(1)->getStyle('B4:J5')->applyFromArray($styleArray); 
 $phpExcelObject->getActiveSheet(1)->getStyle('L4')->applyFromArray($styleArray); 
 $phpExcelObject->getActiveSheet(1)->getStyle('B6:B22')->applyFromArray($styleArray); 
  $phpExcelObject->getActiveSheet(1)->getStyle('C22:J24')->applyFromArray($styleArray); 
  $phpExcelObject->getActiveSheet(1)->getStyle('D23')->applyFromArray($styleArray); 
   $phpExcelObject->getActiveSheet(1)->getStyle('B4:H23')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
   $phpExcelObject->getActiveSheet(1)->getStyle('B2')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
     $phpExcelObject->getActiveSheet(1)->getStyle('B2')->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(12)
            
                                ->getColor()->setRGB('FFFFFFFF');
   $phpExcelObject->getActiveSheet(1)
        ->getStyle('C22:H22')
           
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('b2dfdb');
     $phpExcelObject->getActiveSheet(1)
        ->getStyle('H6:H21')
           
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('b2dfdb');
    $phpExcelObject->getActiveSheet(1)
        ->getStyle('L4')
           
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('b2dfdb');
    $phpExcelObject->getActiveSheet(1)
        ->getStyle('I22:J23')
           
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('b2dfdb');
    $phpExcelObject->getActiveSheet(1)
        ->getStyle('D23')
           
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('b2dfdb');
    $phpExcelObject->getActiveSheet(1)
        ->getStyle('B4:J5')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('4db6ac');
     $phpExcelObject->getActiveSheet(1)
        ->getStyle('B4:B22')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('4db6ac');
      
    $phpExcelObject->setActiveSheetIndex(1)->mergeCells('I4:J4');
    $phpExcelObject->getActiveSheet(1)->getStyle('B2')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
     $phpExcelObject->getActiveSheet(1)->getStyle('I4:J23')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')
); 
    
     $phpExcelObject->getActiveSheet(1)->getStyle('B2')->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(12)
            
                                ->getColor()->setRGB('FFFFFFFF');
   // se crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        // se crea el response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // y por último se añaden las cabeceras
        
        $response->headers->set('Content-Disposition', 'attachment; filename="Consolidado_Trami_Desp_'.date('d-m-Y').'.xlsx"');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
         $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        
        
        
        
        return $response;

     

}
 
public function concluidosAction(Request $request){
 
$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       
        // ...y le asignamos una serie de propiedades
        $phpExcelObject->getProperties()
            ->setCreator("Vabadus")
            ->setLastModifiedBy("Vabadus")
            ->setTitle("Ejemplo de exportación")
            ->setSubject("Ejemplo")
            ->setDescription("Listado de ejemplo.")
            ->setKeywords("vabadus exportar excel ejemplo");
        

 $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet(0)->setTitle('Despachados_Concluidos');

 $phpExcelObject->setActiveSheetIndex(0)->mergeCells('D4:D8');
           $phpExcelObject->setActiveSheetIndex(0)->mergeCells('E4:E8');
             $phpExcelObject->setActiveSheetIndex(0)->mergeCells('F4:F8');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('G4:G8');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('H4:H8');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('I4:I8');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('J4:J8');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('K4:K8');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('L4:L8');
                $phpExcelObject->setActiveSheetIndex(0)->mergeCells('M4:M8');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('N4:N8');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('O4:O8');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('P4:P8');
                $phpExcelObject->setActiveSheetIndex(0)->mergeCells('Q4:Q8');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('R4:R8');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('S4:S8');
                $phpExcelObject->setActiveSheetIndex(0)->mergeCells('T4:T8');
                $phpExcelObject->setActiveSheetIndex(0)->mergeCells('U4:U8');
               $phpExcelObject->setActiveSheetIndex(0)->mergeCells('C4:C8');
             $phpExcelObject->setActiveSheetIndex(0)->mergeCells('B4:B8');
             $phpExcelObject->setActiveSheetIndex(0)->mergeCells('B1:U1');
              $phpExcelObject->setActiveSheetIndex(0)->mergeCells('B25:C25'); 
               
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('B4', 'No')
                   ->setCellValue('B1', 'TOTAL DE EFP DESPACHADOS Y CONCLUIDOS')
                ->setCellValue('B9', '1')
                ->setCellValue('B10', '2')
                ->setCellValue('B11', '3')
                 ->setCellValue('B12', '4')
                 ->setCellValue('B13', '5')
            ->setCellValue('B14', '6')
            ->setCellValue('B15', '7')
              
            ->setCellValue('B16', '8')
                
            ->setCellValue('B17', '9')
            
            ->setCellValue('B18', '10')
                 ->setCellValue('B19', '11')
            
            ->setCellValue('B20', '12')
                
          ->setCellValue('B21', '13')
            ->setCellValue('B22', '14')
         ->setCellValue('B23', '15')
            
          ->setCellValue('B24', '16')
         ->setCellValue('C4', 'Municipio')
        ->setCellValue('C9', 'H/Vieja')
            
          ->setCellValue('C10', 'C.Hab')
         ->setCellValue('C11', 'Plaza')
    ->setCellValue('C12', 'Cerro')
                ->setCellValue('C13', 'Playa')
                ->setCellValue('C14','Marianao' )
                 ->setCellValue('C15', 'La Lisa')
                 ->setCellValue('C16', 'Boyeros')
            ->setCellValue('C17', 'Arroyo N')
            ->setCellValue('C18', '10-Oct')
              
            ->setCellValue('C19', 'San MP')
                
            ->setCellValue('C20', 'Cotorro')
            
            ->setCellValue('C21', 'Regla')
                 ->setCellValue('C22', 'Guanabacoa')
            
            ->setCellValue('C23', 'H.Este')
                 ->setCellValue('B25', 'Total')
                
          ->setCellValue('C24', 'Divico')
     ->setCellValue('D4', 'EFP Iniciados ')
          ->setCellValue('E4', 'EFP Tramitacion')        
  ->setCellValue('F4', 'Total Concluidos  ')
         ->setCellValue('G4', 'Total EFP Despachados ')         
         ->setCellValue('H4', 'EFP P.Despacho  ')
          ->setCellValue('I4', 'C-P Pendientes a Revision F.P ')        
  ->setCellValue('J4', 'Despachados en el dia   ')
         ->setCellValue('K4', 'Concluciones  ')          
          ->setCellValue('L4', 'Sobreseimienos  ')        
  ->setCellValue('M4', 'Inhibitorias  ')
         ->setCellValue('N4', '8.3  ')             
            ->setCellValue('O4', ' Otros  ')        
  ->setCellValue('P4', ' Total Controlados  ')
         ->setCellValue('Q4', ' Dev Tribunal Recibidas ')        
                    ->setCellValue('R4', '  Tot SIS Recibidas')        
  
            ->setCellValue('Q2', 'FECHA')   
               ->setCellValue('R2',date('d-m-Y')) ;  
                $phpExcelObject->getActiveSheet(0)->getStyle('B1')->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(15) ->getColor()->setRGB('3F51B5');
              $phpExcelObject->getActiveSheet(0)->getStyle('B1')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')); 
        $phpExcelObject->getActiveSheet(0)
        ->getStyle('Q2:R2')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd');
        
        
         $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
        
        $phpExcelObject->getActiveSheet(0)->getStyle('Q2:R2')->applyFromArray($styleArray); 
              $phpExcelObject->getActiveSheet(0)
        ->getStyle('B4:R8')
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('4db6ac');   
                 $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
            'endcolor' => array(
                'argb' => 'FFFFFFFF',
               
            ),
               'horizontal' => 'center',
      
    );
                 $borders = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thin',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
               $phpExcelObject->getActiveSheet(0)->getStyle('B4:R8')->applyFromArray($styleArray); 
                $phpExcelObject->getActiveSheet(0)->getStyle('B25:R25')->applyFromArray($styleArray);  
               $phpExcelObject->getActiveSheet(0)->getStyle('B9:C24')->applyFromArray($styleArray);  
             $phpExcelObject->getActiveSheet(0)->getStyle('B4:R25')->applyFromArray($borders);     
                 
               $phpExcelObject->getActiveSheet(0)->getStyle('B4:U8')->getAlignment()->applyFromArray(
    array('horizontal' => 'distributed')  );  
                $phpExcelObject->getActiveSheet(0)->getStyle('B9:R25')->getAlignment()->applyFromArray(
    array('horizontal' => 'center')  );  
                 
                 
                 
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('B')
            ->setWidth(4);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('C')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('D')
            ->setWidth(11);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('E')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('F')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('G')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('H')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('I')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('J')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('K')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('L')
            ->setWidth(10);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('M')
            ->setWidth(8,50);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('N')
            ->setWidth(14);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('O')
            ->setWidth(9);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('P')
            ->setWidth(9);
               $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('Q')
            ->setWidth(10);
             $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('R')
            ->setWidth(11);
           
             $j=0;$tconcluidos=0;$tiniciados=0;$ttrami=0;$tdespa=0;$tdesdia=0;$tconcluciones=0;$tsobre=0;$tini=0;$tocho=0;$tcontro=0;$tdev=0;$tsis=0;$totro=0;
              $municipios=array('hvieja','centrohabana','plaza','cerro','playa','marianao','lisa','boyeros','arroyo','10Octubre','sanmiguel','cotorro','regla','guanabacoa','h.este','divico') ;       
       for($i=9;$i<=24;$i++){
           
           
            $em = $this->getDoctrine()->getManager();
           $iniciados = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$j],"insertn"=>  date('Y-m-d')));
		$trami = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$j],"tipoExpediente"=>"Tramitación"));
               $despa = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$j],"tipoExpediente"=>"Despacho"));
         $contro = $em->getRepository('UnoMainBundle:Tramitacion')->findBy(array('municipio'=> $municipios[$j]));
               $desdia = $em->getRepository('UnoMainBundle:Despacho')->findBy(array('municipio'=> $municipios[$j]));
               
               
             $contpp=0;$contsis=0;
               foreach ($trami as $item){
               if($item->getDEVTTP())
                   $contpp++;
                if($item->getSIS())
                   $contsis++;
                   
                   
                   
                   
                   
                   
                   
               }
               
               if(   $contpp>0){
               $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('Q'.$i, $contpp );  
               $tdev+=$contpp;
               }
                 if(   $contsis>0){
               $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('R'.$i, $contsis );           
     $tsis+=$contsis;
                 }
               
               
                $repository = $em->getRepository('UnoMainBundle:Cambio'); 
        $query = $repository->createQueryBuilder('p') ->where('p.dia =:expediente and p.municipio=:municipio and p.unidad=:uni and p.paso=:paso')->setParameter('expediente',  date('Y-m-d'))->setParameter('municipio',$municipios[$j])->setParameter('uni','PNR')->setParameter('paso','Despacho') ->getQuery();
     $concluidospnr = $query->getResult();  
     $query = $repository->createQueryBuilder('p') ->where('p.dia =:expediente and p.municipio=:municipio and p.unidad=:uni and p.paso=:paso')->setParameter('expediente',date('Y-m-d'))->setParameter('municipio',$municipios[$j])->setParameter('uni','DTICO')->setParameter('paso','Despacho') ->getQuery();
     $concluidosdtico = $query->getResult(); 
               
           if(count($concluidospnr)+count(   $concluidosdtico)>0){
               $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('F'.$i, count($concluidospnr)+count(   $concluidosdtico));    
               $tconcluidos+=count($concluidospnr)+count(   $concluidosdtico);
           }
     
     if(count($trami)){
               $phpExcelObject->setActiveSheetIndex(0)
     ->setCellValue('E'.$i, count($trami)); 
               
               $ttrami+=count($trami);
               
     }
               if(count($despa)){
                $phpExcelObject->setActiveSheetIndex(0)
               ->setCellValue('H'.$i, count($despa)); 
                $tdespa+=count($despa);
               }
               
                if(count($iniciados)){
                 $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('D'.$i, count($iniciados));
                 $tiniciados+= count($iniciados);
                }
                if($desdia and $desdia[0]->getDespachadosDia()>0) {
                 $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('J'.$i, $desdia[0]->getDespachadosDia());
                 
                 $tdesdia+=$desdia[0]->getDespachadosDia();
                }
                
                 if($desdia and $desdia[0]->getConcluciones()>0) {
                 $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('K'.$i, $desdia[0]->getConcluciones());  
                 
                 $tconcluciones+=$desdia[0]->getConcluciones();
                 }

                 
                 if($desdia and $desdia[0]->getSobreseimientos()>0){ 
                 $phpExcelObject->setActiveSheetIndex(0)
                 ->setCellValue('L'.$i, $desdia[0]->getSobreseimientos());
                 $tsobre+=$desdia[0]->getSobreseimientos();
                 
                 
                 
                 }
             
             if($desdia and $desdia[0]->getInhibitorias()>0) {
                 $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('M'.$i, $desdia[0]->getInhibitorias());
                 
                 $tini+=$desdia[0]->getInhibitorias();
             }
             
              if($desdia and $desdia[0]->getochotres()>0) {
                 $phpExcelObject->setActiveSheetIndex(0)
              ->setCellValue('N'.$i, $desdia[0]->getochotres());
                 
               $tocho+=  $desdia[0]->getochotres();
                 
              }
             
                  if($desdia and $desdia[0]->getOtros()>0) {
                 $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('O'.$i, $desdia[0]->getOtros());
                  
                 $totro+=$desdia[0]->getOtros();
                  }
                  
                   if($contro and $contro[0]->getTotalContdia()>0) {
                 $phpExcelObject->setActiveSheetIndex(0)
                   ->setCellValue('P'.$i, $contro[0]->getTotalContdia());
                 
                 $tcontro+=$contro[0]->getTotalContdia();
                   }
                   
                
        $j++;        
                
       }
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('F25', $tconcluidos);
          $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('D25', $tiniciados);
           $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('E25', $ttrami);
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('H25', $tdespa);
             $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('J25', $tdesdia);
              $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('K25', $tconcluciones);
              $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('L25', $tsobre);
                $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('M25', $tini);
                  $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('N25', $tocho);
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('O25', $totro);
          $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('P25', $tcontro);
           $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('Q25', $tdev);
           $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('R25', $tsis);
// se crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        // se crea el response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // y por último se añaden las cabeceras
        
        $response->headers->set('Content-Disposition', 'attachment; filename="Total_EFP_Despachados_Concluidos'.date('d-m-Y').'.xlsx"');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
         $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        
        
        
        
        return $response;

     

}
 


    }
