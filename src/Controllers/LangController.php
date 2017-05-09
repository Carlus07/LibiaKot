<?php

namespace Controllers;

use Controllers\Tools\Router;
use Controllers\Tools\Language;
use Models\Session;

class LangController extends Controller 
{
    public function change() {
        if (isset($_GET['l'])) {
            Language::setLanguage($_GET['l']);
        }
        Router::setRefreshInterface(true);
        $currentPages = Session::get('currentPage');
        Router::redirect(end($currentPages));
    }

    public function getTraductionByLabel($l = null) {
        $label = (isset($_POST['label'])) ? $_POST["label"] : $l;
        if (!empty($label))
        {
            $query = $this->getConnection()->createQuery("
                SELECT t.libelle
                FROM Translation t JOIN t.idLabel l 
                WHERE t.idLanguage = '".Language::getLanguage()."' AND l.label = '".$label."'
            ");
            $result = $query->getResult();
            if (empty($l)) echo(utf8_encode($result[0]['libelle']));
            else return utf8_encode($result[0]['libelle']);
        }
    	else echo '';
    }
}
