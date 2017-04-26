<?php
/**
 * @Entity @Table(name="user")
 **/
class User
{
    /** @Id @Column(type="integer") 
        @GeneratedValue **/
    protected $idUser;
    /** @Column(type="string", length=100) **/
    protected $name;
    /** @Column(type="string", length=100) **/
    protected $firstName;
    /** @Column(type="boolean") **/
    protected $gender;
    /** @Column(type="string", length=200) **/
    protected $mail;
    /** @Column(type="string") **/
    protected $password;
    /** @Column(type="string", length=15) **/
    protected $phone;
    /** @Column(type="string", nullable=true, length=15) **/
    protected $secondPhone;
    /** @Column(type="string", length=150) **/
    protected $street;
    /** @Column(type="integer") **/
    protected $number;
    /** @Column(type="string", length=50) **/
    protected $city;
    /** @Column(type="integer") **/
    protected $zipCode;
    /** @Column(type="string", nullable=true) **/
    protected $picture;
    /**
     * Un utilisateur possède un role
     * @ManyToOne(targetEntity="Role")
     * @JoinColumn(name="idRole", referencedColumnName="idRole")
     */
    protected $idRole;
    /** @Column(type="string") **/
    protected $token;
    /** @Column(type="integer", options={"default" : 0}) **/
    protected $nbConnection;
    /** @Column(type="boolean", options={"default" : 0}) **/
    protected $confirmed;
    /** @Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"}) **/
    protected $timeToken;
    /** @Column(type="string", length=15) **/
    protected $ip;

    public function getId(){
        return $this->idUser;
    }
    
    public function setId($idUser){
        $this->idUser = $idUser;
    }

    public function getName(){
        return $this->name;
    }
 
    public function setName($name){
        $this->name = $name;
    }

    public function getFirstName(){
        return $this->firstName;
    }
 
    public function setFirstName($firstName){
        $this->firstName = $firstName;
    }

    public function getGender(){
        return $this->gender;
    }

    public function setGender($gender){
        $this->gender = $gender;
    }

    public function getMail(){
        return $this->mail;
    }
 
    public function setMail($mail){
        $this->mail = $mail;
    }

    public function getPassword(){
        return $this->password;
    }
 
    public function setPassword($password){
        $this->password = $password;
    }

    public function getPhone(){
        return $this->phone;
    }
 
    public function setPhone($phone){
        $this->phone = $phone;
    }

    public function getSecondPhone(){
        return $this->secondPhone;
    }
 
    public function setSecondPhone($secondPhone){
        $this->secondPhone = $secondPhone;
    }

    public function getNumber(){
        return $this->number;
    }
 
    public function setNumber($number){
        $this->number = $number;
    }

    public function getPicture(){
        return $this->picture;
    }
 
    public function setPicture($picture){
        $this->picture = $picture;
    }

    public function getidRole(){
        return $this->idRole;
    }
 
    public function setidRole($idRole){
        $this->idRole = $idRole;
    }

    public function getStreet(){
        return $this->street;
    }

    public function setStreet($street){
        $this->street = $street;
    }

    public function getZipCode(){
        return $this->zipCode;
    }
 
    public function setZipCode($zipCode){
        $this->zipCode = $zipCode;
    }

    public function getCity(){
        return $this->city;
    }
 
    public function setCity($city){
        $this->city = $city;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getTimeToken()
    {
        return $this->timeToken;
    }

    public function setTimeToken($timeToken)
    {
        $this->timeToken = $timeToken;
    }

    public function getConfirmed()
    {
        return $this->confirmed;
    }

    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function getNbConnection()
    {
        return $this->nbConnection;
    }

    public function setNbConnection($nbConnection)
    {
        $this->nbConnection = $nbConnection;
    }
}