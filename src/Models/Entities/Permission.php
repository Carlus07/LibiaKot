<?php
/**
 * @Entity @Table(name="permission")
 **/
class Permission
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idPermission;
    /** @Column(type="string", length=50) **/
    protected $url;
    /**
     * Une permission possède un rôle
     * @ManytoOne(targetEntity="Role")
     * @JoinColumn(name="idRole", referencedColumnName="idRole")
     */
    protected $idRole;
   

    public function getId(){
        return $this->idPermission;
    }
 
    public function getIdRole(){
        return $this->idRole;
    }
    
    public function setIdRole($idRole){
        $this->idRole = $idRole;
    }
}