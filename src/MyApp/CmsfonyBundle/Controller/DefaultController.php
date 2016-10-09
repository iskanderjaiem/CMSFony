<?php

namespace MyApp\CmsfonyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyApp\CmsfonyBundle\Entity\DefaultTemplate;
use MyApp\CmsfonyBundle\Form\DefaultTemplateType;
use MyApp\CmsfonyBundle\Entity\User;
use MyApp\CmsfonyBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('MyAppCmsfonyBundle::base.html.twig');
    }

    public function templateListAction() {

        return $this->render('MyAppCmsfonyBundle:Appearance:templateList.html.twig');
    }

    public function defaultTemplateConfigAction(Request $request, $templateTitle) {
        $defaultTemplate = new DefaultTemplate();
        $form = $this->createForm(new DefaultTemplateType(), $defaultTemplate);

        $em = $this->getDoctrine()->getManager();
        $defaultTemplate = new DefaultTemplate();
        $defaultTemplate = $em->getRepository('MyAppCmsfonyBundle:DefaultTemplate')->findOneBy(array('title' => 'default'));
        //var_dump($defaultTemplate);
        $Request = $this->get('request_stack')->getCurrentRequest();

        $form = $this->createForm(new DefaultTemplateType(), $defaultTemplate);

        if ($form->handleRequest($request)->isValid()) {
            //remove recent configs  
            $otherTemplateConfig = $em->getRepository('MyAppCmsfonyBundle:DefaultTemplate')->findAll();
            for ($i = 0; $i < count($otherTemplateConfig); $i++) {
                $em->remove($otherTemplateConfig[$i]);
                $em->flush();
            }
            $defaultTemplate->setTitle('default');
            
            $hc = $this->get('request')->request->get('color');
            $defaultTemplate->setHeaderBackgroundColor($hc); 
            $fc = $this->get('request')->request->get('color2');
            $defaultTemplate->setHeaderBackgroundColor($fc);
            
            $em->persist($defaultTemplate);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Configuration du template modifiée avec succès.');

            return $this->redirect($this->generateUrl('my_app_cmsfony_templateList'));
        }

        return $this->render('MyAppCmsfonyBundle:Appearance:defaultTemplateConfig.html.twig', array('form' => $form->createView(), 'template' => $defaultTemplate, 'logo' => $defaultTemplate->getImageLogo()));
    }

    public function profilAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MyAppCmsfonyBundle:User')->find($this->getUser()->getId());
        $Request = $this->get('request_stack')->getCurrentRequest();
        //var_dump($this->getUser());
        $form = $this->createForm(new UserType(), $user);

        if ($form->handleRequest($request)->isValid()) {
            $em->persist($user);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'utilisateur modifié avec succès.');

            return $this->redirect($this->generateUrl('my_app_cmsfony_postList'));
        }
        return $this->render('MyAppCmsfonyBundle:Appearance:profil.html.twig', array('form' => $form->createView(),));
    }

}
