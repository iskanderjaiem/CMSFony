<?php

namespace MyApp\CmsfonyBundle\Controller;

use MyApp\CmsfonyBundle\Entity\Post;
use MyApp\CmsfonyBundle\Form\PostType;
use MyApp\CmsfonyBundle\Form\ImageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller {

    public function postListAction(Request $request) {
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT p FROM MyAppCmsfonyBundle:Post p";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1)/* page number */, $this->container->getParameter('limit_page')/* limit per page */
        );
        // $em = $this->getDoctrine()->getManager();
        return $this->render('MyAppCmsfonyBundle:Post:postList.html.twig', array('posts' => $pagination));
    }

    public function addPostAction(Request $request) {

        $post = new Post();
        $post->setAuthor($this->getUser()->getUsername());
        $form = $this->createForm(new PostType(), $post);
        //same as $this->get('form.factory')->create(..)

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Article ajouté avec succès.');

            return $this->redirect($this->generateUrl('my_app_cmsfony_postList'));
        }

        return $this->render('MyAppCmsfonyBundle:Post:addPost.html.twig', array('form' => $form->createView(),));
    }

    public function editPostAction($postId, Request $request) {

        //remplissage

        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('MyAppCmsfonyBundle:Post')->find($postId);
        $Request = $this->get('request_stack')->getCurrentRequest();

        $post->setAuthor($this->getUser()->getUsername());
        $form = $this->createForm(new PostType(), $post);


        if ($form->handleRequest($request)->isValid()) {
            $em->persist($post);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Article modifié avec succès.');

            return $this->redirect($this->generateUrl('my_app_cmsfony_postList'));
        }
        return $this->render('MyAppCmsfonyBundle:Post:editPost.html.twig', array('form' => $form->createView(), 'postId' => $postId));
    }

    public function renderPostAction($menuPageUrl, $postId) {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('MyAppCmsfonyBundle:Post')->findBy(array('id' => $postId));
        $post = $post[0];

        $authorInfo = $em->getRepository('MyAppCmsfonyBundle:User')->findBy(array('username' => $post->getAuthor()));
        $authorDescription = $authorInfo[0]->getDescription();
        return $this->render('MyAppCmsfonyFrontBundle:Page:post.html.twig', array('post' => $post, 'authorDescription' => $authorDescription));
    }

    public function deletePostAction($postId, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('MyAppCmsfonyBundle:Post')->find($postId);
        $em->remove($post);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Article supprimée avec succès.');
        return $this->postListAction();
    }

    public function unpublishPostAction($postId, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('MyAppCmsfonyBundle:Post')->find($postId);
        $Request = $this->get('request_stack')->getCurrentRequest();
        if ($post->getPublished() == 0) {
            $post->setPublished(1);
        } else {
            $post->setPublished(0);
        }
        $em->persist($post);
        $em->flush();
        if ($post->getPublished() == 0) {
            $request->getSession()->getFlashBag()->add('notice', 'Article non publiée.');
        } else {
            $request->getSession()->getFlashBag()->add('notice', 'Article publiée avec succès.');
        }

        return $this->redirect($this->generateUrl('my_app_cmsfony_postList'));
    }

    public function postInLastDaysAction($nbDays, Request $request) {

        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT p FROM MyAppCmsfonyBundle:Post p WHERE p.date > CURRENT_DATE()- ?1 ');
        $query->setParameter(1, $nbDays);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1)/* page number */, $this->container->getParameter('limit_page')/* limit per page */
        );
        return $this->render('MyAppCmsfonyBundle:Post:postList.html.twig', array('posts' => $pagination, 'nbDays' => $nbDays));
    }

    public function postSearchAction($keyword, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $Request = $this->get('request_stack')->getCurrentRequest();

        $query = $em->createQuery('SELECT p FROM MyAppCmsfonyBundle:Post p WHERE p.title LIKE :key or p.content LIKE :key ');
        $query->setParameter('key', '%' . $keyword . '%');
        $posts = $query->getResult();

        return $this->render('MyAppCmsfonyBundle:Post:postList.html.twig', array('posts' => $posts));
    }

}
