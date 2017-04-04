<?php
/**
 * @Entity @Table(name="activity")
 **/
class Activity
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idActivity;
    /** @Column(type="string", length=20) **/
    protected $name;
    /**
     * Une activité possède un label.
     * @OneToOne(targetEntity="Label")
     * @JoinColumn(name="idLabel", referencedColumnName="idLabel")
     */
    protected $idLabel;
   

    public function getId(){
        return $this->idActivity;
    }
 
    public function getIdLabel(){
        return $this->idLabel;
    }
    
    public function setIdLabel($idLabel){
        $this->idLabel = $idLabel;
    }

    public function getName()
    {
        return $this->name;
    }
}