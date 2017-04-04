<?php
/**
 * @Entity @Table(name="role")
 **/
class Role
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idRole;
    /** @Column(type="string") **/
    protected $description;

    public function getId(){
        return $this->idRole;
    }
    public function getDescription(){
        return $this->description;
    }
    public function setDescription($description){
        $this->description = $description;
    }
}