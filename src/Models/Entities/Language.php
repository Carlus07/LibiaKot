<?php
/**
 * @Entity @Table(name="language")
 **/
class Language
{
    /** @Id @Column(type="string") **/
    protected $idLanguage;
    /** @Column(type="string") **/
    protected $name;
    /** @Column(type="string") **/
    protected $flag;
    /** @Column(type="integer") 
        @GeneratedValue **/
    protected $order;

    public function getId(){
        return $this->idLanguage;
    }
 
    public function getName(){
        return $this->name;
    }
 
    public function setName($name){
        $this->name = $name;
    }

    public function getFlag(){
        return $this->flag;
    }
 
    public function setFlag($flag){
        $this->flag = $flag;
    }

    public function getOrder(){
        return $this->order;
    }
 
    public function setOrder($order){
        $this->order = $order;
    }
}