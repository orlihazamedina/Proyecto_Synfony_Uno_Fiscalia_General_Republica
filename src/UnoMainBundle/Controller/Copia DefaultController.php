<?php

namespace UnoMainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UnoMainBundle\Util\Util;

use UnoMainBundle\Entity\Expediente;
use Symfony\Component\HttpFoundation\Response;
use UnoMainBundle\Entity\Tramitacion;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('UnoMainBundle:Default:index.html.twig', array('name' => $name));
    }
 
    

public function homeAction()
    {
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
     $productos = $query->getResult();    
   }
    
    $c=0;
                foreach ( $productos as $item){
                    
                  $termi[$c]=Util::dias($item->getFechaInicial()); 
                  $c++;
                }
          $active="active"; 
          $actived="";
     return $this->render('UnoMainBundle:User:home.html.twig', array("productos"=>$productos,"termi"=>$termi,"c"=>$c,"active"=>$active,"actived"=>$actived));   
        
       
        
        
    }
    public function orderAction($parametro)
    {
        $c=0;


        // $em = $this->getDoctrine()->getManager();
	//	$productos = $em->getRepository('UnoMainBundle:Expediente')->findBytipoExpediente("Tramitacion");
   $repository = $this->getDoctrine() ->getRepository('UnoMainBundle:Expediente'); 
   $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
   $us=$this->get('security.context')->getToken()->getUser()->getUsername();
   if($usuario=="ROLE_USER"){
    $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio')->setParameter('expediente', 'Tramitacion')->setParameter('municipio',$us)  ->orderBy('p.'.$parametro, 'ASC') ->getQuery();
     $productos = $query->getResult();  
   }else
       {
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente')->setParameter('expediente', 'Tramitacion')  ->orderBy('p.'.$parametro, 'ASC') ->getQuery();
     $productos = $query->getResult();    
   }
   
    
    
                foreach ( $productos as $item){
                    
                  $termi[$c]=Util::dias($item->getFechaInicial()); 
                  $c++;
                }
              
                return $this->render('UnoMainBundle:User:home.html.twig', array("productos"=>$productos,"termi"=>$termi,"c"=>$c));   
        
       
     
        
    }
    public function orderdAction($parametro)
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
    $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio')->setParameter('expediente', 'Despacho')->setParameter('municipio',$us)  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productos = $query->getResult();  
   }else
       {
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente')->setParameter('expediente', 'Despacho')  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productos = $query->getResult();    
   }
    
    
                foreach ( $productos as $item){
                    
                  $termi[$c]=Util::dias($item->getFechaInicial()); 
                    $entre[$c]=Util::dias($item->getFechaEntrega()); 
                  $c++;
                }
              
                return $this->render('UnoMainBundle:User:despa.html.twig', array("productos"=>$productos,"termi"=>$termi,"entre"=>$entre));   
        
       
        
        
    }
public function despaAction()
    {
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
      $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente')->setParameter('expediente', 'Despacho')  ->orderBy('p.fechainicial', 'ASC') ->getQuery();
     $productos = $query->getResult();    
   }
      $c=0;
                foreach ( $productos as $item){
                    
                  $termi[$c]=Util::dias($item->getFechaInicial()); 
                   $entre[$c]=Util::dias($item->getFechaEntrega()); 
                  $c++;
                }
                $active=""; 
          $actived="active";
     return $this->render('UnoMainBundle:User:despa.html.twig', array("productos"=>$productos,"termi"=>$termi,"entre"=>$entre,'active'=>$active,'actived'=>$actived));   
        
       
        
        
    }
  
   
    public function excelAction(Request $request)
    {
    
       

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
            ->getColumnDimension('B')
            ->setWidth(5);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('C')
            ->setWidth(10);
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
            ->setWidth(15);
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
     $productos = $query->getResult();    
   }
	
                  
          $row =7;
          $cont=1;
        foreach ($productos as $item) {
            
$dias= Util::dias($item->getFechaInicial());


            $a= date_create($item->getFechaInicial());
        
             
            $phpExcelObject->setActiveSheetIndex(0)
                 ->setCellValue('B'.$row, $cont)    
               ->setCellValue('C'.$row, $item->getNumExpediente())
                ->setCellValue('D'.$row, $item->getMunicipio())
                ->setCellValue('E'.$row, $item->getUnidad())
                ->setCellValue('F'.$row, $item->getAcusados())
        ->setCellValue('G'.$row, $item->getDelito())
                             ->setCellValue('H'.$row,date_format($a, "d-m-Y") )
                 ->setCellValue('I'.$row,$dias)  
             ->setCellValue('J'.$row,$item->getDEVTTP())
                ->setCellValue('K'.$row,$item->getSIS())
                ->setCellValue('L'.$row,$item-> getDEVFISCAL() )  
               ->setCellValue('M'.$row,$item->getPronostico())
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
             
       $municipios=array('habvieja','centrohabana','plaza','cerro','playa','marianao','lisa','boyeros','arroyo','10Octubre','SanMiguel','Cotorro','Regla','Guanabacoa','H.Este','Divico') ;       
    $cont60=0;$cont90=0;$cont120=0;$cont150=0;$cont180=0;$ca=0;
      
         $em = $this->getDoctrine()->getManager();
		$trami = $em->getRepository('UnoMainBundle:Expediente')->findBytipoExpediente("despacho");
                
             
    foreach ($trami as $item) {
        
        if($item->getacusados())
        {
         $ca++;   
            
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
                             
       $de=$this->get('security.context')->getToken()->getUser()->getUsername();            
       
    $trami = $em->getRepository('UnoMainBundle:Despacho')->findByMunicipio($de);
    $totalpendientedespacho=0;$prision=0;$despachadosdia=0;   $term10dias=0; $recitribu=0;$sis60dias=0; $concluciones=0;$sobreseimientos=0;$inhibitorias=0;$ocho=0; $otros=0;  $remi=0;
   foreach ($trami as $item) {
     $totalpendientedespacho+=$item->gettotalExpPendientes();
     $prision+=$item->getacusadosHasta60();
     $despachadosdia+=$item->getdespachadosDia();
    $term10dias+=$item-> gettermmas10dias();
    $recitribu+=$item-> getdevtrirecibidas60dias();
     $sis60dias+=$item-> gettotalSis60dias();  
    $concluciones+=$item-> getconcluciones();
    $sobreseimientos+=$item-> getsobreseimientos();
         $inhibitorias+= $item-> getinhibitorias();
            $ocho+=$item->getochotres();
         $otros+=   $item->getotros();
           $remi+= $item->getremitidosFactura();
   }
   $prision+=$ca;
   $phpExcelObject->setActiveSheetIndex(1)
                 ->setCellValue('G9',$prision)   ;
   $phpExcelObject->setActiveSheetIndex(1)
                 ->setCellValue('G10',$totalpendientedespacho)   ;
   
   $totalpendientedespacho+=$cont60+$cont90+$cont120+$cont150+$cont180;
     $phpExcelObject->setActiveSheetIndex(1)
                 ->setCellValue('G16',$despachadosdia)   
       ->setCellValue('G8',$totalpendientedespacho)  
                 ->setCellValue('G17',$term10dias)   
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
                ->setCellValue('B7', 'DESPACHO')
                 ->setCellValue('B8', 'Total de EFP Pendiente de Despacho ')
                ->setCellValue('B9', 'Con acusados en Prisión Provisional ')
                   ->setCellValue('B10', 'Hasta 60 días ')
                   ->setCellValue('B11', 'De 61 a 90 días ')
                 ->setCellValue('B12', 'De 91 a 120 días ')
                 ->setCellValue('B13', 'De 121 a 150 días ')
                  ->setCellValue('B14', 'De 151 a 180 días ')
                ->setCellValue('B15', 'De 151 a 180 días ')
                ->setCellValue('B16', 'Total de despachados en el día ')
                    ->setCellValue('B16', 'Total de despachados en el día ')
                    ->setCellValue('B16', 'Total de despachados en el día ')
                    ->setCellValue('B17', 'En el término superior a 10 días')
                ->setCellValue('B18', 'Devoluciones del Tribunal Recibidas')
                ->setCellValue('B19', ' Total de SIS recibidas')
                   ->setCellValue('B21', ' Tipos de despacho:')
                   ->setCellValue('B22', ' Conclusiones')
                   ->setCellValue('B23', ' Sobreseimientos')
                   ->setCellValue('B24', ' Inhibitorias')
                 ->setCellValue('B25', ' 8.3')
                   ->setCellValue('B26', ' Otros')
                   ->setCellValue('B27', ' Remitidos al Tribunal según factura');
     
                
                
                
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
            ->setCellValue('H6', 'TERM-TRAMITACION')
                 ->setCellValue('I6', 'FECHA/E')
            
            ->setCellValue('J6', 'TERM-DESPACHO')
                
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
            ->setWidth(10);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('D')
            ->setWidth(12);
       
          
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('E')
            ->setWidth(12);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('F')
            ->setWidth(19);
         $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('G')
            ->setWidth(11);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('H')
            ->setWidth(19);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('I')
            ->setWidth(11);
        $phpExcelObject->setActiveSheetIndex(2)
            ->getColumnDimension('J')
            ->setWidth(16);
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
            ->setWidth(25);
        
 $TipoExpedient="despacho";

//$repository = $this->getDoctrine()->getRepository('UnoMainBundle:Expediente');
		//$producto = $repository->findByNombre($nombre);
	//	$producto = $repository->findByTipoExpediente($TipoExpediente);
		//$producto = $repository->findBy(array("nombre"=>$nombre), 20, 0);
	
	
         $repository = $this->getDoctrine() ->getRepository('UnoMainBundle:Expediente'); 
   $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
   $us=$this->get('security.context')->getToken()->getUser()->getUsername();
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
            
$dias= Util::dias($item->getFechaInicial());
$despacho=Util::dias($item->getFechaEntrega());

if(60<$dias&&$dias<=80)
    $phpExcelObject->getActiveSheet(2)
        ->getStyle('I'.$row)
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd'); 
   if(80<$dias&&$dias<=90)
    $phpExcelObject->getActiveSheet(2)
        ->getStyle('I'.$row)
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd'); 
        if(90<$dias)
    $phpExcelObject->getActiveSheet(2)
        ->getStyle('I'.$row)
        ->getFill()
        ->setFillType('solid')
        ->getStartColor()
        ->setRGB('e3f2fd'); 
                   
            
            
            
             
            $phpExcelObject->setActiveSheetIndex(2)
                 ->setCellValue('B'.$row, $cont)    
               ->setCellValue('C'.$row, $item->getNumExpediente())
                ->setCellValue('D'.$row, $item->getMunicipio())
              
                ->setCellValue('E'.$row,$item->getAcusados())
               ->setCellValue('G'.$row, $item->getFechaInicial())
                        
                 ->setCellValue('F'.$row,$item->getDelito())     
                 ->setCellValue('H'.$row,$dias)  
                  ->setCellValue('I'.$row, $item->getFechaEntrega())
                 ->setCellValue('J'.$row,$despacho) 
         ->setCellValue('K'.$row,$item->getDEVTTP())
                ->setCellValue('L'.$row, $item->getSIS())
                ->setCellValue('M'.$row,$item-> getDEVFISCAL() )  
                ->setCellValue('N'.$row, $item->getPronostico())
                ->setCellValue('O'.$row, $item->getObservaciones());
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
                     $phpExcelObject->setActiveSheetIndex(0)->mergeCells('S25:T25');
                   
       
        // escribimos en distintas celdas del documento el título de los campos que vamos a exportar
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
              
            ->setCellValue('S25', 'Total Dtico')
                
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
                 ->setCellValue('V8', 'PNR')  
                 ->setCellValue('W8', 'Dtic')  
                 ->setCellValue('Y8', 'PNR') ; 
       $municipios=array('vieja','centrohabana','plaza','cerro','playa','marianao','lisa','boyeros','arroyo','10Octubre','SanMiguel','Cotorro','Regla','Guanabacoa','H.Este','Divico') ;
    
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
    $cont60d=0;
    $cont120d=0;
    $cont150d=0;$cont180d=0;$cont90d=0;$contfilad=0;
    
     $usuari=$this->get('security.context')->getToken()->getUser()->getRole();
           
            
    
    if($usuari=="ROLE_ADMIN"){
        for($i=9;$i<=24;$i++){
         $em = $this->getDoctrine()->getManager();
		$trami = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$j],"tipoExpediente"=>"tramitacion", "unidad"=>"PNR"));
                
             
    foreach ($trami as $item) {
       
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
		$tram = $dtc->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$j],"tipoExpediente"=>"tramitacion", "unidad"=>"DITICO"));
         
                foreach ($tram as $ite) {
       
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
     if($cont60d>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('P'.$i, $cont60d);
       $contfilad+=$cont60d;  $sumd+=$cont60d;$cont60d=0;
         }
         
         if($cont90d>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('Q'.$i, $cont90d);
          $contfilad+=$cont90d;$sumd+=$cont90d;$con90d=0;
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
  
 
            if($productos){
         $contfila+=$productos[0]->gettotalExp60diasPNR();   
         $contfilad+=$productos[0]->gettotalExp60diasDTICO();  
            }
         
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('N'.$i, $contfila);     
            $contfila=0;
          $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('U'.$i, $contfilad);     
            $contfilad=0;  
       
                
    if($productos){
          $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('D'.$i, $productos[0]->getTotalEFPiniciodiaPNR())
               ->setCellValue('E'.$i, $productos[0]->getTotalEFPiniciodiaDTICO())
               ->setCellValue('F'.$i, $productos[0]->gettotalEFPcierrediaPNR())
                   ->setCellValue('G'.$i, $productos[0]->gettotalEFPcierrediaDtico())
            ->setCellValue('H'.$i, $productos[0]->gettotalExp60diasPNR())
               ->setCellValue('O'.$i, $productos[0]->gettotalExp60diasDTICO())     
           ->setCellValue('V'.$i, $productos[0]->getcantExpacusadosPNR())
                    ->setCellValue('W'.$i, $productos[0]->getcantExpacusadosDTICO())
             ->setCellValue('X'.$i, $productos[0]->gettotexpdientesinidia())
          ->setCellValue('Y'.$i, $productos[0]->gettotalexpFasePrepaIniPNR())
        ->setCellValue('Z'.$i, $productos[0]->gettotalexpFasePrepaIniDtico())
         ->setCellValue('AA'.$i, $productos[0]->gettotalrecibiconcluPNR())
          ->setCellValue('AB'.$i, $productos[0]->gettotalrecibiconcluDTICO())
           ->setCellValue('AC'.$i, $productos[0]->gettotaldevIntruccionPNR())
            ->setCellValue('AD'.$i, $productos[0]->gettotaldevIntruccionDtico());
        
   
          $sum+=$productos[0]->gettotalExp60diasPNR();
          $sumd+=$productos[0]->gettotalExp60diasDTICO(); 
         
          
    }
               $j++;
          
          
        }
        
        
    
        
    }
        
        else
        
        {
        
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
		$tram = $dtc->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $munici,"tipoExpediente"=>"tramitacion", "unidad"=>"DITICO"));
         
                foreach ($tram as $ite) {
       
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
    
		
   
  $productos = $em->getRepository('UnoMainBundle:Tramitacion')->findByMunicipio($munici);
  
 
            if($productos){
         $contfila+=$productos[0]->gettotalExp60diasPNR();   
         $contfilad+=$productos[0]->gettotalExp60diasDTICO();  
            }
         
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('N'.$res, $contfila);     
            $contfila=0;
          $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('U'.$res, $contfilad);     
            $contfilad=0;  
       
                
    if($productos){
          $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('D'.$res, $productos[0]->getTotalEFPiniciodiaPNR())
               ->setCellValue('E'.$res, $productos[0]->getTotalEFPiniciodiaDTICO())
               ->setCellValue('F'.$res, $productos[0]->gettotalEFPcierrediaPNR())
                   ->setCellValue('G'.$res, $productos[0]->gettotalEFPcierrediaDtico())
            ->setCellValue('H'.$res, $productos[0]->gettotalExp60diasPNR())
               ->setCellValue('O'.$res, $productos[0]->gettotalExp60diasDTICO())     
           ->setCellValue('V'.$res, $productos[0]->getcantExpacusadosPNR())
                    ->setCellValue('W'.$res, $productos[0]->getcantExpacusadosDTICO())
             ->setCellValue('X'.$res, $productos[0]->gettotexpdientesinidia())
          ->setCellValue('Y'.$res, $productos[0]->gettotalexpFasePrepaIniPNR())
        ->setCellValue('Z'.$res, $productos[0]->gettotalexpFasePrepaIniDtico())
         ->setCellValue('AA'.$res, $productos[0]->gettotalrecibiconcluPNR())
          ->setCellValue('AB'.$res, $productos[0]->gettotalrecibiconcluDTICO())
           ->setCellValue('AC'.$res, $productos[0]->gettotaldevIntruccionPNR())
            ->setCellValue('AD'.$res, $productos[0]->gettotaldevIntruccionDtico());
        
   
          $sum+=$productos[0]->gettotalExp60diasPNR();
          $sumd+=$productos[0]->gettotalExp60diasDTICO(); 
         
          
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
     $phpExcelObject->getActiveSheet()->getStyle('S25:U25')->applyFromArray($borders);   
    
    
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
    


public function tramiconAction(Request $request)
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
        $phpExcelObject->getActiveSheet(0)->setTitle('Consolidado Despacho');
      
   $phpExcelObject->setActiveSheetIndex(0)->mergeCells('B3:H3');
      $phpExcelObject->setActiveSheetIndex(0)->mergeCells('B4:B5');
         $phpExcelObject->setActiveSheetIndex(0)->mergeCells('C4:H4');
            $phpExcelObject->setActiveSheetIndex(0)->mergeCells('D23:G23');
            
            $municipios=array('habvieja','centrohabana','plaza','cerro','playa','marianao','lisa','boyeros','arroyo','10Octubre','SanMiguel','Cotorro','Regla','Guanabacoa','H.Este','Divico') ;  
         $j=0;$TOTALPNR=0;$cont60=0;$cont90=0;$cont120=0;$cont150=0;$cont180=0;$sum=0;$contfila=0;$contfilaD=0;$sumD=0;$toti=0;
          $sum180=0;$sum120=0;$sum90=0;$sum150=0; $contnoven=0; 
            
            for($i=6;$i<=21;$i++){
         $em = $this->getDoctrine()->getManager();
		$trami = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$j],"tipoExpediente"=>"despacho"));
                
             
    foreach ($trami as $item) {
       
       
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
        
        
         
         if($cont90>0){
         $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('D'.$i, $cont90);
          $contfila+=$cont90;$sum90+=$cont90;$con90=0;
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
    
if($productos){
         $contfila+=$productos[0]->gettotalExpPendientes(); 
  $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('H22', $contnoven); 
   $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('D23', $contnoven); 
            }
         if($contfila>0)
      $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('C'.$i, $contfila);   
         $toti+=$contfila;
         $contfila=0;
         
            $j++; 
          }
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
           
            
$borders = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thin',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
$phpExcelObject->getActiveSheet(0)->getStyle('B4:H22')->applyFromArray($borders);
$phpExcelObject->getActiveSheet(0)->getStyle('D23:G23')->applyFromArray($borders);







 $phpExcelObject->setActiveSheetIndex(0)
         ->setCellValue('C4', 'PENDIENTE A DESPACHO')
         ->setCellValue('C5', 'TOTAL')
         ->setCellValue('D5', '91-120')
         ->setCellValue('E5', '121-150')
         ->setCellValue('F5', '151-180')
         ->setCellValue('G5', '+180')
         ->setCellValue('H5', 'TOTAL>90')
         ->setCellValue('B4', 'La Habana')
                ->setCellValue('B6', 'H.  Vieja')
                ->setCellValue('B7', 'C. Habana')
                ->setCellValue('B8', 'Plaza')
                ->setCellValue('B9', 'Marianao')
                 ->setCellValue('B10', 'Lisa')
                 ->setCellValue('B11', 'Boyeros')
            ->setCellValue('B12', 'Arroyo N.')
            ->setCellValue('B13', '10 de Oct.')
              
            ->setCellValue('B14', 'SMPadron')
                    ->setCellValue('B15', 'Cotorro')
                 ->setCellValue('B16', 'Regla')
            ->setCellValue('B17', 'Gbcoa')
            ->setCellValue('B18', 'H. Este')
              
            ->setCellValue('B19', 'DIVICO') 
 ->setCellValue('B20', 'Cerro')
            ->setCellValue('B21', 'Playa')
              
            ->setCellValue('B22', 'Total') ;
 
 
 
 
 
  $phpExcelObject->createSheet(1);

  $phpExcelObject->setActiveSheetIndex(1);
        $phpExcelObject->getActiveSheet(1)->setTitle('Consolidado Tramitacion');
      
   $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B3:H3');
      $phpExcelObject->setActiveSheetIndex(1)->mergeCells('B4:B5');
         $phpExcelObject->setActiveSheetIndex(1)->mergeCells('C4:H4');
            $phpExcelObject->setActiveSheetIndex(1)->mergeCells('D23:G23');
       
            
            
$borders = array(
      'borders' => array(
        'allborders' => array(
          'style' => 'thin',
          'color' => array('argb' => 'FF000000'),
        )
      ),
    );
$phpExcelObject->getActiveSheet(1)->getStyle('B4:H22')->applyFromArray($borders);
$phpExcelObject->getActiveSheet(1)->getStyle('D23:G23')->applyFromArray($borders);




  $municipios=array('habvieja','centrohabana','plaza','cerro','playa','marianao','lisa','boyeros','arroyo','10Octubre','SanMiguel','Cotorro','Regla','Guanabacoa','H.Este','Divico') ;  
         $colume=0;$j=0;$cont60t=0;$cont90t=0;$cont120t=0;$cont150t=0;$cont180t=0;$sumt=0;$contfilat=0;$contfilaDt=0;$sumDt=0;$totil=0;
          $sum180t=0;$sum120t=0;$sum90t=0;$sum150t=0; $contnovent=0; $tramitacion=0;
            
            for($i=6;$i<=21;$i++){
         $em = $this->getDoctrine()->getManager();
		$trami = $em->getRepository('UnoMainBundle:Expediente')->findBy(array('municipio'=> $municipios[$j],"tipoExpediente"=>"tramitacion"));
                
             
    foreach ($trami as $item) {
       
       
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
          
           }
       
         if($cont90t>0){
         $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('D'.$i, $cont90t);
          $contfilat+=$cont90t;$sum90t+=$cont90t;$con90t=0;
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

         
          $mun = $em->getRepository('UnoMainBundle:Tramitacion')->findBymunicipio($municipios[$j]);
      
        if($mun){
           $tramitacion=$contfilat+$mun[0]->gettotalExp60diasPNR()+$mun[0]->gettotalExp60diasDTICO();
           $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('C'.$i, $tramitacion);  
           $totil+=$tramitacion;
           $tramitacion=0;
        }
        else{
          $phpExcelObject->setActiveSheetIndex(1)
                ->setCellValue('C'.$i, $tramitacion);     
        $totil+=$tramitacion;
        $tramitacion=0;
        
        }        
         
         
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
            ->setCellValue('D23', $colume);    
            

 $phpExcelObject->setActiveSheetIndex(1)
         ->setCellValue('C4', 'PENDIENTE A TRAMITACION')
         ->setCellValue('C5', 'TOTAL')
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
            ->setCellValue('B12', 'Boyeros')
            ->setCellValue('B13', 'Lisa')
              
            ->setCellValue('B14', 'Arroyo N')
                    ->setCellValue('B15', '10 Octubre')
                 ->setCellValue('B16', 'SanMiguel')
            ->setCellValue('B17', 'Cotorro')
            ->setCellValue('B18', 'Regla')
              
            ->setCellValue('B19', 'Guanabacoa') 
 ->setCellValue('B20', 'Hab.Este')
            ->setCellValue('B21', 'Divico')
              
            ->setCellValue('B22', 'Total') ;


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



}}