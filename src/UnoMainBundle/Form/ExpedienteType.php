<?php

namespace UnoMainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExpedienteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numExpediente')
           ->add('municipio','choice', array( 'choices' =>array('po'=>'Municipio','cerro' => 'Cerro', 'plaza' => 'Plaza','playa' => 'Playa','marianao' => 'Marianao','lisa' => 'Lisa','boyeros' => 'Boyeros','guanabacoa' => 'Guanabacoa','hvieja' => 'Habana Vieja','centrohabana' => 'Centro Habana','arroyo' => 'Arroyo Naranjo',
               '10Octubre' => '10 de Octubre','sanmiguel' => 'San Miguel','cotorro' => 'Cotorro','regla' => 'Regla','h.este' => 'Habana del Este','divico' => 'Divico')))
            ->add('unidad','choice', array( 'choices' => array('po'=>'Seleccione Unidad','PNR' => 'PNR', 'DTICO' => 'DTICO')))
            ->add('acusados','integer', array('required'=>false,'attr' =>array(
            'min' => 1, 'max' => 100))
    )
          ->add('delito','choice', array( 'choices' => array('Seleccione un delito','Amenazas' => 'Amenazas', 'Asesinato' => 'Asesinato',
              'Abusos Lascivos' => 'Abusos Lascivos', 'Act. Económica ILícita' => 'Act. Económica ILícita','Atentado' => 'Atentado', 'Cohecho' => 'Cohecho','Contrabando' => 'Contrabando', 'Estafa' => 'Estafa','Evasión Fiscal' => 'Evasión Fiscal', 'Evasión de Presos' => 'Evasión de Presos'
,              'Falsificación de Documentos' => 'Falsificación de Documentos', 'Homicidio' => 'Homicidio','Homicidio/Tránsito' => 'Homicidio/Tránsito', 'Hurto' => 'Hurto','Lesiones' => 'Lesiones', 'Malversación' => 'Malversación'
,              'Pederastia con violencia' => 'Pederastia con violencia', 'Portación Ilegal de armas' => 'Portación Ilegal de armas','Proxenetismo' => 'Proxenetismo', 'Robo con Fuerza' => 'Robo con Fuerza'
,                     'Robo con Violencia' => 'Robo con Violencia', 'Receptación' => 'Receptación','Tenencia de Droga' => 'Tenencia de Droga', 'Tráfico de Drogas' => 'Tráfico de Drogas'    
 ,             'Violación' => 'Violación', 'Violación de Domicilio' => 'Violación de Domicilio' ,'Juego Prohibido'=> 'Juego Prohibido','Apropiación Indebida'=>'Apropiación Indebida','INPHT'=>'INPHT','Corrupción de Menores'=>'Corrupción de Menores','Lavado de Activos'=>'Lavado de Activos','Tráfico de Personas'=>'Trafico de Personas','Danos'=>'Danos','Coacción'=>'Coacción','Venta/Tráfico de Menores'=>'Venta/Tráfico de Menores','Denegación de Auxilio'=>'Denegación de Auxilio','Abuso en ejercicio del cargo'
=>'Abuso en ejercicio del cargo','Otros'=>'Otros'             
              
              
              
              
              
              )))
                
                  ->add('acusadosp','integer', array('required'=>false,'attr' =>array(
            'min' => 1, 'max' => 100))
    )
            ->add('fechainicial')
            ->add('fechaentrega')
         ->add('tipoExpediente','choice',array( 'choices' =>array('u' => 'Seleccione estado','Tramitación' => 'Tramitación','Despacho'=>'Despacho')))
            ->add('dEVTTP')
            ->add('sIS')
            ->add('dEVFISCAL')
            ->add('pronostico')
            ->add('observaciones')
           
                 ->add('Guardar', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UnoMainBundle\Entity\Expediente'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'unomainbundle_expediente';
    }
}
