<?php

namespace MyApp\CmsfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DefaultTemplate
 *
 * @ORM\Table(name="cf_default_template")
 * @ORM\Entity
 */
class DefaultTemplate {

    /**
     * @ORM\OneToOne(targetEntity="MyApp\CmsfonyBundle\Entity\Image", cascade={"persist", "remove"}))
     */
    private $imageLogo;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="header_background_color", type="string", nullable=true, length=255)
     */
    private $headerBackgroundColor;

    /**
     * @var string
     *
     * @ORM\Column(name="header_font_color",  type="string", nullable=true, length=255)
     */
    private $headerFontColor;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * Get headerBackgroundColor
     *
     * @return string 
     */
    public function getHeaderBackgroundColor() {
        return $this->headerBackgroundColor;
    }

    /**
     * Set headerBackgroundColor
     *
     * @param integer $headerBackgroundColor
     * @return DefaultTemplate
     */
    public function setHeaderBackgroundColor($headerBackgroundColor) {
        $this->headerBackgroundColor = $headerBackgroundColor;
        return $this;
    }

    /**
     * Set headerFontColor
     *
     * @param integer $headerFontColor
     * @return DefaultTemplate
     */
    public function setHeaderFontColor($headerFontColor) {
        $this->headerFontColor = $headerFontColor;

        return $this;
    }

    /**
      /**
     * Get $headerFontColor
     *
     * @return string 
     */
    public function getHeaderFontColor() {
        return $this->headerFontColor;
    }

    public function setImageLogo(Image $imageLogo = null) {
        $this->imageLogo = $imageLogo;
    }

    public function getImageLogo() {
        return $this->imageLogo;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Page
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

}
