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
     * @ManyToOne(targetEntity="subType")
     * @JoinColumn(name="idSubType", referencedColumnName="idSubType", nullable=true)
    */
    protected $idSubType;
    /**
     * Une logement possède un idType
     * @ManyToOne(targetEntity="Type")
     * @JoinColumn(name="idType", referencedColumnName="idType")
    */
    protected $idType;
    /** @Column(type="datetime") **/
    protected $availability;
    /** @Column(type="integer", nullable=true) **/
    protected $capacity;
    /** @Column(type="integer", nullable=true) **/
    protected $spaceAvailable;
    /** @Column(type="integer") **/
    protected $area;
    /** @Column(type="integer") **/
    protected $floor;
    /** @Column(type="integer") **/
    protected $bathroom;
    /** @Column(type="integer") **/
    protected $kitchen;
    /** @Column(type="integer") **/
    protected $heating;
    /** @Column(type="integer") **/
    protected $charge;
    /** @Column(type="integer") **/
    protected $rent;
    /** @Column(type="integer") **/
    protected $deposit;
     /** @Column(type="integer") **/
    protected $rentalDuration;
     /** @Column(type="text", nullable=true) **/
    protected $rentComment;
    /** @Column(type="integer") **/
    protected $state;
    /**
     * Une logement possède une propriété
     * @OneToOne(targetEntity="Property")
     * @JoinColumn(name="idProperty", referencedColumnName="idProperty")
    */
    protected $idProperty;
    /** @Column(type="integer") **/
    protected $reference;

    public function getId(){
        return $this->idHousing;
    }
 
    public function getIdSubType(){
        return $this->idSubType;
    }
 
    public function setIdSubType($idSubType){
        $this->idSubType = $idSubType;
    }

    public function getIdType(){
        return $this->idType;
    }
 
    public function setIdType($idType){
        $this->idType = $idType;
    }

    public function getAvailability(){
        return $this->availability;
    }
 
    public function setAvailability($availability){
        $this->availability = $availability;
    }

    public function getCapacity(){
        return $this->capacity;
    }
 
    public function setCapacity($capacity){
        $this->capacity = $capacity;
    }

    public function getSpaceAvailable(){
        return $this->spaceAvailable;
    }
 
    public function setSpaceAvailable($spaceAvailable){
        $this->spaceAvailable = $spaceAvailable;
    }

    public function getArea(){
        return $this->area;
    }
 
    public function setArea($area){
        $this->area = $area;
    }

    public function getFloor(){
        return $this->floor;
    }
 
    public function setFloor($floor){
        $this->floor = $floor;
    }

    public function getBathroom(){
        return $this->bathroom;
    }
 
    public function setBathroom($bathroom){
        $this->bathroom = $bathroom;
    }

    public function getKitchen(){
        return $this->kitchen;
    }
 
    public function setKitchen($kitchen){
        $this->kitchen = $kitchen;
    }

    public function getHeating(){
        return $this->heating;
    }
 
    public function setHeating($heating){
        $this->heating = $heating;
    }


    public function getCharge(){
        return $this->charge;
    }
 
    public function setCharge($charge){
        $this->charge = $charge;
    }

    public function getRent(){
        return $this->rent;
    }
 
    public function setRent($rent){
        $this->rent = $rent;
    }

    public function getDeposit(){
        return $this->deposit;
    }
 
    public function setDeposit($deposit){
        $this->deposit = $deposit;
    }

    public function getrentalDuration(){
        return $this->rentalDuration;
    }
 
    public function setrentalDuration($rentalDuration){
        $this->rentalDuration = $rentalDuration;
    }

    public function getRentComment(){
        return $this->rentComment;
    }
 
    public function setRentComment($rentComment){
        $this->rentComment = $rentComment;
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

    public function getReference(){
        return $this->reference;
    }
 
    public function setReference($reference){
        $this->reference = $reference;
    }
}