<?php

namespace MyApp\CmsfonyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title', 'text')
                ->add('url', 'text')
                ->add('published', 'checkbox', array('required' => false))
                ->add('type', 'choice', array('label' => 'Veuillez choisir un type',
                    'multiple' => false,
                    'choices' => array('Html' => 'Html', 'Blog' => 'Blog'),
                    'attr' => array('style' => 'width:300px;margin-top:-20px;', 'customattr' => 'customdata')
                ))
                ->add('date', 'date')
                ->add('content', 'ckeditor', array('attr' => array('class' => 'ckeditor')))
                ->add('Appliquer', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MyApp\CmsfonyBundle\Entity\Page'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'myapp_cmsfonybundle_page';
    }

}
