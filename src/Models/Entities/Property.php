<?php
/**
 * @Entity @Table(name="property")
 **/
class Property
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idProperty;
    /** @Column(type="boolean") **/
    protected $gender;
    /** @Column(type="float") **/
    protected $bail;
    /** @Column(type="string", length=5) **/
    protected $number;
    /** @Column(type="boolean") **/
    protected $bicycleParking;
    /** @Column(type="boolean") **/
    protected $carParking;
    /** @Column(type="boolean") **/
    protected $private;
    /** @Column(type="boolean") **/
    protected $internet;
    /** @Column(type="boolean") **/
    protected $animal;
    /** @Column(type="text", nullable=true) **/
    protected $facility;
    /** @Column(type="text", nullable=true) **/
    protected $comment;
    /** @Column(type="integer") **/
    protected $state;
    /**
     * Une propriété à des propriétaires
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="idUser", referencedColumnName="idUser")
    */
    protected $idUser;
    /**
     * Une propriété possède une adresse
     * @OneToOne(targetEntity="Address")
     * @JoinColumn(name="idAddress", referencedColumnName="idAddress")
     */
    protected $idAddress;

    public function getId(){
        return $this->idProperty;
    }
 
    public function getGender(){
        return $this->gender;
    }
 
    public function setGender($gender){
        $this->gender = $gender;
    }

    public function getBail(){
        return $this->bail;
    }
 
    public function setBail($bail){
        $this->bail = $bail;
    }

    public function getNumber(){
        return $this->number;
    }
 
    public function setNumber($number){
        $this->number = $number;
    }

    public function getBicycleParking(){
        return $this->bicycleParking;
    }
 
    public function setBicycleParking($bicycleParking){
        $this->bicycleParking = $bicycleParking;
    }

    public function getCarParking($carParking){
        return $this->carParking;
    }
 
    public function setCarParking($carParking){
        $this->carParking = $carParking;
    }

    public function getPrivate(){
        return $this->private;
    }
 
    public function setPrivate($private){
        $this->private = $private;
    }

    public function getInternet(){
        return $this->internet;
    }
 
    public function setInternet($internet){
        $this->internet = $internet;
    }

    public function getAnimal(){
        return $this->animal;
    }
 
    public function setAnimal($animal){
        $this->animal = $animal;
    }

    public function getFacility(){
        return $this->facility;
    }
 
    public function setFacility($facility){
        $this->facility = $facility;
    }

    public function getComment(){
        return $this->comment;
    }
 
    public function setComment($comment){
        $this->comment = $comment;
    }

    public function getState(){
        return $this->state;
    }
 
    public function setState($state){
        $this->state = $state;
    }

    public function getIdUser(){
        return $this->idUser;
    }
 
    public function setIdUser($idUser){
        $this->idUser = $idUser;
    }

    public function getIdAdress(){
        return $this->idAddress;
    }
 
    public function setIdAddress($idAddress){
        $this->idAddress = $idAddress;
    }
}