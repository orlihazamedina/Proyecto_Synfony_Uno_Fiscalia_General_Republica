<?php

namespace UnoMainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DespachoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
          
            ->add('despachadosDia','integer', array('attr' =>array(
            'min' => 0, 'max' => 100)))

            ->add('totalsis60dias','integer', array('attr' =>array(
            'min' => 0, 'max' => 100)))

                
              ->add('termmas10dias','integer', array('attr' =>array(
            'min' => 0, 'max' => 100)))
            
            ->add('devtrirecibidas60dias','integer', array('attr' =>array(
            'min' => 0, 'max' => 100)))
            
            ->add('concluciones','integer', array('attr' =>array(
            'min' => 0, 'max' => 100)))
            ->add('sobreseimientos','integer',array('attr' =>array(
            'min' => 0, 'max' => 100)))
            ->add('inhibitorias','integer', array('attr' =>array(
            'min' => 0, 'max' => 100)))
            ->add('ochotres','integer',array('attr' =>array(
            'min' => 0, 'max' => 100)))
            ->add('otros','integer', array('attr' =>array(
            'min' => 0, 'max' => 100)))
            ->add('remitidosFactura','integer', array('attr' =>array(
            'min' => 0, 'max' => 100)))
                 
                   ->add('Guardar', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UnoMainBundle\Entity\Despacho'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'unomainbundle_despacho';
    }
}
