<?php

namespace MyApp\CmsfonyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('email','email')
                ->add('plainpassword','password')
                ->add('description','textarea')
                ->add('Modifier', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MyApp\CmsfonyBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'myapp_cmsfonybundle_user';
    }

}
