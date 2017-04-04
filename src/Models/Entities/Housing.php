<?php
/**
 * @Entity @Table(name="housing")
 **/
class Housing
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idHousing;
    /**
     * Une logement possède un idSubType
     * @OneToOne(targetEntity="subType")
     * @JoinColumn(name="idSubType", referencedColumnName="idSubType")
    */
    protected $idSubType;
    /** @Column(type="float") **/
    protected $charges;
    /** @Column(type="float") **/
    protected $rent;
    /** @Column(type="integer") **/
    protected $contractTerm;
    /** @Column(type="float", nullable=true) **/
    protected $area;
    /** @Column(type="integer") **/
    protected $numberPeople;
    /** @Column(type="integer") **/
    protected $occupation;
    /** @Column(type="integer") **/
    protected $state;
    /**
     * Une logement possède une propriété
     * @OneToOne(targetEntity="Property")
     * @JoinColumn(name="idProperty", referencedColumnName="idProperty")
    */
    protected $idProperty;

    public function getId(){
        return $this->idHousing;
    }
 
    public function getIdSubType(){
        return $this->idSubType;
    }
 
    public function setIdSubType($idSubType){
        $this->idSubType = $idSubType;
    }

    public function getCharges(){
        return $this->charges;
    }
 
    public function setCharges($charges){
        $this->charges = $charges;
    }

    public function getRent(){
        return $this->rent;
    }
 
    public function setRent($rent){
        $this->rent = $rent;
    }

    public function getContratTerm(){
        return $this->contractTerm;
    }
 
    public function setContractTerm($contractTerm){
        $this->contractTerm = $contractTerm;
    }

    public function getArea(){
        return $this->area;
    }
 
    public function setArea($area){
        $this->area = $area;
    }

    public function getNumberPeople(){
        return $this->numberPeople;
    }
 
    public function setNumberPeople($numberPeople){
        $this->numberPeople = $numberPeople;
    }

    public function getOccupation(){
        return $this->occupation;
    }
 
    public function setOccupation($occupation){
        $this->occupation = $occupation;
    }

    public function getState(){
        return $this->state;
    }
 
    public function setState($state){
        $this->state = $state;
    }

    public function getIdProperty(){
        return $this->idProperty;
    }
 
    public function setIdProperty($idProperty){
        $this->idProperty = $idProperty;
    }
}