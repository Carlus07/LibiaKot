<?php
namespace Controllers;

use Controllers\Tools\Connection;
use Controllers\Tools\Router;
use Controllers\Tools\Language;
use Controllers\Tools\Navi;
use Models\Session;
/**
 * Class Controller
 * Controller de base de l'application.
 * @package Controllers
 */
class Controller
{
    private $viewPath = 'web/pages/';
    private $viewJs = 'web/js/modules/';

    /**
     * Affiche une le contenu d'une page en lui envoyant les données nécessaires.
     * @param $view Nom de la vue au format 'activité.vue'
     * @param array $variables Variables à envoyer à la vue.
     */
    public function render($view, $variables = [])
    {      
        //Division de la vue en partie
        $parts = explode('.', $view);
        $vp = ucfirst($parts[0]) . '/' . $parts[1];
        extract($variables);
        //Traduction de la vue + du contenu statique
        $translation = (!isset($parts[2])) ? Language::translation($parts[0]) : Language::translation($parts[0], $parts[2]);
        $languages = Language::getAll();

        //Ajout du contenu de la page dans la variable $content
        ob_start();
        require $this->viewPath . $vp . '.php';
        $content = ob_get_clean();

        //Vérifie si une page js est lié à la vue
        $jsPath = $this->viewJs . $vp . '.js';
        $js = '';
        if (file_exists($jsPath)) {
            $js = $jsPath;
        }

        //Si c'est un appel normal
        if (!Router::isAjaxCall()) {
            //Composant du template
            $title = $translation['titlePage'];
            $topBar = Navi::topBar($translation, true);
            $navTop = Navi::navTop($translation, true);
            $navBar = Navi::navBar($translation, true);
            $footer = Navi::footer($translation, true);
            $moduleJs = '<div id="module" data="'.$js.'"></div>';
            require 'web/template.php';
        } 
        //Si c'est un appel ajax
        else {
            $topBar = ' ';
            $navTop = ' ';
            $navBar = ' ';
            $footer = ' ';
            $title  = ' ';
            if (Router::isRefreshInterface()) {
                $title = $translation['titlePage'];
                $topBar = Navi::topBar($translation, true);
                $navTop = Navi::navTop($translation, true);
                $navBar = Navi::navBar($translation, true);
                $footer = Navi::footer($translation, true);
            }
            echo $content . '/-/' . $topBar . '/-/'. $navTop . '/-/'. $navBar . '/-/'. $footer . '/-/'. $js . '/-/'. $title;
        }
    }

    /**
     * Recupère l'object de connexion.
     * @return obj $entityManager
     */
    public function getConnection()
    {
        return Connection::getConnection();
    }
}