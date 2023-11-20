<?php

namespace UnoMainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TramitacionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
           
           
           
          
          
                 ->add('TotalContdia','integer', array('attr' =>array(
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
            'data_class' => 'UnoMainBundle\Entity\Tramitacion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'unomainbundle_tramitacion';
    }
}
