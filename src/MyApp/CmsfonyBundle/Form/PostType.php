<?php

namespace MyApp\CmsfonyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('date', 'date')
                ->add('published', 'checkbox', array('required' => false))
                ->add('title', 'text')
                ->add('image', new ImageType(), array('required' => false))
                ->add('content', 'ckeditor', array('attr' => array('class' => 'ckeditor')))
                ->add('Appliquer', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MyApp\CmsfonyBundle\Entity\Post'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'myapp_cmsfonybundle_post';
    }

}
