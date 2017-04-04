<?php
/**
 * @Entity @Table(name="menu")
 **/
class Menu
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idMenu;
    /**
     * Un menu possède un label.
     * @OneToOne(targetEntity="Label")
     * @JoinColumn(name="idLabel", referencedColumnName="idLabel")
     */
    protected $idLabel;
    /**
     * Un menu possède un role.
     * @ManyToOne(targetEntity="Role")
     * @JoinColumn(name="idRole", referencedColumnName="idRole")
     */
    protected $idRole;

    /** @Column(type="string", length=100) **/
    protected $link;

    public function getId(){
        return $this->idMenu;
    }
 
    public function getIdRole(){
        return $this->idRole;
    }
 
    public function setIdRole($idRole){
        $this->idRole = $idRole;
    }

    public function getIdLabel(){
        return $this->idLabel;
    }
 
    public function setIdLabel($idLabel){
        $this->idLabel = $idLabel;
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