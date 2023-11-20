<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace UnoMainBundle\Util;
use UnoMainBundle\Entity\Traza;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class Util extends Controller
{
    
     public function dias($param) {
          $fecha_a= date("Y-m-d");
         
          
$dias = (strtotime($fecha_a)-strtotime($param))/86400;
$dias = abs($dias);
$dias = floor($dias);
   return   $dias;   
    } 
    
    public function mayor($fecha,$fecha_actual){
        if($fecha && $fecha_actual){
        $a= date_create($fecha);
        $a1= date_format($a, "d-m-Y");
        $b= date_create($fecha_actual);
        $b1= date_format($b, "d-m-Y"); 
if(strtotime($b1)>strtotime($a1)){
        return TRUE;
        }}else{
       return FALSE;
}
    
    
    }
    
    public function segmento($array,$paginado){
      $productos=NULL;  
         $j=0;
     for($i=$paginado*15-15;$i<$paginado*15;$i++){
         if( isset($array[$i]))
      $productos[$j]=$array[$i];   
         $j++;
     }
        
     return $productos;   
    }
    



public static function trazas($em,$arreglo){
    
     //$em = $this->getDoctrine()->getManager();
        $fecha= date('Y-m-d');
         $repository = $em->getRepository('UnoMainBundle:Traza'); 
        $query = $repository->createQueryBuilder('p') ->where('p.fecha =:expediente and p.municipio=:municipio')->setParameter('expediente',$fecha)->setParameter('municipio',$arreglo->getMunicipio()) ->getQuery();
     $producto = $query->getResult();  
        
          $cantidad = $em->getRepository('UnoMainBundle:Expediente')->findAll();   
       if($producto){
           
      if($cantidad!=$producto[0]->getCantexpedientes())
          $producto[0]->setCantexpedientes(count($cantidad));
			
            if($arreglo->getTipoExpediente()!='Despacho') {
                if($arreglo->getUnidad()=='PNR'){
                
              $c=1;
              $c+=$producto[0]->getTpnr();
                $producto[0]->setTpnr($c);
                 $producto[0]->setInipnr($producto[0]->getInipnr()+1);
                
                 
                
                }
                else{
                    
               $c=1;
              $c+=$producto[0]->getTdtico();
                $producto[0]->setTdtico($c);      
                 $producto[0]->setInidtico($producto[0]->getInidtico()+1);     
                }
                
            }   
            
         $em->persist($producto[0]);
			$em->flush();   
                        
                        
                        
           
       }
       else{
          $repository = $em->getRepository('UnoMainBundle:Expediente'); 
        $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni')->setParameter('expediente','Tramitación')->setParameter('municipio',$arreglo->getMunicipio()) ->setParameter('uni','PNR')->getQuery();
     $pnr = $query->getResult(); 
       $query = $repository->createQueryBuilder('p') ->where('p.tipoExpediente =:expediente and p.municipio=:municipio and p.unidad=:uni')->setParameter('expediente','Tramitación')->setParameter('municipio',$arreglo->getMunicipio()) ->setParameter('uni','DTICO')->getQuery();
     $dtico = $query->getResult(); 
          
         $producto = new Traza();  
              $producto->setFecha($fecha);
               $producto->setMunicipio($arreglo->getMunicipio());
             
          $producto->setCantexpedientes( count($cantidad));
          
          
           if($arreglo->getTipoExpediente()!='Despacho') {
                if($arreglo->getUnidad()=='PNR'){
                
              $c=count($pnr)+1;
              $c+=$producto->getTpnr();
                $producto->setTpnr($c);
                 $producto->setTdtico(count($dtico));   
                   $producto->setInipnr(1);
                }
                else{
                    
               $c=count($dtico)+1;
              $c+=$producto->getTdtico();
                $producto->setTdtico($c); 
                 $producto->setTpnr(count($pnr));
                    $producto->setInidtico(1); 
                    
                }
                
            }   
            
           $em->persist($producto);
			$em->flush();
           
       }
        
    
    
    
}}