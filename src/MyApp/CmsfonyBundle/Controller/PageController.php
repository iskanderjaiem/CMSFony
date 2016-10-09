<?php

namespace MyApp\CmsfonyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyApp\CmsfonyBundle\Entity\Page;
use MyApp\CmsfonyBundle\Form\PageType;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller {

    public function pageListAction(Request $request) {

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT p FROM MyAppCmsfonyBundle:Page p";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1)/* page number */, $this->container->getParameter('limit_page')/* limit per page */
        );
        // $em = $this->getDoctrine()->getManager();
        return $this->render('MyAppCmsfonyBundle:Page:pageList.html.twig', array('pagination' => $pagination));
    }

    public function addPageAction(Request $request) {

        $page = new Page();
        $form = $this->createForm(new PageType, $page);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Page ajoutée avec succès.');

            return $this->redirect($this->generateUrl('my_app_cmsfony_pageList'));
        }

        return $this->render('MyAppCmsfonyBundle:Page:addPage.html.twig', array('form' => $form->createView(),));
    }

    public function editPageAction($pageId, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('MyAppCmsfonyBundle:Page')->find($pageId);
        $Request = $this->get('request_stack')->getCurrentRequest();

        $form = $this->createForm(new PageType, $page);

        if ($form->handleRequest($request)->isValid()) {
            $em->persist($page);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Page modifiée avec succès.');

            return $this->redirect($this->generateUrl('my_app_cmsfony_pageList'));
        }

        return $this->render('MyAppCmsfonyBundle:Page:editPage.html.twig', array('form' => $form->createView(), 'pageId' => $pageId));
    }

    public function deletePageAction($pageId, Request $request) {
        $page = new Page();
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('MyAppCmsfonyBundle:Page')->find($pageId);
        $em->remove($page);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Page supprimée avec succès.');
        return $this->redirect($this->generateUrl('my_app_cmsfony_pageList'));
    }

    public function unpublishPageAction($pageId, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('MyAppCmsfonyBundle:Page')->find($pageId);
        $Request = $this->get('request_stack')->getCurrentRequest();
        if ($page->getIsHomePage() && $page->getPublished() == 1) {
            $request->getSession()->getFlashBag()->add('alerte', 'Une page d\'accueil doit être publié');
        } else {
            if ($page->getPublished() == 0) {
                $page->setPublished(1);
            } else {
                $page->setPublished(0);
            }
            $em->persist($page);
            $em->flush();
            if ($page->getPublished() == 0) {
                $request->getSession()->getFlashBag()->add('alerte', 'Page non publiée.');
            } else {
                $request->getSession()->getFlashBag()->add('notice', 'Page publiée avec succès.');
            }
        }
        return $this->redirect($this->generateUrl('my_app_cmsfony_pageList'));
    }

    public function HomePageOnAction($pageId, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('MyAppCmsfonyBundle:Page')->find($pageId);
        $Request = $this->get('request_stack')->getCurrentRequest();

        $query = $em->createQuery('SELECT p.published FROM MyAppCmsfonyBundle:Page p WHERE p.id = ?1');
        $query->setParameter(1, $pageId);
        $currentpagePublished = $query->getSingleScalarResult();

        if ($currentpagePublished) {
            $query = $em->createQuery('SELECT count(p.id) FROM MyAppCmsfonyBundle:Page p WHERE p.isHomePage = 1');
            $pageNb = $query->getSingleScalarResult();
            if ($pageNb == 1) {

                //SuppHomePage
                $query = $em->createQuery('SELECT p FROM MyAppCmsfonyBundle:Page p WHERE p.isHomePage = 1');
                $page = $query->getResult();
                $page = $page[0];
                $page->setIsHomePage(0);
                $em->persist($page);
                $em->flush();
            }
            //affectNewHomePage
            $query = $em->createQuery('SELECT p FROM MyAppCmsfonyBundle:Page p WHERE p.id = ?1');
            $query->setParameter(1, $pageId);
            $page = $query->getResult();
            $page = $page[0];
            var_dump($page);
            if ($page->getIsHomePage()) {
                $page->setIsHomePage(0);
            } else {
                $page->setIsHomePage(1);
            }
            $em->persist($page);
            $em->flush();
            if ($page->getIsHomePage() == 1) {
                $request->getSession()->getFlashBag()->add('notice', 'Page d\'accueil affectée avec succès.');
            }
        } else {
            $request->getSession()->getFlashBag()->add('alerte', 'Une page d\'accueil doit être publiée.');
        }
        return $this->redirect($this->generateUrl('my_app_cmsfony_pageList'));
    }

    public function homePageAction() {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('MyAppCmsfonyBundle:Page')->findBy(array('isHomePage' => '1'));
        $page = $page[0];

        return $this->render('MyAppCmsfonyFrontBundle:Page:standardPage.html.twig', array('content' => $page->getContent()));
    }

    public function pageInLastDaysAction($nbDays, Request $request) {

        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT p FROM MyAppCmsfonyBundle:Page p WHERE p.date > CURRENT_DATE()- ?1 ');
        $query->setParameter(1, $nbDays);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1)/* page number */, $this->container->getParameter('limit_page')/* limit per page */
        );
        return $this->render('MyAppCmsfonyBundle:Page:pageList.html.twig', array('pagination' => $pagination, 'nbDays' => $nbDays));
    }

    public function pageSearchAction($keyword, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $Request = $this->get('request_stack')->getCurrentRequest();

        $query = $em->createQuery('SELECT p FROM MyAppCmsfonyBundle:Page p WHERE p.title LIKE :key ');
        $query->setParameter('key', '%' . $keyword . '%');
        $pages = $query->getResult();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1)/* page number */, $this->container->getParameter('limit_page')/* limit per page */
        );
        // $em = $this->getDoctrine()->getManager();

        return $this->render('MyAppCmsfonyBundle:Page:pageList.html.twig', array('pages' => $pages,'pagination' => $pagination));
    }

}
