<?php
/**
 * @Entity @Table(name="property")
 **/
class Property
{
    /** @Id @Column(type="integer")
        @GeneratedValue **/
    protected $idProperty;
    /** @Column(type="integer") **/
    protected $zipCode;
    /** @Column(type="text") **/
    protected $city;
    /** @Column(type="text") **/
    protected $street;
    /** @Column(type="integer") **/
    protected $number;
    /** @Column(type="text", nullable=true) **/
    protected $GPSPosition;
    /** @Column(type="text", nullable=true) **/
    protected $easeNearby;
     /** @Column(type="integer") **/
    protected $domiciliation;
     /** @Column(type="integer") **/
    protected $targetAudience;
     /** @Column(type="boolean") **/
    protected $garden;
     /** @Column(type="boolean") **/
    protected $terrace;
    /** @Column(type="boolean") **/
    protected $bicycleParking;
    /** @Column(type="boolean") **/
    protected $carParking;
     /** @Column(type="boolean") **/
    protected $disabledAccess;
    /** @Column(type="boolean") **/
    protected $smokerAllowded;
    /** @Column(type="boolean") **/
    protected $realizedPEB;
    /** @Column(type="boolean") **/
    protected $animalAllowded;
    /** @Column(type="integer") **/
    protected $state;
    /**
     * Une propriété à des propriétaires
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="idUser", referencedColumnName="idUser")
    */
    protected $idUser;

    public function getId(){
        return $this->idProperty;
    }

    public function getZipCode(){
        return $this->zipCode;
    }

    public function setZipCode($zipCode){
        $this->zipCode = $zipCode;
    }

    public function getCity(){
        return $this->city;
    }

    public function setCity($city){
        $this->city = $city;
    }

    public function getStreet(){
        return $this->street;
    }

    public function setStreet($street){
        $this->street = $street;
    }

    public function getNumber(){
        return $this->number;
    }

    public function setNumber($number){
        $this->number = $number;
    }

    public function getGPSPosition(){
        return $this->GPSPosition;
    }

    public function setGPSPosition($GPSPosition){
        $this->GPSPosition = $GPSPosition;
    }

    public function getEaseNearby(){
        return $this->easeNearby;
    }

    public function setEaseNearby($easeNearby){
        $this->easeNearby = $easeNearby;
    }

    public function getDomiciliation(){
        return $this->domiciliation;
    }

    public function setDomiciliation($domiciliation){
        $this->domiciliation = $domiciliation;
    }

    public function getTargetAudience(){
        return $this->targetAudience;
    }

    public function setTargetAudience($targetAudience){
        $this->targetAudience = $targetAudience;
    }

    public function getGarden(){
        return $this->garden;
    }

    public function setGarden($garden){
        $this->garden = $garden;
    }

    public function getTerrace(){
        return $this->terrace;
    }

    public function setTerrace($terrace){
        $this->terrace = $terrace;
    }

    public function getBicycleParking(){
        return $this->bicycleParking;
    }

    public function setBicycleParking($bicycleParking){
        $this->bicycleParking = $bicycleParking;
    }

    public function getCarParking(){
        return $this->carParking;
    }

    public function setCarParking($carParking){
        $this->carParking = $carParking;
    }

    public function getDisabledAccess(){
        return $this->disabledAccess;
    }

    public function setDisabledAccess($disabledAccess){
        $this->disabledAccess = $disabledAccess;
    }

    public function getSmoker(){
        return $this->smokerAllowded;
    }

    public function setSmoker($smokerAllowded){
        $this->smokerAllowded = $smokerAllowded;
    }

    public function getPEB(){
        return $this->realizedPEB;
    }

    public function setPEB($realizedPEB){
        $this->realizedPEB = $realizedPEB;
    }

    public function getAnimal(){
        return $this->animalAllowded;
    }

    public function setAnimal($animalAllowded){
        $this->animalAllowded = $animalAllowded;
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
}
