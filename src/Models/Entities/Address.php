<?php
/**
 * @Entity @Table(name="address")
 **/
class Address
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idAddress;
    /** @Column(type="string") **/
    protected $street;
    /**
     * Plusieurs rues sont attribuées à une ville
     * @ManyToOne(targetEntity="Locality")
     * @JoinColumn(name="idLocality", referencedColumnName="idLocality")
    */
    protected $idLocality;
   

    public function getId(){
        return $this->idAddress;
    }
 
    public function getStreet(){
        return $this->street;
    }
 
    public function setStreet($street){
        $this->street = $street;
    }

    public function getIdLocality(){
        return $this->idLocality;
    }
 
    public function setIdLocality($idLocality){
        $this->idLocality = $idLocality;
    }
}