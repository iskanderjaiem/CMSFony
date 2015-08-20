<?php
namespace Acme\BasicCmsBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use Acme\BasicCmsBundle\Document\Post;

class LoadPostData implements FixtureInterface
{
    public function load(ObjectManager $dm)
    {
        if (!$dm instanceof DocumentManager) {
            $class = get_class($dm);
            throw new \RuntimeException("Fixture requires a PHPCR ODM DocumentManager instance, instance of '$class' given.");
        }

        $parent = $dm->find(null, '/cms/posts');

        foreach (array('First', 'Second', 'Third', 'Fourth') as $title) {
            $post = new Post();
            $post->setTitle(sprintf('My %s Post', $title));
            $post->setParentDocument($parent);
            $post->setContent(<<<HERE
This is the content of my post.
HERE
            );

            $dm->persist($post);
        }

        $dm->flush();
    }
}