<?php

namespace UnoMainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FuerzaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('totalEspecialidad')
                 
            ->add('dedControl','integer', array('attr' =>array(
            'min' => 0, 'max' => 100))
    )
            ->add('dedDespacho','integer', array('attr' =>array(
            'min' => 0, 'max' => 100))
    )
            ->add('dedJuicios','integer', array('attr' =>array(
            'min' => 0, 'max' => 100))
    )
            ->add('otrasActividades','integer', array('attr' =>array(
            'min' => 0, 'max' => 100))
    )
            ->add('totaljuicios','integer', array('attr' =>array(
            'min' => 0, 'max' => 100))
    )
            ->add('sumario','integer', array('attr' =>array(
            'min' => 0, 'max' => 100))
    )
            ->add('ordinario','integer', array('attr' =>array(
            'min' => 0, 'max' => 100))
    )
            ->add('tribProvOrd','integer', array('attr' =>array(
            'min' => 0, 'max' => 100))
    )
            ->add('tribProvApel','integer', array('attr' =>array(
            'min' => 0, 'max' => 100))
    )
            ->add('incidenciasReport','integer', array('attr' =>array(
            'min' => 0, 'max' => 100))
    )
                  
                ->add('Guardar', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UnoMainBundle\Entity\Fuerza'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'unomainbundle_fuerza';
    }
}
