<?php

namespace Controllers;
use \Translation;

class TranslationController extends Controller {

    public function update() {

        $translation = $this->getConnection()->getRepository('Translation')->find($_POST['idTranslation']);
        if (!empty($translation)) 
        {
        	
           	$translation->setLibelle(utf8_decode($_POST['text']));
           	$this->getConnection()->flush();
            echo "success+successUpdateTranslation";
        }
        else
        {
        	echo "warning+errorUpdateTranslation";
        }
    }
    public function add()
    {
        $language = $this->getConnection()->getRepository('Language')->findBy(array(), array('order' => 'ASC'));
        $label = $this->getConnection()->getRepository('Label')->find($_POST['idLabel']);

        $translation = new Translation;
        $translation->setLibelle(utf8_decode($_POST['text']));
        $translation->setIdLabel($label);
        $translation->setIdLanguage($language[$_POST['order']]);
        $this->getConnection()->persist($translation);
        $this->getConnection()->flush();

        echo (!empty($translation)) ? "success+successUpdateTranslation" : "warning+errorUpdateTranslation";
    }
}
