<?php
namespace Controllers\Tools;

use Models\Session;

/**
 * Class Language
 * Permet de gérer les traductions d'une page.
 * @package Controllers\Tools
 */
class Language
{
    private $entityManager;
	/**
     * Recupère l'ensemble des traductions pour une page selon la langue.
     * @param string $activity Activité de la page.
     * @return array $results
     */
    public static function translation($activity, $activityAssociated = null)
    {
    	$query = static::getConnection()->createQuery("
    		SELECT t.libelle, l.label 
    		FROM Translation t JOIN t.idLabel l JOIN l.idActivity a 
    		WHERE ((a.name = '".$activity."' OR a.name = '".$activityAssociated."' OR a.name = 'main') AND t.idLanguage = '".static::getLanguage()."')
    	");
    	$lang = $query->getResult();
    	$results = static::structuration($lang);
        return $results;
    }

    /**
     * Crée un tableau associatif "libellé" => "traduction"
     * @param array $result Traduction brute sous le forme de tableau à double dimension.
     * @return array $associativeArray
     */
    private static function structuration($result)
    {
    	$associativeArray = array();
    	foreach($result as $value) 
	  	{  
	    	$associativeArray[$value['label']] = utf8_encode($value['libelle']);
	  	}
	  	return $associativeArray;
    }

	/**
     * Recupère la langue de la session
     * @return int $lang
     */
    public static function getLanguage()
    {
    	return Session::get("Language");
    }

    /**
     * Modifie le langage dans la Session
     * @param string $lang
     */
    public static function setLanguage($lang)
    {
        $langRepo = static::getConnection()->getRepository('Language');
        $lang = $langRepo->find($lang);
        $lang = (!empty($lang)) ? $lang->getId() : "en";
        Session::set('Language', $lang);
    }

    /**
     * Recupère la langue courante du navigateur
     * @return int $lang
     */
    public static function getLanguageDefault()
    {
    	if (!Session::exist('Language')) {
		    $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		    $langRepo = static::getConnection()->getRepository('Language');
		    $lang = $langRepo->find($language);
		    $lang = (!empty($lang)) ? $lang->getId() : "en";
		    Session::set('Language', $lang);
		}
    	return Session::get("Language");
    }

    /**
     * Recupère l'ensemble de la table Language
     * @return array $langs
     */
    public static function getAll()
    {
        $langRepo = static::getConnection()->getRepository('Language');
        $langs = $langRepo->findAll();
        return $langs;
    }

    /**
     * Traduit un label en fonction de son id et de la langue de session
     * @return string $result
     */
    public static function getLabelTranslation($idLabel)
    {
        $langRepo = static::getConnection()->getRepository('Translation');
        $result = $langRepo->findBy(array("idLabel" => $idLabel, "idLanguage" => static::getLanguage()));
        return utf8_encode($result[0]->getLibelle());
    }

    /**
     * Recupère l'objet de connexion
     * @return obj $entityManager
     */
    private static function getConnection()
    {
        if (empty($entityManager)) $entityManager = Connection::getConnection();
        return $entityManager;
    }
}