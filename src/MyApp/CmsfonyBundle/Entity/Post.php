<?php

namespace MyApp\CmsfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="cf_post")
 * @ORM\Entity
 */
class Post {

    /**
     * @ORM\OneToOne(targetEntity="MyApp\CmsfonyBundle\Entity\Image", cascade={"persist", "remove"}))
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=20000)
     */
    private $content;

    /**
     * @var date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    public function __construct() {
        $this->date = new \Datetime();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Post
     */
    public function setAuthor($author) {
        $this->author = $author;

        return $this;
    }

    /**
     * @param boolean $published
     * @return Post
     */
    public function setPublished($published) {
        $this->published = $published;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPublished() {
        return $this->published;
    }

    /**
      /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return string
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param date $date
     * @return Post
     */
    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return string 
     */
    public function getDate() {
        return $this->date;
    }

    public function setImage(Image $image = null) {
        $this->image = $image;
    }

    public function getImage() {
        return $this->image;
    }

}
