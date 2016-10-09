<?php

namespace MyApp\CmsfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RelationMenuPage
 *
 * @ORM\Table(name="cf_RelationMenuPage")
 * @ORM\Entity
 */
class RelationMenuPage {

    /**
     * @var integer
     *
     * @ORM\Column(name="idPage", type="integer")
     * @ORM\Id
     */
    private $idPage;

    /**
     * @var integer
     *
     * @ORM\Column(name="idMenu", type="integer")
     * @ORM\Id
     */
    private $idMenu;

    public function __construct($idMenu, $idPage) {
        $this->setIdMenu($idMenu);
        $this->setIdPage($idPage);
    }

    /**
     * Set idPage
     *
     * @param integer 
     * @return RelationMenuPage
     */
    public function setIdPage($idPage) {
        $this->idPage = $idPage;

        return $this;
    }

    /**
     * Get idPage
     *
     * @return integer 
     */
    public function getIdPage() {
        return $this->idPage;
    }

    /**
     * Set idMenu
     *
     * @param integer $idMenu
     * @return RelationMenuPage
     */
    public function setIdMenu($idMenu) {
        $this->idMenu = $idMenu;

        return $this;
    }

    /**
     * Get idMenu
     *
     * @return integer 
     */
    public function getIdMenu() {
        return $this->idMenu;
    }

}
