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
    /** @Column(type="string", length=200) **/
    protected $repertory;
    /** @Column(type="boolean") **/
    protected $priority;
    /** @Column(type="datetime") **/
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

    public function getRepertory(){
        return $this->repertory;
    }
 
    public function setRepertory($repertory){
        $this->repertory = $repertory;
    }

    public function getPriority(){
        return $this->priority;
    }
 
    public function setPriority($priority){
        $this->priority = $priority;
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