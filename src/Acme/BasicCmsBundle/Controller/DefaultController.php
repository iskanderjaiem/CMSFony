<?php

namespace Acme\BasicCmsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
class DefaultController extends Controller
{
       public function indexAction()
    {
        $dm = $this->get('doctrine_phpcr')->getManager();
        $site = $dm->find('Acme\BasicCmsBundle\Document\Site', '/cms');
        $homepage = $site->getHomepage();

        if (!$homepage) {
            throw $this->createNotFoundException('No homepage configured');
        }

        return $this->forward('AcmeBasicCmsBundle:Default:page', array(
            'contentDocument' => $homepage
        ));
    }
    
      /**
     * @Template()
     */
    public function pageAction($contentDocument)
    {
        $dm = $this->get('doctrine_phpcr')->getManager();
        $posts = $dm->getRepository('AcmeBasicCmsBundle:Post')->findAll();

        return array(
            'page'  => $contentDocument,
            'posts' => $posts,
        );
    }
    

    /**
     * @Template()
     */
    public function postAction($contentDocument)
    {
        $dm = $this->get('doctrine_phpcr')->getManager();
        $pages= $dm->getRepository('AcmeBasicCmsBundle:Page')->findAll();
        
        return array(
            'post'  => $contentDocument,
            'pages' =>$pages,
        );
    }
    
    
  
}
