<?php
/**
 * @Entity @Table(name="subtype")
 **/
class SubType
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idSubType;
    /**
     * Une activité possède un label.
     * @OneToOne(targetEntity="Label")
     * @JoinColumn(name="idLabel", referencedColumnName="idLabel")
     */
    protected $idLabel;
    /**
     * Un sous-type est référencé par des types.
     * @ManyToOne(targetEntity="Type")
     * @JoinColumn(name="idType", referencedColumnName="idType")
     */
    protected $idType;

    public function getId(){
        return $this->idSubType;
    }
 
    public function getIdLabel(){
        return $this->idLabel;
    }
 
    public function setIdLabel($idLabel){
        $this->idLabel = $idLabel;
    }

    public function getIdType(){
        return $this->idType;
    }
 
    public function setIdType($idType){
        $this->idType = $idType;
    }
}