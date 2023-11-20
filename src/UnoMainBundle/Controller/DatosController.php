<?php

namespace UnoMainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use UnoMainBundle\Entity\Expediente;

use UnoMainBundle\Entity\User;
use UnoMainBundle\Form\ExpedienteType;
use UnoMainBundle\Util\Util;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use UnoMainBundle\Entity\Traza;
use UnoMainBundle\Entity\Cambio;
 

class DatosController extends Controller
{
  public function editguardarAction(Request $request, $id){
		 $cont=0;
                $em = $this->getDoctrine()->getManager();
		$producto = $em->getRepository('UnoMainBundle:Expediente')->findOneById($id); 
		
		$error[1]=" ";
                $error[2]=" ";
                 $error[10]=" ";
                $error[3]="";
                 $error[4]="";
                  $error[5]="";
                   $error[6]="";
                   $error[7]="";
                         $error[8]="";
                            $error[9]="";
                                $error[11]="";
             
       $a=  date_create($producto->getFechainicial());
                    $b=  date_format($a, 'y');
                   $x= str_ireplace('/'.$b,' ',$producto->getNumExpediente());
                   $producto->setNumExpediente($x);
$lastUsername=$this->get('security.context')->getToken()->getUser()->getUsername();


 $role=$this->get('security.context')->getToken()->getUser()->getRole();

    
		$form = $this->createForm(new ExpedienteType(), $producto);
             
                $form->handleRequest($request);
		
               if ($role=="ROLE_USER"){
              
         $producto->setMunicipio($lastUsername);      
              }
       
             
                
            
              
             
             if (($producto->getSIS() && $producto->getDEVTTP())or ($producto->getDEVFISCAL()&& $producto->getSIS()) or ($producto->getDEVTTP()&&  $producto->getDEVFISCAL() ))
     { $error[1]="Usted solamente debe escoger uno de los tres elementos anteriores";
     $cont++;
       }  
       
          
        
          
      
      if($form->isValid()==true && !$producto->getFechainicial() ){
           $error[3]="Inserte fecha";
           $cont++;
       }

        if($form->isValid()==true && $producto->getTipoExpediente()=='u'){
           $error[2]="Seleccione un Tipo de Expediente";
           $cont++;
       }
       if($form->isValid()==true && $producto->getTipoExpediente()=="Despacho" && !$producto->getFechaentrega()){
           $error[4]="Inserte la fecha de elevado";
           $cont++;
           
       }
       if($form->isValid()==true && !$producto->getPronostico()){
           $error[5]="Inserte fecha de pronostico";
           $cont++;
           
       }
       if($form->isValid()==true && $producto->getDelito()=='0' ){
           $error[6]="Seleccione un delito";
           $cont++;
           
       }
       if($form->isValid()==true && $producto->getMunicipio()=="po" ){
           $error[7]="Seleccione un municipio";
           $cont++;
           
       }
       if($form->isValid()==true && $producto->getUnidad()=="po" ){
           $error[8]="Seleccione Unidad";
           $cont++;
           
       }
        if($form->isValid()==true && $producto->getTipoExpediente()=="Tramitacion" && $producto->getFechaentrega() ){
        $producto->setFechaentrega(NULL);
       
      }
       if(  Util::mayor($producto->getFechaentrega(),$producto->getFechainicial()) or Util::mayor($producto->getPronostico(),$producto->getFechainicial()) or Util::mayor($producto->getDEVFISCAL(),$producto->getFechainicial())or Util::mayor($producto->getSIS(),$producto->getFechainicial())or Util::mayor($producto->getDEVTTP(),$producto->getFechainicial())
         or  Util::mayor($producto->getPronostico(),$producto->getFechaentrega()) or  Util::mayor($producto->getPronostico(),$producto->getDEVFISCAL()) or  Util::mayor($producto->getPronostico(),$producto->getSIS()) or  Util::mayor($producto->getPronostico(),$producto->getDEVTTP())    )  {
          $error[9]="Revise la logica de las fechas insertadas";
            
           $cont++;
        }
        
              if(Util::mayor($producto->getPronostico(),date('Y-m-d'))){
            $error[11]="El pronostico no puede ser menor que la fecha actual";
            
           $cont++;  
             
         }
        
        
        $em = $this->getDoctrine()->getManager();

          $busqueda= $em->getRepository('UnoMainBundle:Expediente')->findByNumExpediente($producto->getNumExpediente());
         if($form->isValid()==true && $producto->getNumExpediente()==$busqueda ){
           $error[10]="El expediente".$producto->getNumExpediente()."ya se encuentra insertado en el sistema";
           $cont++;
           
       }
        
       
		if ($form->isValid() && $cont==0) {
                    
                    
                    if($producto->getTipoExpediente()=='Tramitación'){
                        
                        $producto->setFechaentrega(NULL);
                    }
                    
                    
                    
                      $a=  date_create($producto->getFechainicial());
          
date_create($producto->getFechainicial());
                    $b=  date_format($a, 'y');            
                $producto->setNumExpediente($producto->getNumExpediente().'/'.$b);     
                    
                    
                    
                    $em = $this->getDoctrine()->getManager();
			$em->persist($producto);
			$em->flush();
                 if($producto->getTipoExpediente()=='Tramitación')  { 
                      $cantidad = $em->getRepository('UnoMainBundle:Expediente')->findAll();   
                       $fecha= date('Y-m-d');
         $repository = $em->getRepository('UnoMainBundle:Traza'); 
        $query = $repository->createQueryBuilder('p') ->where('p.fecha =:expediente and p.municipio=:municipio')->setParameter('expediente',$fecha)->setParameter('municipio',$producto->getMunicipio()) ->getQuery();
     $trazi = $query->getResult();  
     
     if($trazi){
         
         
          $repository = $em->getRepository('UnoMainBundle:Expediente'); 
        $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni')->setParameter('expediente','Tramitación')->setParameter('municipio',$producto->getMunicipio()) ->setParameter('uni','PNR')->getQuery();
     $pnr = $query->getResult(); 
       $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni')->setParameter('expediente','Tramitación')->setParameter('municipio',$producto->getMunicipio()) ->setParameter('uni','DTICO')->getQuery();
     $dtico = $query->getResult(); 
        
      $trazi[0]->setTpnr(count($pnr));
                 $trazi[0]->setTdtico(count($dtico));   
          $em->persist($trazi[0]);
			$em->flush();
         
         
     }else{
          $repository = $em->getRepository('UnoMainBundle:Expediente'); 
        $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni')->setParameter('expediente','Tramitación')->setParameter('municipio',$producto->getMunicipio()) ->setParameter('uni','PNR')->getQuery();
     $pnr = $query->getResult(); 
       $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni')->setParameter('expediente','Tramitación')->setParameter('municipio',$producto->getMunicipio()) ->setParameter('uni','DTICO')->getQuery();
     $dtico = $query->getResult(); 
     
      $trazi = new Traza();  
        $trazi->setCantexpedientes( count($cantidad));
              $trazi->setFecha($fecha);
               $trazi->setMunicipio($producto->getMunicipio());
              $trazi->setTpnr(count($pnr));
                 $trazi->setTdtico(count($dtico));  
         $em->persist($trazi);
			$em->flush();
         
         
         
         
         
         
                 }}
                 
                 
                 
                    $date=  date('Y-m-d');
             $repository = $em->getRepository('UnoMainBundle:Cambio'); 
        $query = $repository->createQueryBuilder('p') ->where('p.dia =:expediente and p.expediente=:municipio')->setParameter('expediente',$date)->setParameter('municipio',$producto->getId()) ->getQuery();
     $exp = $query->getResult();    
                    
              if($exp[0]->getEstadoinicial()!=$producto->getTipoExpediente())
                  $exp[0]->setPaso($producto->getTipoExpediente());
                    else
                   $exp[0]->setPaso(NULL);
                    
                 $exp[0]->setUnidad($producto->getUnidad());        
         $em->persist($exp[0]);
			$em->flush();  
                    
                    
			
                         
			if($producto->getTipoExpediente()=="Tramitación"){
                            $successMessage = $this->get('translator')->trans('El expediente ha sido modificado satisfactoriamente');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_homepage'));
                        
                        } else
                             $successMessage = $this->get('translator')->trans('El expediente ha sido modificado satisfactoriamente');
                $this->addFlash('mensaje', $successMessage);
                     $response = $this->forward('UnoMainBundle:Default:despa', array(
        
        'index' => '1'
    ));

                
    return $response;   
		}
                
             
                
             if(!$form->isSubmitted()  ){
                 $date=  date('Y-m-d');
             $repository = $em->getRepository('UnoMainBundle:Cambio'); 
        $query = $repository->createQueryBuilder('p') ->where('p.dia =:expediente and p.expediente=:municipio')->setParameter('expediente',$date)->setParameter('municipio',$producto->getId()) ->getQuery();
     $exp = $query->getResult();  
		
     if(!$exp){
     $instancia= new Cambio();
     $instancia->setDia($date);
     $instancia->setExpediente($producto->getId());
     $instancia->setEstadoinicial($producto->getTipoExpediente());
     $instancia->setMunicipio($producto->getMunicipio());
     
        $em->persist($instancia);
			$em->flush();  
         
         
         
         
     }     
     
     
     
     
     
             }
             
                
            
		$respuesta= $this->render("UnoMainBundle:Default:expediente.html.twig", array(
				"form"=>$form->createView(),"error"=>$error));
                $respuesta->setPublic();
                return $respuesta;
		
	}
        
        public function vexpedienteAction( $id){
		$em = $this->getDoctrine()->getManager();
		$form = $em->getRepository('UnoMainBundle:Expediente')->findOneById($id); 
	return $this->render("UnoMainBundle:Default:vistaexpediente.html.twig", array(
				"form"=>$form));
		
	}
        public function vfuerzaAction( ){
            $lastUsername=$this->get('security.context')->getToken()->getUser()->getUsername();
		$em = $this->getDoctrine()->getManager();
              $role=$this->get('security.context')->getToken()->getUser()->getRole();  
		$form = $em->getRepository('UnoMainBundle:Fuerza')->findOneBymunicipio($lastUsername); 
                 if(!$form){
                     if($role=="ROLE_USER"){
                  $successMessage = $this->get('translator')->trans('No se encuentra insertado los datos correspondientes a la Fuerza laboral');
                 $this->addFlash('mensaje', $successMessage);}
			return $this->redirect($this->generateUrl('uno_main_homepage'));    
                  }
	return $this->render("UnoMainBundle:Default:vistafuerza.html.twig", array(
				"form"=>$form));
		
	}
         public function fuerzaadminAction(Request $request,$municipio ){
         $sesion = $request->getSession();        
         $sesion ->set('municipio', $municipio); 
		$em = $this->getDoctrine()->getManager();
                
		$form = $em->getRepository('UnoMainBundle:Fuerza')->findOneBymunicipio($municipio); 
                 if(!$form){
                
			return $this->redirect($this->generateUrl('uno_main_fuerza')); 
                  }
	return $this->render("UnoMainBundle:Default:vistafuerza.html.twig", array(
				"form"=>$form));
		
	}
        
        public function despaadminAction(Request $request,$municipio ){
         $sesion = $request->getSession();        
         $sesion ->set('municipio', $municipio); 
		$em = $this->getDoctrine()->getManager();
                
		$form = $em->getRepository('UnoMainBundle:Despacho')->findOneBymunicipio($municipio); 
                 if(!$form){
                
			return $this->redirect($this->generateUrl('uno_main_des')); 
                  }
	return $this->render("UnoMainBundle:Default:vistadespacho.html.twig", array(
				"form"=>$form));
		
	}
       public function tramiadminAction(Request $request,$municipio ){
         $sesion = $request->getSession();        
         $sesion ->set('municipio', $municipio); 
		$em = $this->getDoctrine()->getManager();
                
		$form = $em->getRepository('UnoMainBundle:Tramitacion')->findOneBymunicipio($municipio); 
                 if(!$form){
                
			return $this->redirect($this->generateUrl('uno_main_trami')); 
                  }
	return $this->render("UnoMainBundle:Default:vistatramitacion.html.twig", array(
				"form"=>$form));
		
	}  
        
        
        
        
        
        
        public function vdesAction( ){
               $role=$this->get('security.context')->getToken()->getUser()->getRole();  
             
              $lastUsername=$this->get('security.context')->getToken()->getUser()->getUsername();
		$em = $this->getDoctrine()->getManager();
		$form = $em->getRepository('UnoMainBundle:Despacho')->findOneBymunicipio($lastUsername); 
                 if(!$form){
                      if($role=="ROLE_USER"){
                  $successMessage = $this->get('translator')->trans('No se encuentra insertado el despacho ');
                      $this->addFlash('mensaje', $successMessage);}
			return $this->redirect($this->generateUrl('uno_main_homepage'));    
                  }
	return $this->render("UnoMainBundle:Default:vistadespacho.html.twig", array(
				"form"=>$form));
		
	}
        public function vtramiAction( ){
            
              $lastUsername=$this->get('security.context')->getToken()->getUser()->getUsername();
		$em = $this->getDoctrine()->getManager();
		$form = $em->getRepository('UnoMainBundle:Tramitacion')->findOneBymunicipio($lastUsername); 
                
                  if(!$form){
                  $successMessage = $this->get('translator')->trans('No se encuentra insertada la tramitacion ');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_homepage'));    
                  }
	return $this->render("UnoMainBundle:Default:vistatramitacion.html.twig", array(
				"form"=>$form));
		
	}
        
    
    public function guardarAction(Request $request){
	 $cont=0;	
		$producto = new Expediente();
		$error[1]=" ";
                $error[2]=" ";
                $error[3]="";
                 $error[4]="";
                  $error[5]="";
                   $error[6]="";
                   $error[7]="";
                         $error[8]="";
                            $error[9]="";
                             $error[10]="";
                              $error[11]="";
                 
                 
$lastUsername=$this->get('security.context')->getToken()->getUser()->getUsername();


 $role=$this->get('security.context')->getToken()->getUser()->getRole();

    
		$form = $this->createForm(new ExpedienteType(), $producto);
             
                $form->handleRequest($request);
		
               if ($role=="ROLE_USER"){
              
         $producto->setMunicipio($lastUsername);      
              }
       
             
                
              
              
             
             if (($producto->getSIS() && $producto->getDEVTTP())or ($producto->getDEVFISCAL()&& $producto->getSIS()) or ($producto->getDEVTTP()&&  $producto->getDEVFISCAL() ))
     { $error[1]="Usted solamente debe escoger uno de los tres elementos anteriores";
     $cont++;
       }  
       
          
    if(Util::mayor($producto->getPronostico(),date('Y-m-d'))){
            $error[11]="El pronostico no puede ser menor que la fecha actual";
            
           $cont++;  
             
         }
             
   
      
      if($form->isValid()==true && !$producto->getFechainicial() ){
           $error[3]="Inserte fecha";
           $cont++;
       }

        if($form->isValid()==true && $producto->getTipoExpediente()=='u'){
           $error[2]="Seleccione un Tipo de Expediente";
           $cont++;
       }
       if($form->isValid()==true && $producto->getTipoExpediente()=="Despacho" && !$producto->getFechaentrega()){
           $error[4]="Inserte la fecha de elevado";
           $cont++;
           
       }
       if($form->isValid()==true && !$producto->getPronostico()){
           $error[5]="Inserte fecha de pronostico";
           $cont++;
           
       }
       if($form->isValid()==true && $producto->getDelito()=='0' ){
           $error[6]="Seleccione un delito";
           $cont++;
           
       }
       if($form->isValid()==true && $producto->getMunicipio()=="po" ){
           $error[7]="Seleccione un municipio";
           $cont++;
           
       }
       if($form->isValid()==true && $producto->getUnidad()=="po" ){
           $error[8]="Seleccione Unidad";
           $cont++;
           
       }
        if($form->isValid()==true && $producto->getTipoExpediente()=="Tramitacion" && $producto->getFechaentrega() ){
        $producto->setFechaentrega(NULL);
       
      }
       if(  Util::mayor($producto->getFechaentrega(),$producto->getFechainicial()) or Util::mayor($producto->getPronostico(),$producto->getFechainicial()) or Util::mayor($producto->getDEVFISCAL(),$producto->getFechainicial())or Util::mayor($producto->getSIS(),$producto->getFechainicial())or Util::mayor($producto->getDEVTTP(),$producto->getFechainicial())
         or  Util::mayor($producto->getPronostico(),$producto->getFechaentrega()) or  Util::mayor($producto->getPronostico(),$producto->getDEVFISCAL()) or  Util::mayor($producto->getPronostico(),$producto->getSIS()) or  Util::mayor($producto->getPronostico(),$producto->getDEVTTP())    )  {
          $error[9]="Revise la logica de las fechas insertadas";
            
           $cont++;
        }
         $em = $this->getDoctrine()->getManager();$a=  date_create($producto->getFechainicial());
                    $b=  date_format($a, 'y');
                    
                   $producto->setNumExpediente($producto->getNumExpediente().'/'.$b);
          $busqueda= $em->getRepository('UnoMainBundle:Expediente')->findOneBynumExpediente($producto->getNumExpediente());
         
         if($form->isValid()==true && $busqueda){
           $error[10]="El expediente ".$producto->getNumExpediente()." ya se encuentra insertado en el sistema";
           $cont++;
           
       }
        
        
        
        
       
		if ($form->isValid() && $cont==0) {
                    if($producto->getTipoExpediente()=='Tramitación'){
                        
                        $producto->setFechaentrega(NULL);
                    }
                    $producto->setInsertn(date('Y-m-d'));
                    
                      $foobar = new Util;  

                   $foobar->trazas($em,$producto);
                          
			$em = $this->getDoctrine()->getManager();
			$em->persist($producto);
			$em->flush();
              
			if($producto->getTipoExpediente()=="Despacho"){
                           
                        
                            $successMessage = $this->get('translator')->trans('El Expediente ha sido creado satisfactoriamente');
                $this->addFlash('mensaje', $successMessage);
                
                 $response = $this->forward('UnoMainBundle:Default:despa', array(
        
        'index' => '1'
    ));

                
    return $response;
                
               
    
		}else
                {
                     $successMessage = $this->get('translator')->trans('El Expediente ha sido creado satisfactoriamente');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_homepage'));
                }
             
                }
		$respuesta= $this->render("UnoMainBundle:Default:expediente.html.twig", array(
				"form"=>$form->createView(),"error"=>$error,));
		$respuesta->setMaxAge(500*60);

                return $respuesta;
		
    }
        
        
         public function tramiAction(Request $request){
             $orli="";
             $role=$this->get('security.context')->getToken()->getUser()->getRole(); 
             if($role=="ROLE_USER"){
             $lastUsername=$this->get('security.context')->getToken()->getUser()->getUsername();
                $em = $this->getDoctrine()->getManager();
               	$fo = $em->getRepository('UnoMainBundle:Tramitacion')->findOneBymunicipio($lastUsername);  
                if($fo){
                    $successMessage = $this->get('translator')->trans('Ya existe insertado los datos sobre la Tramitacion');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_homepage'));
                }}
                
		$producto = new \UnoMainBundle\Entity\Tramitacion();
		
		$form = $this->createForm(new \UnoMainBundle\Form\TramitacionType, $producto);
		
		$form->handleRequest($request);
	 $role=$this->get('security.context')->getToken()->getUser()->getRole(); 	
		 if ($role=="ROLE_ADMIN"){
                    $sesion = $request->getSession();  
             $producto->setMunicipio( $sesion ->get('municipio')); 
              $orli=$sesion ->get('municipio');
              }	else{
                  $producto->setMunicipio($lastUsername); 
              }

		
                
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($producto);
			$em->flush();
			 $successMessage = $this->get('translator')->trans('El formulario Tramitacion se ha insertado correctamente');
                $this->addFlash('mensaje', $successMessage);
                       
                return $this->redirect($this->generateUrl('uno_main_homepage'));
                
                
                }
         	return $this->render("UnoMainBundle:Default:tramitacion.html.twig", array(
				"form"=>$form->createView(),'orli'=>$orli
		));
         }
          
         
               public function tramieditAction(Request $request,$id){
             
             $em = $this->getDoctrine()->getManager();
		$producto = $em->getRepository('UnoMainBundle:Tramitacion')->findOneById($id); 
		
		
		
		
		$form = $this->createForm(new \UnoMainBundle\Form\TramitacionType, $producto);
		
		$form->handleRequest($request);
		
		
		$orli=$producto->getMunicipio();
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($producto);
			$em->flush();
			$successMessage = $this->get('translator')->trans('La Tramitacion ha sido modificada correctamente');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_homepage'));
		}	
                
               return $this->render("UnoMainBundle:Default:tramitacion.html.twig", array(
				"form"=>$form->createView(),'orli'=>$orli
		));
		
	}
         public function fuerzaeditAction(Request $request,$id){
             $error[0]="";
             $error[1]="";
             $cont=0;
             $em = $this->getDoctrine()->getManager();
		$producto = $em->getRepository('UnoMainBundle:Fuerza')->findOneById($id); 
		
		
		$orli=$producto->getMunicipio(); 
		
		$form = $this->createForm(new \UnoMainBundle\Form\FuerzaType, $producto);
		
		$form->handleRequest($request);
		
		$sumita=$producto->getDedControl()+$producto->getDedDespacho()+$producto->getDedJuicios()+$producto->getOtrasActividades();
    $sumate=$producto->getSumario()+$producto->getOrdinario()+$producto->getTribProvOrd()+$producto->getTribProvApel();

             if($form->isValid()==true && $producto->getTotalEspecialidad()!=$sumita ){
               $cont++;  
               $error[0]="Existe incoherencia en la sumatoria de fuerzas"  ;
             }   
             if($form->isValid()==true && $producto->getTotaljuicios()!=$sumate){
               $cont++;  
               $error[1]="Existe incoherencia en la sumatoria de los juicios realizados"  ;
             }   
		
		if ($form->isValid() && $cont==0) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($producto);
			$em->flush();
			$successMessage = $this->get('translator')->trans('La Fuerza laboral ha sido modificada correctamente');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_vfuerza'));
		}	
                
               return $this->render("UnoMainBundle:Default:fuerza.html.twig", array(
				"form"=>$form->createView(),'orli'=>$orli,'error'=>$error
		));
		
	}
        
         public function despachoeditAction(Request $request,$id){
             $c=0;$error='';$r='';
             $em = $this->getDoctrine()->getManager();
		$producto = $em->getRepository('UnoMainBundle:Despacho')->findOneById($id); 
		
		$orli=$producto->getMunicipio();
		
		
		$form = $this->createForm(new \UnoMainBundle\Form\DespachoType, $producto);
		
		$form->handleRequest($request);
		
		 if($producto->getDespachadosDia()!=($producto->getConcluciones()+$producto->getSobreseimientos()+$producto->getInhibitorias()+$producto->getochotres()+$producto->getOtros())){
                  
                  $error='Existe incoherencia en la sumatoria de los tipos de despacho';
                  $c++;
              }
              
              if($producto->getTermmas10dias()>$producto->getDespachadosDia()){
               $r='En Termino mas de 10 dias no puede ser mayor que los despachados en el dia';   
                $c++;  
              }
		
		if ($form->isValid() and $c==0) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($producto);
			$em->flush();
			$successMessage = $this->get('translator')->trans('El formulario Despacho ha sido modificado correctamente');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_vdes'));
		}	
                
               return $this->render("UnoMainBundle:Default:despacho.html.twig", array(
				"form"=>$form->createView(),'orli'=>$orli,'error'=>$error,'r'=>$r
		));
		
	}
         public function deletetramiAction($id){
		$em = $this->getDoctrine()->getManager();
		$producto = $em->getRepository('UnoMainBundle:Tramitacion')->find($id);
            
	
		$em->remove($producto);
		$em->flush();
		
 $successMessage = $this->get('translator')->trans('La Tramitacion hasta 60 dias ha sido eliminada del sistema');
                $this->addFlash('mensaje', $successMessage);
                     return $this->redirect($this->generateUrl('uno_main_homepage'));  
}   

 public function deletefuerzaAction($id){
		$em = $this->getDoctrine()->getManager();
		$producto = $em->getRepository('UnoMainBundle:Fuerza')->find($id);
            
	
		$em->remove($producto);
		$em->flush();
		
 $successMessage = $this->get('translator')->trans('La Fuerza Laboral ha sido eliminada del sistema');
                $this->addFlash('mensaje', $successMessage);
                     return $this->redirect($this->generateUrl('uno_main_homepage'));  
}   
public function deletedespachoAction($id){
		$em = $this->getDoctrine()->getManager();
		$producto = $em->getRepository('UnoMainBundle:Despacho')->find($id);
            
	
		$em->remove($producto);
		$em->flush();
		
 $successMessage = $this->get('translator')->trans('El formulario Despacho ha sido eliminado del sistema');
                $this->addFlash('mensaje', $successMessage);
                     return $this->redirect($this->generateUrl('uno_main_homepage'));  
}   

               public function desAction(Request $request){
                   $orli="";
                   $error='';
                  $r='';
                   $role=$this->get('security.context')->getToken()->getUser()->getRole(); 
             if($role=="ROLE_USER"){
		$lastUsername=$this->get('security.context')->getToken()->getUser()->getUsername();
                $em = $this->getDoctrine()->getManager();
               	$fo = $em->getRepository('UnoMainBundle:Despacho')->findOneBymunicipio($lastUsername);  
                if($fo){
                    $successMessage = $this->get('translator')->trans('Ya existe insertado los datos sobre Despacho');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_homepage'));
             }
             
                }
               $error='';
               $c=0;
		$producto = new \UnoMainBundle\Entity\Despacho();
		
                
		$form = $this->createForm(new \UnoMainBundle\Form\DespachoType, $producto);
		
		$form->handleRequest($request);
		
		
		
			 if ($role=="ROLE_ADMIN"){
                    $sesion = $request->getSession();  
             $producto->setMunicipio( $sesion ->get('municipio')); 
              $orli=$sesion ->get('municipio');
              }	else{
                  $producto->setMunicipio($lastUsername); 
              }
              
              if($producto->getDespachadosDia()!=($producto->getConcluciones()+$producto->getSobreseimientos()+$producto->getInhibitorias()+$producto->getochotres()+$producto->getOtros())){
                  
                  $error='Existe incoherencia en la sumatoria de los tipos de despacho';
                  $c++;
              }
              
               if($producto->getTermmas10dias()>$producto->getDespachadosDia()){
               $r='En Termino mas de 10 dias no puede ser mayor que los despachados en el dia';   
                $c++;  
              }
		if ($form->isValid() and $c==0) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($producto);
			$em->flush();
			 $successMessage = $this->get('translator')->trans('Se inserto el formulario Despacho correctamente');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_homepage'));
		}	
		
		return $this->render("UnoMainBundle:Default:despacho.html.twig", array(
				"form"=>$form->createView(),'orli'=>$orli,'error'=>$error,'r'=>$r
		));
		
               }
        public function fuerzaAction(Request $request){
            $orli="";
             $error[0]="";
             $error[1]="";
             $cont=0;
            $role=$this->get('security.context')->getToken()->getUser()->getRole(); 
             if($role=="ROLE_USER"){
             $usuario=$this->get('security.context')->getToken()->getUser()->getRole();
		$lastUsername=$this->get('security.context')->getToken()->getUser()->getUsername();
                $em = $this->getDoctrine()->getManager();
               	$fo = $em->getRepository('UnoMainBundle:Fuerza')->findOneBymunicipio($lastUsername);  
                if($fo){
                    $successMessage = $this->get('translator')->trans('Ya existe insertado los datos sobre la Fuerza Laboral');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_homepage'));
                }
             }
		$producto = new \UnoMainBundle\Entity\Fuerza();
		
		$form = $this->createForm(new \UnoMainBundle\Form\FuerzaType, $producto);
		
		$form->handleRequest($request);
		
		
                 if ($role=="ROLE_ADMIN"){
                    $sesion = $request->getSession();  
             $producto->setMunicipio( $sesion ->get('municipio')); 
              $orli=$sesion ->get('municipio');
              }	else{
                  $producto->setMunicipio($lastUsername); 
              }
             
              
              $sumita=$producto->getDedControl()+$producto->getDedDespacho()+$producto->getDedJuicios()+$producto->getOtrasActividades();
    $sumate=$producto->getSumario()+$producto->getOrdinario()+$producto->getTribProvOrd()+$producto->getTribProvApel();
             if($form->isValid()==true && $producto->getTotalEspecialidad()!=$sumita ){
               $cont++;  
               $error[0]="Existe incoherencia en la sumatoria de fuerzas"  ;
             }   
             if($form->isValid()==true && $producto->getTotaljuicios()!=$sumate){
               $cont++;  
               $error[1]="Existe incoherencia en la sumatoria de los juicios realizados"  ;
             }   
		if ($form->isValid() && $cont==0) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($producto);
			$em->flush();
			$successMessage = $this->get('translator')->trans('El formulario Fuerza ha sido correctamente insertado');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_homepage'));
		}	
		
		return $this->render("UnoMainBundle:Default:fuerza.html.twig", array(
				"form"=>$form->createView(),'orli'=>$orli,'error'=>$error
                ));
               
        
	} 
        
   
        
        public function deleteAction($id){
		$em = $this->getDoctrine()->getManager();
		$producto = $em->getRepository('UnoMainBundle:Expediente')->find($id);
                $tu=$producto->getTipoExpediente();
                $uni=$producto->getUnidad();
                $muni=$producto->getMunicipio();
                $upd=$producto-> getInsertn();
                
		if (!$producto) {
			throw $this->createNotFoundException(
					'No se ha encontrado el producto para la id '.$id
			);
		}
		$em->remove($producto);
		$em->flush();
                  $hoy= date('Y-m-d');
                 $repository = $em->getRepository('UnoMainBundle:Cambio'); 
        $query = $repository->createQueryBuilder('p') ->where('p.expediente =:expedien and p.dia=:municipio')->setParameter('expedien',$id)->setParameter('municipio',$hoy) ->getQuery();
     $cam= $query->getResult();  
     
     if($cam){
     $em->remove($cam[0]);
		$em->flush();    
         
     }
               
		if($tu=="Tramitación"){
                    
                  
                    
                     $repository = $em->getRepository('UnoMainBundle:Traza'); 
        $query = $repository->createQueryBuilder('p') ->where('p.fecha =:expediente and p.municipio=:municipio')->setParameter('expediente',$hoy)->setParameter('municipio',$muni) ->getQuery();
     $campo = $query->getResult();  
     
     
                    
                    if($campo){
                        
                        if($uni=='PNR'){
                            if($campo[0]->getTpnr()>0)
                       $campo[0]->setTpnr($campo[0]->getTpnr()-1);  
                         
                            
                            
                            if($upd==$hoy)
                           $campo[0]-> setInipnr($campo[0]-> getInipnr()-1);         
                            
                        }
                        else{
                           if($campo[0]->getTdtico()>0)   
                      $campo[0]->setTdtico($campo[0]->getTdtico()-1);  
                           
                           
                            if($upd==$hoy)
                           $campo[0]-> setInidtico($campo[0]-> getInidtico()-1);
                        }
                        	$em = $this->getDoctrine()->getManager();
                     $em->persist($campo[0]);
			$em->flush();   
                        
                    }else
                    {
                         $repository = $em->getRepository('UnoMainBundle:Expediente'); 
        $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni')->setParameter('expediente','Tramitación')->setParameter('municipio',$muni) ->setParameter('uni','PNR')->getQuery();
     $pnr = $query->getResult(); 
       $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni')->setParameter('expediente','Tramitación')->setParameter('municipio',$muni) ->setParameter('uni','DTICO')->getQuery();
     $dtico = $query->getResult(); 
           $cantidad = $em->getRepository('UnoMainBundle:Expediente')->findAll();  
         $producto = new Traza();  
              $producto->setFecha($hoy);
               $producto->setMunicipio($muni);
             
          $producto->setCantexpedientes( count($cantidad));
           $producto->setTpnr(count($pnr));
             $producto->setTdtico( count($dtico)); 
              $em->persist($producto);
			$em->flush(); 
                    }
                    
                    
                    
                    
                    
                    
                    
                    
                     $successMessage = $this->get('translator')->trans('El Expediente ha sido eliminado del sistema');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_homepage'));
                }  else
                             $successMessage = $this->get('translator')->trans('El Expediente ha sido eliminado del sistema');
                $this->addFlash('mensaje', $successMessage);
                   
                   $response = $this->forward('UnoMainBundle:Default:despa', array(
        
        'index' => '1'
    ));

                
    return $response;
}

 public function deleteuAction($id){
		$em = $this->getDoctrine()->getManager();
		$producto = $em->getRepository('UnoMainBundle:User')->find($id);
               
		
		$em->remove($producto);
		$em->flush();
               
	
                     $successMessage = $this->get('translator')->trans('El Usuario ha sido eliminado del sistema');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_usuarios'));
               
}   }