<?php

namespace Controllers;
use Controllers\Tools\Router;
use Controllers\Tools\Mail;
use Controllers\Tools\Navi;
use Controllers\Tools\Language;
use Controllers\Tools\Security;
use Controllers\Tools\Validation;
use Models\Session;
use \User;

class UserController extends Controller {

    public function login() {
    	if (isset($_POST['mail']) && isset($_POST['password'])) 
    	{
            $mail = htmlspecialchars($_POST['mail']);
            $user = $this->getConnection()->getRepository('User')->findOneBy(array('mail' => $mail, 'password' => Security::cryptage($_POST['password'])));
            if (!empty($user)) 
            {
                $user->setNbConnection($user->getNbConnection()+1);
                $this->getConnection()->flush();
                Session::set('idUser', $user->getId());
                Session::set('Role', $user->getIdRole()->getId());
                Session::set('FirstNameUser', $user->getFirstName());
                Router::setRefreshInterface(true);
                $this->render('home.index');
            }
            else 
            {
                $error = 'connectionNotValid';
                $this->render('user.login', compact('error', 'mail'));
            }
    	}
    	else
    	{
            if (Session::get("Role") != 1) $this->render('home.index');
    		else $this->render('user.login');
    	}
    }

    public function register() {
    	if (empty($_POST)) 
        {
            if (Session::get("Role") != 1) $this->render('home.index');
            else $this->render('user.register');
        } 
        else 
        {
            $user = new User;
            $validation = new Validation;

            $user->setName(htmlspecialchars($_POST['lastName']));
            $user->setFirstName(htmlspecialchars($_POST['firstName']));
            $user->setGender(htmlspecialchars($_POST['gender']));
            $user->setMail(htmlspecialchars($_POST['mail']));
            $user->setStreet(htmlspecialchars($_POST['street']));
            $user->setNumber(intval(htmlspecialchars($_POST['number'])));
            $user->setCity(htmlspecialchars($_POST['city']));
            $user->setZipCode(intval(htmlspecialchars($_POST['zipCode'])));
            $user->setPhone(htmlspecialchars($_POST['phone']));
            $user->setSecondPhone(htmlspecialchars($_POST['secondPhone']));

            $validation->text($user->getName());
            $validation->text($user->getFirstName());
            $validation->email($user->getMail());
            $validation->password($_POST['password'], $_POST['confirmPassword']);
            $validation->text($user->getStreet());
            $validation->number($user->getNumber());    
            $validation->text($user->getCity());
            $validation->number($user->getZipCode());
            $validation->phone($user->getPhone());
            $validation->phone($user->getSecondPhone(), true);

            if ($validation->isErrors()) {
                $errors = $validation->getErrors();
                $this->render('user.register', compact('user', 'errors'));
            }
            else
            {
                $role = $this->getConnection()->getRepository('Role')->find(2);
                $user->setIdRole($role);
                $user->setNbConnection(0);
                $user->setConfirmed(false);
                $user->setTimeToken(new \DateTime("now"));
                $user->setIp(Session::get("ip"));
                $user->setToken(Security::generateToken());
                $user->setPassword(Security::cryptage($_POST['password']));
                $this->getConnection()->persist($user);
                $this->getConnection()->flush();
                $translation = Language::translation("mail");
                $redirection = Navi::getRedirection($translation, true, "http://localhost/Projet/mail.php?fn=".$user->getFirstName()."&l=".Session::get("Language")."&m=register&t=".$user->getToken());
                $contentMessage = Navi::getContentMail($translation, true, $user->getFirstName(), "register", "http://localhost/Projet/index.php?p=user.confirmation&t=".$user->getToken());
                Mail::sendMail($translation["subjectRegister"], $user->getMail(), $redirection, $contentMessage);
                Router::redirect('user.confirmation');
            }
        }
    }

    public function confirmation() {
        $finalization = 0;
        if (isset($_GET['t']))
        {
            $user = $this->getConnection()->getRepository('User')->findByToken($_GET['t']);
            if (!empty($user)) 
            {
                $user[0]->setConfirmed(true);
                $this->getConnection()->flush();
                $finalization = 1;
            }
        }
        $this->render('user.confirmation', compact("finalization"));
    }

    public function logout() {
        Session::set('idUser', 0);
        Session::set('Role', 1);
        Session::set('FirstNameUser', "");
        $pageIndex = Session::get("pageIndex");
        Router::setRefreshInterface(true);
        $this->render("home.index");
    }

    public function profile()
    {
        $user = $this->getConnection()->getRepository('User')->find(Session::get('idUser'));
        $this->render("user.profile", compact('user'));
    }

    public function addAvatar()
    {
        $imageData = $_POST['file'];
        $ext = $_POST['extension'];
        $nameFinal = $_POST['name'].'.'.$ext;
        $filteredData = substr($imageData, strpos($imageData, ",")+1);
        $repertory = "web/pictures/Avatar/";
        //Décodage du base64
        $unencodedData = base64_decode($filteredData);
        //Renommage de l'image avec un nom unique
        $fp = fopen($repertory.$nameFinal, 'wb' );
        //Ecriture dans le fichier
        fwrite( $fp, $unencodedData);
        fclose( $fp );

        $user = $this->getConnection()->getRepository('User')->find(Session::get('idUser'));
        
        if (!empty($user)) 
        {
            if ($user->getPicture() != null) unlink($user->getPicture());
            $user->setPicture($repertory.$nameFinal);
            $this->getConnection()->persist($user);
            $this->getConnection()->flush();
            echo "success+successUploadAvatar";
        }
        else echo "warning+errorUploadAvatar";
    }
}
