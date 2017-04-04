<?php
/**
 * @Entity @Table(name="locality")
 **/
class Locality
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idLocality;
    /** @Column(type="integer") **/
    protected $zipCode;
    /** @Column(type="string", length=100) **/
    protected $nameCity;
   

    public function getId(){
        return $this->idLocality;
    }
 
    public function getZipCode(){
        return $this->zipCode;
    }
 
    public function setZipCode($zipCode){
        $this->zipCode = $zipCode;
    }

    public function getNameCity(){
        return $this->nameCity;
    }
 
    public function setNameCity($nameCity){
        $this->nameCity = $nameCity;
    }
}