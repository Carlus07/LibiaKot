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
                if ($user->getConfirmed() == 1)
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
                    $error = 'notConfirmed';
                    $this->render('user.login', compact('error', 'mail'));
                }
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
            $this->render('user.register', compact('user'));
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
                $redirection = Navi::getRedirection($translation, true, "http://libiakot-test.test.fundp.ac.be/mail.php?fn=".$user->getFirstName()."&l=".Session::get("Language")."&m=register&t=".$user->getToken());
                $contentMessage = Navi::getContentMail($translation, true, $user->getFirstName(), "register", "http://libiakot-test.test.fundp.ac.be/index.php?p=mail.confirmation&t=".$user->getToken()."&m=register");
                if (!Mail::sendMail($translation["subjectRegister"], $user->getMail(), $redirection, $contentMessage))
                {
                    $this->render('error.mail');
                }
                Router::redirect('mail.confirmation', 'register');
            }
        }
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
        if(isset($_GET['id']))
        {
            $value['id'] =  $_GET['id'];
            Session::set('settings', $value);
            $user = $this->getConnection()->getRepository('User')->find($_GET['id']);
        }
        else $user = $this->getConnection()->getRepository('User')->find(Session::get('idUser'));
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
        if ($fp != false)
        {
            //Ecriture dans le fichier
            fwrite( $fp, $unencodedData);
            fclose( $fp );

            $user = $this->getConnection()->getRepository('User')->find($_POST['id']);

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
        else echo "warning+errorUploadAvatar";
    }

    public function updateProfil()
    {
        $user = $this->getConnection()->getRepository('User')->find($_POST['id']);
        if (!empty($user)) 
        {
            $method = "set".ucfirst($_POST['field']);
            $user->$method($_POST['text']);
            $this->getConnection()->persist($user);
            $this->getConnection()->flush();
            echo "success+successUploadProfil";
        }
        else echo "warning+errorUploadProfil";
    }

    public function changePassword()
    {
        if (empty($_POST)) 
        {
            $this->render("user.changePassword");
        } 
        else 
        {
            $user = $this->getConnection()->getRepository('User')->find(Session::get('idUser')); 
            if (!empty($user)) 
            {
                $validation = new Validation;
                $validation->password($_POST['password'], $_POST['confirmPassword']);

                if ($validation->isErrors()) {
                    $errors = $validation->getErrors();
                }
                else
                {
                    if ($user->getPassword() == Security::cryptage($_POST['oldPassword']))
                    {
                        $user->setPassword(Security::cryptage($_POST['password']));
                        $this->getConnection()->persist($user);
                        $this->getConnection()->flush();
                    }
                    else $errors = "errorOldPasswordWrong";
                }
            }
            else $errors = "errorUpdatePassword";
            if (isset($errors)) $this->render('user.changePassword', compact('errors'));
            else $this->render("user.profile", compact('user'));
        }
    }
    public function reset()
    {
        if (empty($_POST)) 
        {
            $this->render("user.reset");
        } 
        else 
        {
            $user = $this->getConnection()->getRepository('User')->findByMail($_POST['mail']); 
            if (!empty($user[0])) 
            {
                $newPassword = Security::createPassword(12);
                $user[0]->setConfirmed(false);
                $user[0]->setTimeToken(new \DateTime("now"));
                $user[0]->setToken(Security::generateToken());
                $user[0]->setPassword(Security::cryptage($newPassword));

                $this->getConnection()->persist($user[0]);
                $this->getConnection()->flush();
                $translation = Language::translation("mail");
                $redirection = Navi::getRedirection($translation, true, "http://libiakot-test.test.fundp.ac.be/mail.php?fn=".$user[0]->getFirstName()."&l=".Session::get("Language")."&m=changepassword&t=".$user[0]->getToken().'&p='.Security::encrypt($newPassword));
                $contentMessage = Navi::getContentMail($translation, true, $user[0]->getFirstName(), "changepassword", "http://libiakot-test.test.fundp.ac.be/index.php?p=mail.confirmation&t=".$user[0]->getToken()."&m=changepassword", $newPassword);
                if (!Mail::sendMail($translation["subjectChangePassword"], $user[0]->getMail(), $redirection, $contentMessage))
                {
                    $this->render('error.mail');
                }
                Router::redirect('mail.confirmation', 'changepassword');
            }
            else
            {
                $this->render('error.index');
            }
        }
    }
    public function add()
    {
        if (empty($_POST)) 
        {
            $admin = true;
            $this->render("user.register", compact('admin'));
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
            if (!empty($_POST['mail'])) $validation->email($user->getMail());
            $validation->text($user->getStreet());
            $validation->number($user->getNumber());    
            $validation->text($user->getCity());
            $validation->number($user->getZipCode());
            $validation->phone($user->getPhone());
            $validation->phone($user->getSecondPhone(), true);

            if ($validation->isErrors()) {
                $errors = $validation->getErrors();
                $admin = true;
                $this->render('user.register', compact('user', 'admin', 'errors'));
            }
            else
            {
                $role = $this->getConnection()->getRepository('Role')->find(2);
                $user->setIdRole($role);
                $user->setNbConnection(0);
                $user->setConfirmed(true);
                $user->setTimeToken(new \DateTime("now"));
                $user->setIp(Session::get("ip"));
                $user->setToken(Security::generateToken());
                $newPassword = Security::createPassword(12);
                $user->setPassword(Security::cryptage($newPassword));
                $this->getConnection()->persist($user);
                $this->getConnection()->flush();
                $translation = Language::translation("mail");
                $redirection = Navi::getRedirection($translation, true, "http://libiakot-test.test.fundp.ac.be/mail.php?fn=".$user->getFirstName()."&l=".Session::get("Language")."&m=addUser&p=".Security::encrypt($newPassword));
                $contentMessage = Navi::getContentMail($translation, true, $user->getFirstName(), "addUser", "http://libiakot-test.test.fundp.ac.be/index.php?p=mail.confirmation&m=addUser", $newPassword);
                if (!Mail::sendMail($translation["subjectAddUser"], $user->getMail(), $redirection, $contentMessage))
                {
                    $this->render('error.mail');
                }
                Router::redirect('mail.confirmation', 'addUser');
            }
        }
    }
    public function infoUser()
    {
        $user = $this->getConnection()->getRepository('User')->find($_POST['id']);
        $result['picture'] = $user->getPicture();
        $result['mail'] = $user->getMail();
        $result['street'] = $user->getStreet();
        $result['number'] = $user->getNumber();
        $result['city'] = $user->getCity();
        $result['zipCode'] = $user->getZipCode();
        $result['phone'] = $user->getPhone();
        echo json_encode($result);
    }
    public function listUsers()
    {
        if (isset($_GET['r']) && (($_GET['r'] % 12) == 0))
        {
            $value['r'] = $_GET['r'];
            Session::set('settings', $value);
            $role = $this->getConnection()->getRepository('Role')->find(2);
            $users = $this->getConnection()->getRepository('User')->findByIdRole($role); 
            $size = sizeof($users);

            $offset = (isset($_GET['r'])) ? $_GET['r'] : 12;
            $limit = $offset - 12;
            $dql = "SELECT u FROM User u WHERE u.idRole = 2 ORDER BY u.name ASC";
            $query = $this->getConnection()->createQuery($dql)
                           ->setFirstResult($limit)
                           ->setMaxResults($offset);

            $users = $query->getResult();
            $this->render('user.list', compact('users', 'size'));
        }
        else
        {
            $this->render('error.index');
        }
    }
    public function delete()
    {
        $housingController = new housingController;
        $user = $this->getConnection()->getRepository('User')->find($_POST['idUser']);
        if (!empty($user)) 
        {
            $properties = $this->getConnection()->getRepository('Property')->findByIdUser($user->getId());
            foreach ($properties as $property) {
                $housingController->deleteProperty($property->getId());
            }
            $this->getConnection()->remove($user);
            $this->getConnection()->flush();
            echo "success+successDeleteUser";
        }
        else echo "warning+errorDelete";
    }
    public function sendMessage()
    {
        if(!empty($_POST['idOwner']))
        {
            $user = $this->getConnection()->getRepository('User')->find($_POST['idOwner']);
            $translation = Language::translation("mail");
            $translation['message'] = $_POST['message'];
            $redirection = Navi::getRedirection($translation, true, "http://libiakot-test.test.fundp.ac.be/mail.php?fn=".$user->getFirstName()."&l=".Session::get("Language")."&m=sendMail&message=".$_POST['message']);
            $contentMessage = Navi::getContentMail($translation, true, $user->getFirstName(), "sendMail", "http://libiakot-test.test.fundp.ac.be/index.php?p=mail.confirmation&m=sendMail");
            if (!Mail::sendMail($_POST['object'], $user->getMail(), $redirection, $contentMessage))
            {
                $this->render('error.mail');
            }
            Router::redirect('mail.confirmation', 'sendMail');
        }
        else
        {
            $this->render('error.index');
        }
    }
}