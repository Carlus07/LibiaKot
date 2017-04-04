<?php
/**
 * @Entity @Table(name="equipment")
 **/
class Equipment
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idEquipment;
    /** @Column(type="string", nullable=true) **/
    protected $icon;
    /**
     * Un equipement possède un label
     * @OneToOne(targetEntity="Label")
     * @JoinColumn(name="idLabel", referencedColumnName="idLabel")
    */
    protected $idLabel;
    /**
     * Un equipement est référencé par une catégorie
     * @ManyToOne(targetEntity="Category")
     * @JoinColumn(name="idCategory", referencedColumnName="idCategory")
    */
    protected $idCategory;

    public function getId(){
        return $this->idEquipment;
    }
 
    public function getIcon(){
        return $this->icon;
    }
 
    public function setIcon($icon){
        $this->icon = $icon;
    }

    public function getIdLabel(){
        return $this->idLabel;
    }
 
    public function setIdLabel($idLabel){
        $this->idLabel = $idLabel;
    }

    public function getIdCategory(){
        return $this->idCategory;
    }

    public function setIdCategory($idCategory){
        $this->idCategory = $idCategory;
    }
}