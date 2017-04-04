<?php
/**
 * @Entity @Table(name="category")
 **/
class Category
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idCategory;
    /**
     * Une catégorie possède un label.
     * @OneToOne(targetEntity="Label")
     * @JoinColumn(name="idLabel", referencedColumnName="idLabel")
     */
    protected $idLabel;
   
    
    public function getId(){
        return $this->idCategory;
    }
 
    public function getIdLabel(){
        return $this->idLabel;
    }
 
    public function setIdLabel($idLabel){
        $this->idLabel = $idLabel;
    }
}