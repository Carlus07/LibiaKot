<?php
/**
 * @Entity @Table(name="type")
 **/
class Type
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idType;
    /**
     * Un type possède un label.
     * @OneToOne(targetEntity="Label")
     * @JoinColumn(name="idLabel", referencedColumnName="idLabel")
     */
    protected $idLabel;

    public function getId(){
        return $this->idType;
    }
    
    public function getIdLabel(){
        return $this->idLabel;
    }
 
    public function setIdLabel($idLabel){
        $this->idLabel = $idLabel;
    }
}