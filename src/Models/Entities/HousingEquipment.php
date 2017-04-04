<?php
/**
 * @Entity @Table(name="housingEquipment")
 **/
class HousingEquipment
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idHousingEquipment;
    /**
     * Plusieurs lignes référencent vers un logement
     * @ManyToOne(targetEntity="Housing")
     * @JoinColumn(name="idHousing", referencedColumnName="idHousing")
    */
    protected $idHousing;
    /**
     * Plusieurs lignes référencent vers un equipement
     * @ManyToOne(targetEntity="Equipment")
     * @JoinColumn(name="idEquipment", referencedColumnName="idEquipment")
    */
    protected $idEquipment;
   

    public function getId(){
        return $this->idHousingEquipmentu;
    }
 
    public function getIdHousing(){
        return $this->idHousing;
    }
 
    public function setIdHousing($idHousing){
        $this->idHousing = $idHousing;
    }

    public function getIdEquipment(){
        return $this->idEquipment;
    }
 
    public function setIdEquipment($idEquipment){
        $this->idEquipment = $idEquipment;
    }
}