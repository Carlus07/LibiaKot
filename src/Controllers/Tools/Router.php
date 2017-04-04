<?php

namespace Controllers\Tools;

use Models\Session;

/**
 * Class Router
 * Gère le routage des requètes entrantes.
 * @package Controllers
 */
class Router
{
    private static $activity;
    private static $controller_path;
    private static $method_name;
    private static $typeAddress = "";
    private static $ajaxCall = false;
    private static $refreshInterface = false;

    /**
     * Initialise la requète.
     */
    private static function init()
    {
        if (!Permission::exist()) {
            Permission::refresh(Session::get('idUser'));
        }

        if (isset($_POST['ajaxCall'])) {
            static::setAjaxCall(true);
            unset($_POST['ajaxCall']);
        }
    }

    /**
     * Récupère les informations venant de l'adresse.
     * @param $address Adresse de la requète sous forme 'controller.method'.
     */
    private static function prepareAddress($address)
    {
        if ((static::getTypeAddress() == "p") || (static::getTypeAddress() == "h"))
        {
            $currentPages = Session::get('currentPage');
            if ($currentPages == false) Session::push('currentPage', $address);
            else if (end($currentPages) != $address) 
            {
                Session::push('currentPage', $address);
            }
        }
        $parts = explode('.', $address);
        static::$activity = $parts[0];
        static::$controller_path = ($parts[0] != "validation") ? "\Controllers\\" . ucfirst($parts[0]) . "Controller" : "\Controllers\\Tools\\Validation";

        if (isset($parts[1])) {
            static::$method_name = $parts[1];
        } else {
            static::$method_name = 'index';
        }
    }

    /**
     * Tente de changer de vue ou du moins de lancer l'action demandée. Pour se faire, on teste l'existance de celle-ci.
     */
    private static function trySwitch()
    {
        $error = false;
        if (!class_exists(static::$controller_path)) {
            $error = true;
        } 
        else {
            $controller = new static::$controller_path();
            if (!method_exists($controller, static::$method_name)) {
                $error = true;
            }
        }
        if ($error) {
            static::$controller_path = "\Controllers\\ErrorController";
            $controller = new static::$controller_path();
            $controller->index();
        } else {
            $controller->{static::$method_name}();
        }
    }

    /**
     * Redirige vers une adresse et supprime les variables d'environnement.
     * @param string $adress Adresse de redirection.
     */
    public static function redirect($address = 'home.index', $type = "h")
    {
        unset($_GET);
        unset($_POST);
        static::switchView($address);
    }

    /**
     * Change de vue.
     * @param string $adress Adresse à aller chercher.
     */
    public static function switchView($address = 'home.index', $type = "h")
    {
        static::init();
        static::setTypeAddress($type);
        static::prepareAddress($address);
        static::trySwitch();
        //NE RIEN PLACER ICI à cause du redirect!
    }

    /**
     * Modifie la variable ajaxCall.
     * @param bool $value.
     */
    public static function setAjaxCall($value)
    {
        static::$ajaxCall = $value;
    }

    /**
     * Teste si l'action viens d'Ajax.
     * @return bool True si la requète viens d'Ajax.
     */
    public static function isAjaxCall()
    {
        if (static::$ajaxCall) {
            static::setAjaxCall(false);
            return true;
        }
        return false;
    }

    /**
     * Modifie la variable refreshInterface.
     * @param bool $value.
     */
    public static function setRefreshInterface($value)
    {
        static::$refreshInterface = $value;
    }

    /**
     * Teste si il faut rafraichir l'ensemble de l'interface.
     * @return bool True si il faut rafraichir l'ensemble de l'interface.
     */
    public static function isRefreshInterface()
    {
        if (static::$refreshInterface) {
            static::setRefreshInterface(false);
            return true;
        }
        return false;
    }

    /**
     * Modifie la variable typeAddress.
     * @param bool $value.
     */
    public static function setTypeAddress($type)
    {
        static::$typeAddress = $type;
    }

    public static function getTypeAddress()
    {
        return static::$typeAddress;
    }
}
