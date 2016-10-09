<?php

namespace MyApp\CmsfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table(name="cf_page")
 * @ORM\Entity
 */
class Page
{
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

  
    
     /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;
    
    
     /**
     * @var boolean
     *
     * @ORM\Column(name="is_home_page", type="boolean")
     */
    private $isHomePage = false;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=100)
     */
    private $type;

    /**
     * @var date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;
 /**
     * @var string
     *
     * @ORM\Column(name="content", nullable=true, type="string", length=20000)
     */
    private $content;
    
    
     public function __construct() {
        $this->date = new \Datetime();
    }
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    
    
      /**
     * Set content
     *
     * @param string $content
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Page
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

   
    /**
     * Set type
     *
     * @param string $type
     * @return Page
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
    
     /**
     * Set date
     *
     * @param date $date
     * @return Page
     */
    public function setDate($date)
    {  
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return string 
     */
    public function getDate()
    {
        return $this->date;
    }
    
    
      /**
     * @param boolean $published
     * @return Page
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
     * @param boolean $isHomePage
     * @return Page
     */
    public function setIsHomePage($isHomePage) {
        $this->isHomePage = $isHomePage;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsHomePage() {
        return $this->isHomePage;
    }

    
}
