<?php
/**
 * @Entity @Table(name="picture")
 **/
class Picture
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idPicture;
    /** @Column(type="string", length=100) **/
    protected $name;
    /** @Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"}) **/
    protected $dateUpload;
    /**
     * Plusieurs images sont référencés par un logement
     * @ManyToOne(targetEntity="Housing")
     * @JoinColumn(name="idHousing", referencedColumnName="idHousing")
    */
    protected $idHousing;

    public function getId(){
        return $this->idLocality;
    }
 
    public function getName(){
        return $this->name;
    }
 
    public function setName($name){
        $this->name = $name;
    }

    public function getDateUpload(){
        return $this->dateUpload;
    }
 
    public function setDateUpload($dateUpload){
        $this->dateUpload = $dateUpload;
    }

    public function getIdHousing(){
        return $this->idHousing;
    }
 
    public function setIdHousing($idHousing){
        $this->idHousing = $idHousing;
    }
}