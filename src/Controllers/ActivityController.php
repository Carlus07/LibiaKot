<?php

namespace Controllers;

class ActivityController extends Controller {

    public function index() {
        $query = static::getConnection()->createQuery("
            SELECT a.name, l.label
            FROM Activity a JOIN a.idLabel l
            WHERE (l.label LIKE 'activity%')
        ");
        $langs = $this->getConnection()->getRepository('Language')->findBy(array(), array('order' => 'ASC'));
        $activities = $query->getResult();
        $this->render('activity.index', compact('activities', 'langs'));
    }
    public function displayTab()
    {
    	$query = static::getConnection()->createQuery("
            SELECT t,l.label
            FROM Translation t JOIN t.idLabel l JOIN l.idActivity a 
            WHERE (a.name = '".$_POST['activity']."')
        ");

    	$lang = $query->getResult();
    	$result = static::structuration($lang);
        echo json_encode($result);
    }
    private static function structuration($result)
    {
        $associativeArray = array();
        $label = "";
    	foreach($result as $key => $value) 
	  	{  
            $idLabel = (!$value[0]->getIdLabel()->isLinked()) ? "NR+".$value[0]->getIdLabel()->getId() : $value[0]->getIdLabel()->getId()."+";
            $idTranslation = $value[0]->getId();
            $langue = $value[0]->getIdLanguage()->getId();
            
            if ($label != $value[0]->getIdLabel()->getLabel())
            {
                $label = $value[0]->getIdLabel()->getLabel();
                $associativeArray[$idLabel][$label] = array();
            }
            $associativeArray[$idLabel][$label][$idTranslation][$langue] = utf8_encode($value[0]->getLibelle());
	  	}
	  	return $associativeArray;
    }
}
