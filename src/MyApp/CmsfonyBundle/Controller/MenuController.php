<?php

/**
 * Description of MenuController
 *
 * @author Iskander
 */

namespace MyApp\CmsfonyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyApp\CmsfonyBundle\Entity\Menu;
use MyApp\CmsfonyBundle\Entity\RelationMenuPage;
use MyApp\CmsfonyBundle\Form\MenuType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class MenuController extends Controller {

    public function renderPrimaryMenuAction() {
        $em = $this->getDoctrine()->getManager();


        $query = $em->createQuery("SELECT count(m) FROM MyAppCmsfonyBundle:Menu m WHERE m.position=?1");
        $query->setParameter(1, 'primary');
        $nbPages = $query->getSingleScalarResult();
        if ($nbPages != 0) {
            $primaryMenuId = $em->getRepository('MyAppCmsfonyBundle:Menu')->findOneBy(array('position' => 'primary'))->getId();
            //get the pages in the requested menu
            $query2 = $em->createQuery("SELECT p FROM MyAppCmsfonyBundle:Page p, MyAppCmsfonyBundle:RelationMenuPage mp WHERE p.id=mp.idPage and  mp.idMenu = ?1");
            $query2->setParameter(1, $primaryMenuId);
            $menuPages = $query2->getResult();


            //********************** ICON IMAGE***************
            $query = $em->createQuery("SELECT count(m) FROM MyAppCmsfonyBundle:DefaultTemplate m WHERE m.title=?1");
            $query->setParameter(1, 'default');
            $nbTemplate = $query->getSingleScalarResult();
            if ($nbTemplate != 0) {
                $templateId = $em->getRepository('MyAppCmsfonyBundle:DefaultTemplate')->findOneBy(array('title' => 'default'))->getId();
                //get the pages in the requested menu
                $query2 = $em->createQuery("SELECT p FROM MyAppCmsfonyBundle:DefaultTemplate p, MyAppCmsfonyBundle:Image i WHERE p.imageLogo=i.id and  p.title=?1");
                $query2->setParameter(1, 'default');
                $template = $query2->getResult();
                $template=$template[0];
                return $this->render('MyAppCmsfonyFrontBundle:Menu:primaryMenu.html.twig', array('menuPages' => $menuPages, 'template' => $template));
            }
            return $this->render('MyAppCmsfonyFrontBundle:Menu:primaryMenu.html.twig', array('menuPages' => $menuPages));
        }
        return $this->render('MyAppCmsfonyFrontBundle:Menu:primaryMenu.html.twig', array());
    }

    public function menuListAction() {
        $em = $this->getDoctrine()->getManager();
        $menus = $em->getRepository('MyAppCmsfonyBundle:Menu')->findAll();
        return $this->render('MyAppCmsfonyBundle:Menu:menuList.html.twig', array('menus' => $menus));
    }

    public function addMenuAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        //get available pages
        $availablePages = $em->getRepository('MyAppCmsfonyBundle:Page')->findAll();
        $menu = new Menu();
        $form = $this->createForm(new MenuType, $menu);
        if ($form->handleRequest($request)->isValid()) {

            $option = $this->get('request')->request->get('optionsRadios');
            if ($option == 'option1') {
                $query = $em->createQuery("SELECT count(m) FROM MyAppCmsfonyBundle:Menu m WHERE m.position=?1");
                $query->setParameter(1, 'primary');
                $menuPrimary = $query->getSingleScalarResult();
                if ($menuPrimary != 0) {
                    $request->getSession()->getFlashBag()->add('alerte', 'Un menu principal exisite déja!');
                    return $this->render('MyAppCmsfonyBundle:Menu:addMenu.html.twig', array('form' => $form->createView(), 'availablePages' => $availablePages, 'menu' => $menu));
                }
            }

            $em = $this->getDoctrine()->getManager();

            $em->persist($menu);
            $em->flush();
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new GetSetMethodNormalizer());

            $serializer = new Serializer($normalizers, $encoders);

            //$json = $this->get('request')->request->get('nestable-output');
            //$pagesDispoArray = $serializer->decode($json, 'json');
            $json = $this->get('request')->request->get('nestable2-output');
            $pagesMenuArray = $serializer->decode($json, 'json');
            var_dump($json);
            var_dump(count($pagesMenuArray));

            //Add the new MenuPages relation
            for ($j = 0; $j < count($pagesMenuArray); $j++) {
                if ($pagesMenuArray[$j]['id'] != 0) {
                    $relationMenuPage = new RelationMenuPage($menu->getId(), $pagesMenuArray[$j]['id']);
                    $em->persist($relationMenuPage);
                    $em->flush();
                }
            }

            $request->getSession()->getFlashBag()->add('notice', 'Le menu a été ajouté avec succès');
            return $this->redirect($this->generateUrl('my_app_cmsfony_menuList'));
        }



        return $this->render('MyAppCmsfonyBundle:Menu:addMenu.html.twig', array('form' => $form->createView(), 'availablePages' => $availablePages, 'menu' => $menu));
    }

    public function editMenuAction(Request $request, $menuId) {

        $em = $this->getDoctrine()->getManager();

        //get the pages in the requested menu
        $query = $em->createQuery("SELECT p FROM MyAppCmsfonyBundle:Page p, MyAppCmsfonyBundle:RelationMenuPage mp WHERE p.id=mp.idPage and  mp.idMenu = ?1");
        $query->setParameter(1, $menuId);
        $menuPages = $query->getResult();

        //get available pages
        $availablePages = $em->getRepository('MyAppCmsfonyBundle:Page')->findAll();

        $query = $em->createQuery("SELECT m FROM MyAppCmsfonyBundle:Menu m WHERE m.id=?1");
        $query->setParameter(1, $menuId);
        $menu = $query->getResult();
        $menu = $menu[0];
        $form = $this->createForm(new MenuType, $menu);
        if ($form->handleRequest($request)->isValid()) {

            $option = $this->get('request')->request->get('optionsRadios');
            if ($option == 'option1') {
                $query = $em->createQuery("SELECT count(m) FROM MyAppCmsfonyBundle:Menu m WHERE m.id!=?1 and m.position=?2");
                $query->setParameter(1, $menuId);
                $query->setParameter(2, 'primary');
                $menuPrimary = $query->getSingleScalarResult();
                if ($menuPrimary != 0) {
                    $request->getSession()->getFlashBag()->add('alerte', 'Un menu principal exisite déja!');
                    return $this->render('MyAppCmsfonyBundle:Menu:editMenu.html.twig', array('form' => $form->createView(), 'availablePages' => $availablePages, 'menuPages' => $menuPages, 'menu' => $menu));
                }
            }

            $em = $this->getDoctrine()->getManager();

            $em->persist($menu);
            $em->flush();
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new GetSetMethodNormalizer());

            $serializer = new Serializer($normalizers, $encoders);

            //$json = $this->get('request')->request->get('nestable-output');
            //$pagesDispoArray = $serializer->decode($json, 'json');
            $json = $this->get('request')->request->get('nestable2-output');
            $pagesMenuArray = $serializer->decode($json, 'json');
            var_dump($json);
            var_dump(count($pagesMenuArray));


            //Delete all the MenuPages relation
            $query = $em->createQuery("SELECT mp FROM MyAppCmsfonyBundle:RelationMenuPage mp WHERE mp.idMenu=?1");
            $query->setParameter(1, $menuId);
            $MenuInRelation = $query->getResult();
            for ($i = 0; $i < count($MenuInRelation); $i++) {
                $em->remove($MenuInRelation[$i]);
                $em->flush();
            }

            //Add the new MenuPages relation
            for ($j = 0; $j < count($pagesMenuArray); $j++) {
                if ($pagesMenuArray[$j]['id'] != 0) {
                    $relationMenuPage = new RelationMenuPage($menu->getId(), $pagesMenuArray[$j]['id']);
                    $em->persist($relationMenuPage);
                    $em->flush();
                }
            }

            $request->getSession()->getFlashBag()->add('notice', 'Le menu a été modifié avec succès');
            return $this->redirect($this->generateUrl('my_app_cmsfony_menuList'));
        }



        return $this->render('MyAppCmsfonyBundle:Menu:editMenu.html.twig', array('form' => $form->createView(), 'availablePages' => $availablePages, 'menuPages' => $menuPages, 'menu' => $menu));
    }

    //front
    public function renderMenuUrlAction(Request $request, $menuPageUrl) {
        $em = $this->getDoctrine()->getManager();
        $primaryMenuId = $em->getRepository('MyAppCmsfonyBundle:Menu')->findOneBy(array('position' => 'primary'))->getId();
        
        //get the pages in the requested menu
        $query = $em->createQuery("SELECT p FROM MyAppCmsfonyBundle:Page p, MyAppCmsfonyBundle:RelationMenuPage mp WHERE p.id=mp.idPage and  mp.idMenu = ?1");
        $query->setParameter(1, $primaryMenuId);
        $menuPages = $query->getResult();

        $typePage = $em->getRepository('MyAppCmsfonyBundle:Page')->findBy(array('url' => $menuPageUrl))[0]->getType();

        if ($typePage == "Blog") {//à convertir en type
            $em = $this->get('doctrine.orm.entity_manager');
            $dql = "SELECT p FROM MyAppCmsfonyBundle:Post p WHERE p.published=1";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $posts = $paginator->paginate(
                    $query, $request->query->getInt('page', 1)/* page number */, $this->container->getParameter('limit_page')/* limit per page */
            );
            return $this->render('MyAppCmsfonyFrontBundle:Page:blog.html.twig', array('posts' => $posts));
        } else {
            $em = $this->getDoctrine()->getManager();
            $page = $em->getRepository('MyAppCmsfonyBundle:Page')->findBy(array('url' => $menuPageUrl));
            return $this->render('MyAppCmsfonyFrontBundle:Page:standardPage.html.twig', array('content' => $page[0]->getContent()));
        }
    }

}
