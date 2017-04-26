<?php
/**
 * @Entity @Table(name="label")
 **/
class Label
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idLabel;
    /** @Column(type="string") **/
    protected $label;
    /**
     * Des labels sont référencés par une activité
     * @ManyToOne(targetEntity="Activity")
     * @JoinColumn(name="idActivity", referencedColumnName="idActivity")
     */
    protected $idActivity;
    /** @Column(type="boolean") **/
    protected $linked;

    public function getId(){
        return $this->idLabel;
    }
 
    public function getLabel(){
        return $this->label;
    }
 
    public function setLabel($label){
        $this->label = $label;
    }

    public function getIdActivity(){
        return $this->idActivity;
    }
 
    public function setIdActivity($idActivity){
        $this->idActivity = $idActivity;
    }

    public function isLinked(){
        return ($this->linked == true) ? true : false;
    }
 
    public function setLinked($linked){
        $this->linked = $linked;
    }
}