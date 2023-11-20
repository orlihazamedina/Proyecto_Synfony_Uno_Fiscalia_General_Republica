<?php


namespace UnoMainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UnoMainBundle\Entity\User;
use UnoMainBundle\Form\UserType;
use Symfony\Component\Security\Core\SecurityContext;



class SecurityController extends Controller
{
    public function loginAction()
    {
        
        if($this->get('security.context')->isGranted('ROLE_ADMIN' )or$this->get('security.context')->isGranted('ROLE_USER' ) ){
        
         $response = $this->forward('UnoMainBundle:Default:orli', array(
        
        'index' => '1'
    ));

    // ... adicionalmente modifica la respuesta o la devuelve
    //     directamente

    return $response;
        }
        else{
         $authenticationUtils = $this->get('security.authentication_utils');
        
        $error = $authenticationUtils->getLastAuthenticationError();
        
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('UnoMainBundle:Security:login.html.twig', array('last_username' => $lastUsername, 'error' => $error));     
        } }
    
    public function loginCheckAction()
    {
        
    }
     public function userAction(Request $request)
    {
         $producto = new User();
    $form = $this->createForm(new UserType(), $producto);
             
                $form->handleRequest($request);
	
                
                
                if ($form->isValid()) {
                     $password = $form->get('password')->getData();
                  $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($producto, $password);
                
                $producto->setPassword($encoded);   
                    
                    
			$em = $this->getDoctrine()->getManager();
			$em->persist($producto);
			$em->flush();
			$successMessage = $this->get('translator')->trans('El Usuario ha sido creado satisfactoriamente');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_usuarios'));
		}	
		
		return $this->render("UnoMainBundle:Security:add.html.twig", array(
				"form"=>$form->createView()
		));
    }
 public function usereditAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
		$producto = $em->getRepository('UnoMainBundle:User')->findOneById($id); 
    $form = $this->createForm(new UserType(), $producto);
             
                $form->handleRequest($request);
	
                
                
                if ($form->isValid()) {

                     $password = $form->get('password')->getData();
                  $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($producto, $password);
                
                $producto->setPassword($encoded);   
                    

			$em = $this->getDoctrine()->getManager();
			$em->persist($producto);
			$em->flush();
			$successMessage = $this->get('translator')->trans('El Usuario ha sido creado satisfactoriamente');
                $this->addFlash('mensaje', $successMessage);
			return $this->redirect($this->generateUrl('uno_main_usuarios'));
		}	
		
		return $this->render("UnoMainBundle:Security:add.html.twig", array(
				"form"=>$form->createView()
		));
    }
}



