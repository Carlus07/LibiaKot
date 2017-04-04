<?php

namespace Controllers;

use Controllers\Tools\Validation;
use \Equipment;
use \Label;
use \Translation;
use \Category;

class EquipmentController extends Controller {

    public function index($error = null) {
        $categories = $this->getConnection()->getRepository('Category')->findAll();
        $equipments = $this->getConnection()->getRepository('Equipment')->findBy(array(), array('idCategory' => 'ASC'));
        
        $associativeArray = [];
        $notAssociatedEquipment = [];
       	foreach($categories as $key => $category)
       	{
       		$associativeArray[$category->getId()]["category"] = $category->getIdLabel()->getLabel();
       	}
       	foreach ($equipments as $key => $equipment) {
         		if ($equipment->getIdCategory() != null)
         		{
         			  $associativeArray[$equipment->getIdCategory()->getId()]['equipment'][$equipment->getId()]["name"] = $equipment->getIdLabel()->getLabel();
                if ($equipment->getIcon() != null) $associativeArray[$equipment->getIdCategory()->getId()]['equipment'][$equipment->getId()]["icon"] = $equipment->getIcon();
         		}
         		else 
         		{
         			  $notAssociatedEquipment[$equipment->getId()]["name"] = $equipment->getIdLabel()->getLabel();
                $notAssociatedEquipment[$equipment->getId()]["picture"] = $equipment->getIcon();
         		}
       	}
        $this->render('equipment.index.category', compact('associativeArray', 'notAssociatedEquipment', 'error'));
    }
    public function add() {
        $equipment = new Equipment;
        $label = new Label;
        $activity = $this->getConnection()->getRepository('Activity')->findByName("equipment");
        $language = $this->getConnection()->getRepository('Language')->findBy(array(), array('order' => 'ASC'));


        $validation = new Validation;
        $label->setLabel(htmlspecialchars($_POST['labelEquipment']));
        $label->setIdActivity($activity[0]);
        $label->setLinked(true);

        $validation->text($label->getLabel());

        if ($validation->isErrors()) 
        {
            $errors = $validation->getErrors();
            $this->index($errors);
        }
        else
        {
            $this->getConnection()->persist($label);
            $this->getConnection()->flush();

            foreach($language as $key => $langue)
            {
                $translation = new Translation;
                $translation->setLibelle('');
                $translation->setIdLabel($label);
                $translation->setIdLanguage($language[$key]);
                $this->getConnection()->persist($translation);
                $this->getConnection()->flush();
            }

            $equipment->setIcon(htmlspecialchars('web/pictures/Equipment/'.$_POST['namePicture']));
            $equipment->setIdLabel($label);

            if (rename('web/pictures/Equipment/cache/'.$_POST['namePicture'], $equipment->getIcon()))
            {
                $this->getConnection()->persist($equipment);
                $this->getConnection()->flush();
                $this->index();
            }
            else
            {
                $this->index('travelingError+');
            }
        }   
    }

    public function update() {
        $equipment = $this->getConnection()->getRepository('Equipment')->find($_POST['idEquipment']);
        $icon = $equipment->getIcon();
        $equipment->setIcon(htmlspecialchars('web/pictures/Equipment/'.$_POST['name']));
        if (rename('web/pictures/Equipment/cache/'.$_POST['name'], $equipment->getIcon()))
        {
            unlink($icon);
            $this->getConnection()->persist($equipment);
            $this->getConnection()->flush();
            echo "success+udpateLogo";
        }
        else echo "warning+travelingError";
    }

    public function delete()
    {
        $errorDelete = false;
        $equipment = $this->getConnection()->getRepository('Equipment')->find($_POST['idEquipment']);
        $label = $this->getConnection()->getRepository('Label')->find($equipment->getIdLabel()->getId());
        $translations = $this->getConnection()->getRepository('Translation')->findByIdLabel($label->getId());

        foreach($translations as $translation)
        {
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
            if (!empty($label)) 
            {
                
                $this->getConnection()->remove($label);
                unlink($equipment->getIcon());
                $this->getConnection()->remove($equipment);
                $this->getConnection()->flush();
                echo "success+successDeleteEquipment";
            }
            else
            {
                echo "warning+errorDelete";
            }
        }
    }

    public function updateCategory()
    {
        $equipment = $this->getConnection()->getRepository('Equipment')->find($_POST['idEquipment']);
        $category = ($_POST['idCategory'] != 0) ? $this->getConnection()->getRepository('Category')->find($_POST['idCategory']) : null;
        if (!empty($equipment)) 
        {
            $equipment->setIdCategory($category);
            $this->getConnection()->persist($equipment);
            $this->getConnection()->flush();
            echo "success+successupdateCategory";
        }
        else echo "warning+errorUpdateCategory";
    }

    public function upload() {
        $imageData = $_POST['file'];
        $ext = $_POST['extension'];
        $nameFinal = $_POST['name'].'.'.$ext;
        $filteredData = substr($imageData, strpos($imageData, ",")+1);
        $repertory = "web/pictures/Equipment/cache/";
        //Décodage du base64
        $unencodedData = base64_decode($filteredData);
        //Renommage de l'image avec un nom unique
        $fp = fopen($repertory.$nameFinal, 'wb' );
        //Ecriture dans le fichier
        fwrite( $fp, $unencodedData);
        fclose( $fp );
    }
}
