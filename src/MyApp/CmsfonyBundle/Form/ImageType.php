<?php

namespace MyApp\CmsfonyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                //->add('url')
                //->add('alt')
                ->add('file', 'file')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MyApp\CmsfonyBundle\Entity\Image'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'myapp_cmsfonybundle_image';
    }

}
