<?php

namespace MyApp\CmsfonyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DefaultTemplateType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        //, array('required' => false)
        $builder
                ->add('imageLogo', new ImageType())
                ->add('headerFontColor')
                ->add('headerBackgroundColor')
                ->add('Appliquer', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MyApp\CmsfonyBundle\Entity\DefaultTemplate'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'myapp_cmsfonybundle_defaultTemplate';
    }

}
