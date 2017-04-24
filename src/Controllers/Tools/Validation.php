<?php
namespace Controllers\Tools;

use Models\Session;
use Models\Entity\User;

/**
 * Class Validation
 * Gère la validation de données
 * @package Core\Controllers\Tools
 */
class Validation
{
    private $errors = "";

    /**
     * Test la conformité d'un email
     * @param $email
     * @param int $unique Si a true teste si il est présent dans la BD
     */
    public function email($email, $unique = 1)
    {
        if (!$email) {
            $this->setErr('textEmpty');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setErr('textNotMail');
        } elseif ($unique) {
            if ($this->checkMailAvailable($email)) {
                $this->setErr('mailNotAvailable');
            }
        }
    }

    /**
     * Test la conformité d'un mot de passe
     * @param $password
     * @param $password_confirm
     */
    public function password($password, $password_confirm)
    {
        if (!$password) {
            $this->setErr('textEmpty');
        } elseif (!$password_confirm) {
            $this->setErr('textEmpty');
        } elseif (!preg_match('/^[a-zA-Z0-9_-]{6,16}$/', $password)) {
            $this->setErr('passwordNotValid');
        } elseif ($password != $password_confirm) {
            $this->setErr('passwordCheck');
        }
    }

    /**
     * Test la conformité d'un text
     * @param $phone
     * @param bool $empty Si à true teste le fait d'être vide
     */
    public function phone($phone, $empty = false)
    {
        if (!$empty) {
            if (!$phone) {
                $this->setErr('textEmpty');
            }
            else if (!preg_match('#^0[1-68]([-. ]?[0-9]{2}){4}$#', $phone)){
                $this->setErr('textNotPhone');
            }
        }
    }

    /**
     * Test la conformité d'un text
     * @param $text
     * @param $size
     * @param bool $empty Si à true teste le fait d'être vide
     */
    public function text($text, $size = 255, $empty = false)
    {
        if (strlen($text) != 0)
        {
            if (strlen($text) > $size) {
                $this->setErr('textTooLong');
            } elseif (!preg_match('/^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ._\s-]{3,255}$/', $text)) {
                $this->setErr('textNotString');
            } elseif (!$empty) {
                if (!$text) {
                    $this->setErr('textEmpty');
                }
            }
        }
    }

    /**
     * Test la conformité d'un chiffre
     * @param $int
     * @param $size
     * @param bool $empty Si à true teste le fait d'être vide
     */
    public function number($int, $size = 99999, $empty = false)
    {
        $int = intval($int);
        if (strlen($int) > $size) {
            $this->setErr('numberTooLong');
        } elseif (!$empty) {
            if (!is_int($int) && $int != "") {
                $this->setErr('numberNotInteger');
            }
        }
    }

    /**
     * Enregistre une erreur
     * @param $txt Erreur
     */
    private function setErr($txt)
    {
        $this->errors = $this->errors.'+'.$txt;
    }

    /**
     * Teste si des erreurs sont enregistrées
     * @return bool true si erreur
     */
    public function isErrors()
    {
        return ($this->errors != "");
    }

    /**
     * Envoi les erreurs vers l'affichage de la vue
     */
    public function getErrors()
    {
        $result = substr($this->errors, 1);
        $this->errors = "";
        return $result; 
    }

    /**
     * Test la disponibilité d'un email
     * @param $email
     * @return true en cas de non-disponibilité
     */
    public function checkMailAvailable($email = null) {
        $mail = ($email != null) ? $email : $_POST['email'];
        $userRepo = Connection::getConnection()->getRepository('User');
        $user = $userRepo->findByMail($mail);
        if (empty($_POST['email']) && !empty($user)) return true;
        else if (!empty($_POST['email']) && !empty($user)) echo "mailNotAvailable";
        else return false;
    }
}
