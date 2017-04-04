<?php

namespace Controllers;
use \Label;
use \Translation;
use \Language;
use \Activity;


class LabelController extends Controller {

    public function update() {

        $label = $this->getConnection()->getRepository('Label')->find($_POST['idLabel']);
        if (!empty($label)) 
        {
        	
           	$label->setLabel(utf8_decode($_POST['text']));
           	$this->getConnection()->flush();
            echo "success+successUpdateLabel";
        }
        else
        {
        	echo "warning+errorUpdate";
        }
    }
    public function delete() 
    {
        $errorDelete = false;
        $idTranslationArray = $_POST['idTranslations'];
        foreach($idTranslationArray as $idTranslation)
        {
            $translation = $this->getConnection()->getRepository('Translation')->find($idTranslation);
            if (!empty($translation)) 
            {
                $this->getConnection()->remove($translation);
            }
            else
            {
                $errorDelete = true;
                echo "warning+errorDelete";
                break;
            }
        }
        if (!$errorDelete)
        {
            $label = $this->getConnection()->getRepository('Label')->find($_POST['idLab']);
            if (!empty($label)) 
            {
                
                $this->getConnection()->remove($label);
                $this->getConnection()->flush();
                echo "success+successDeleteLabel";
            }
            else
            {
                echo "warning+errorDelete";
            }
        }
    }
    public function add()
    {
        $activity = $this->getConnection()->getRepository('Activity')->findByName($_POST['activity']);
        $language = $this->getConnection()->getRepository('Language')->findBy(array(), array('order' => 'ASC'));
        $label = new Label;

        $label->setIdActivity($activity[0]);
        $label->setLabel($_POST['label']);
        $label->setLinked(false);

        $this->getConnection()->persist($label);
        $this->getConnection()->flush();

        $content = "<tr>
                        <th class=''>
                            <i class='fa fa-times deleteTranslation' aria-hidden='true'></i>
                        </th>
                        <td value='text' alt='".$label->getLabel()."'>".$label->getLabel()."</td>
                        <input type='hidden' value='label' alt='".$label->getId()."'/>";

        foreach($_POST['translation'] as $key => $libelle)
        {
            $translation = new Translation;
            $translation->setLibelle(utf8_decode($libelle));
            $translation->setIdLabel($label);
            $translation->setIdLanguage($language[$key]);
            $this->getConnection()->persist($translation);
            $this->getConnection()->flush();
            $content = $content."<td value='text' alt=".$translation->getId().">".utf8_encode($translation->getLibelle())."</td><input type='hidden' value='".$translation->getIdLanguage()->getId()."' alt='".$translation->getId()."'/>";
        }
        $content = $content."</tr>";

        echo (!empty($label)) ? "success+successAddLabel+".$content : "errorAdd";
    }
}
