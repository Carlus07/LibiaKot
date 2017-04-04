<?php
/**
 * @Entity @Table(name="translation")
 **/
class Translation
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idTranslation;
    /** @Column(type="string") **/
    protected $libelle;
    /**
     * Des traductions sont référencées par un label.
     * @ManyToOne(targetEntity="Label")
     * @JoinColumn(name="idLabel", referencedColumnName="idLabel")
     */
    protected $idLabel;
    /**
     * Des traductions sont référencées par une langue.
     * @ManyToOne(targetEntity="Language")
     * @JoinColumn(name="idLanguage", referencedColumnName="idLanguage")
     */
    protected $idLanguage;

    public function getId(){
        return $this->idTranslation;
    }
 
    public function getLibelle(){
        return $this->libelle;
    }

    public function setLibelle($libelle){
        $this->libelle = $libelle;
    }

    public function getIdLabel(){
        return $this->idLabel;
    }
 
    public function setIdLabel($idLabel){
        $this->idLabel = $idLabel;
    }

    public function getIdLanguage(){
        return $this->idLanguage;
    }
 
    public function setIdLanguage($idLanguage){
        $this->idLanguage = $idLanguage;
    }
}