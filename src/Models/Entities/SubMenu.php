<?php
/**
 * @Entity @Table(name="subMenu")
 **/
class SubMenu
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idSubMenu;
    /**
     * Une activité possède un label.
     * @OneToOne(targetEntity="Label")
     * @JoinColumn(name="idLabel", referencedColumnName="idLabel")
     */
    protected $idLabel;
    /**
     * Un sous-menu est référencé par des menus.
     * @ManyToOne(targetEntity="Menu")
     * @JoinColumn(name="idMenu", referencedColumnName="idMenu")
     */
    protected $idMenu;
    /** @Column(type="string", length=100) **/
    protected $link;
    
    public function getId(){
        return $this->idSubMenu;
    }
 
    public function getIdLabel(){
        return $this->idLabel;
    }
 
    public function setIdLabel($idLabel){
        $this->idLabel = $idLabel;
    }

    public function getIdMenu(){
        return $this->idMenu;
    }
 
    public function setIdMenu($idMenu){
        $this->idMenu = $idMenu;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }
}