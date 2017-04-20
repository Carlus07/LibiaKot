<?php

namespace Controllers;
use \Property;
use \Housing;
use \HousingEquipment;
use \Type;
use \SubType;
use \Picture;
use \User;
use Controllers\Tools\Mail;
use Controllers\Tools\Language;
use Controllers\Tools\Validation;
use Models\Session;

class HousingController extends Controller {

    public function view() {
        $this->render('housing.view');
    }
    public function addHousing($errors = "") 
    {
        if (empty($_POST)) 
        {
            $menus = array();
            $typeRepo = static::getConnection()->getRepository("Type");
            $types = $typeRepo->findAll();
            $subTypeRepo = static::getConnection()->getRepository("SubType");
            $subTypes = $subTypeRepo->findAll();     

            foreach($types as $type)
            {
                $menus[Language::getLabelTranslation($type->getIdLabel())][$type->getId()] = array();
            }
            foreach($subTypes as $subType)
            {
                $menus[Language::getLabelTranslation($subType->getIdType()->getIdLabel()->getId())][$subType->getIdType()->getId()][$subType->getId()] = Language::getLabelTranslation($subType->getIdLabel());
            }

            $zipCodes = $this->getZipCode(true);
            $categories = $this->getConnection()->getRepository('Category')->findBy(array(), array('idCategory' => 'ASC'));
            $equipments = $this->getConnection()->getRepository('Equipment')->findBy(array(), array('idCategory' => 'ASC'));
            $associativeArray = [];
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
            }
            $this->render('housing.add.equipment', compact("zipCodes", "associativeArray", "menus", "errors"));
        } 
        else 
        {
            $property = new Property;
            $housing = new Housing;
            $validation = new Validation;
            $property->setZipCode(htmlspecialchars($_POST['zipCode']));
            $property->setCity(htmlspecialchars($_POST['city']));
            $property->setStreet(htmlspecialchars($_POST['street']));
            $property->setNumber(htmlspecialchars($_POST['number']));
            $property->setGPSPosition(htmlspecialchars($_POST['GPSPosition']));
            $property->setEaseNearby(htmlspecialchars($_POST['easeNearby']));
            $property->setDomiciliation(htmlspecialchars($_POST['domiciliation']));
            $property->setTargetAudience(htmlspecialchars($_POST['targetAudience']));

            if (isset($_POST['garden'])) $property->setGarden(true);
            else $property->setGarden(false);
            if (isset($_POST['terrace'])) $property->setTerrace(true);
            else $property->setTerrace(false);
            if (isset($_POST['bicycleParking'])) $property->setBicycleParking(true);
            else $property->setBicycleParking(false);
            if (isset($_POST['carParking'])) $property->setCarParking(true);
            else $property->setCarParking(false);
            if (isset($_POST['disabledAccess'])) $property->setDisabledAccess(true);
            else $property->setDisabledAccess(false);
            if (isset($_POST['smoker'])) $property->setSmoker(true);
            else $property->setSmoker(false);
            if (isset($_POST['realizedPEB'])) $property->setPEB(true);
            else $property->setPEB(false);
            if (isset($_POST['animal'])) $property->setAnimal(true);
            else $property->setAnimal(false);


            $validation->number($property->getZipCode());
            $validation->text($property->getCity());
            $validation->text($property->getStreet());
            $validation->number($property->getNumber());
            $validation->text($property->getEaseNearby());

            if ($validation->isErrors()) {
                $errors = $validation->getErrors();
                unset($_POST);
                $this->addHousing($errors);
            }
            else
            {
                $user = $this->getConnection()->getRepository("User")->find(Session::get('idUser'));
                $property->setIdUser($user);
                if (Session::get('Role') == 2) $property->setState(0);
                if (Session::get('Role') == 3) $property->setState(1);
                $this->getConnection()->persist($property);
                $this->getConnection()->flush();

                $result = explode('+',$_POST['housingType']);
                $type = $this->getConnection()->getRepository("Type")->find($result[0]);
                $housing->setIdType($type);
                if (isset($result[1])) 
                {
                    $subType = $this->getConnection()->getRepository("SubType")->find($result[1]);
                    $housing->setIdSubType($subType);
                }
                else $housing->setIdSubType(null);

                $housing->setAvailability(new \DateTime($_POST['availability']));
                if ($_POST['capacity'] == "") $housing->setCapacity(0);
                else $housing->setCapacity(htmlspecialchars($_POST['capacity']));
                if ($_POST['spaceAvailable'] == "") $housing->setSpaceAvailable(0);
                else $housing->setSpaceAvailable(htmlspecialchars($_POST['spaceAvailable']));
                $housing->setArea(htmlspecialchars($_POST['area']));
                $housing->setFloor(htmlspecialchars($_POST['floor']));
                $housing->setBathroom(htmlspecialchars($_POST['bathroom']));
                $housing->setKitchen(htmlspecialchars($_POST['kitchen']));
                $housing->setHeating(htmlspecialchars($_POST['heating']));
                $housing->setRent(htmlspecialchars($_POST['rent']));
                $housing->setCharge(htmlspecialchars($_POST['charge']));
                $housing->setDeposit(htmlspecialchars($_POST['deposit']));
                $housing->setRentalDuration(htmlspecialchars($_POST['rentalDuration']));
                $housing->setRentComment(htmlspecialchars($_POST['rentComment']));

                $validation->number($housing->getArea());
                $validation->number($housing->getFloor());
                $validation->number($housing->getRent());
                $validation->number($housing->getCharge());
                $validation->number($housing->getDeposit());
                $validation->text($housing->getRentComment());
                
                if ($validation->isErrors()) {
                    $errors = $validation->getErrors();
                    unset($_POST);
                    $this->addHousing($errors);
                }
                else
                {    
                    if (Session::get('Role') == 2) $housing->setState(0);
                    if (Session::get('Role') == 3) $housing->setState(1);      
                    $housing->setIdProperty($property);
                    $housingReference = $this->getConnection()->getRepository('Housing')->findOneBy(array(), array('reference' => 'DESC'));
                    if (!empty($housingReference))
                    {
                        $reference = $housingReference->getReference()+1;
                        $housing->setReference($reference);
                    }
                    else $housing->setReference(1);
                    $this->getConnection()->persist($housing);
                    $this->getConnection()->flush();   

                    $equipments = $this->getConnection()->getRepository('Equipment')->findAll();
                    foreach ($equipments as $equipment) {
                        if (isset($_POST[$equipment->getIdLabel()->getLabel()])) 
                        {
                            $housingEquipment = new HousingEquipment;
                            $housingEquipment->setIdHousing($housing);
                            $housingEquipment->setIdEquipment($equipment);
                            $this->getConnection()->persist($housingEquipment);
                            $this->getConnection()->flush(); 
                        }
                    }
                    $picturesCache = (scandir('web/pictures/Housing/cache/'));
                    foreach ($picturesCache as $pictureCache) {
                        if (strstr($pictureCache, $_POST['code']))
                        {
                            $repertory = "web/pictures/Housing/";
                            if (strstr($pictureCache,'-miniature')) rename('web/pictures/Housing/cache/'.$pictureCache, "web/pictures/Housing/miniature/".$pictureCache);
                            else 
                            {
                                //list($width, $height, $type, $attr) = getimagesize('Views/Images/Albums/EnAttente/'.$image);
                                //$paysage = ($width > $height) ? true : false;
                                rename('web/pictures/Housing/cache/'.$pictureCache, "web/pictures/Housing/original/".$pictureCache);
                                $picture = new Picture;
                                $picture->setName($pictureCache);
                                $picture->setDateUpload(new \DateTime("now"));
                                $picture->setIdHousing($housing);
                                $this->getConnection()->persist($picture);
                                $this->getConnection()->flush(); 
                            }
                        }
                    }

                }

                /*$translation = Language::translation("mail");
                $redirection = Navi::getRedirection($translation, true, "http://localhost/Projet/mail.php?fn=".$user->getFirstName()."&l=".Session::get("Language")."&m=register&t=".$user->getToken());
                $contentMessage = Navi::getContentMail($translation, true, $user->getFirstName(), "register", "http://localhost/Projet/index.php?p=user.confirmation&t=".$user->getToken()."&m=register");
                Mail::sendMail($translation["subjectRegister"], $user->getMail(), $redirection, $contentMessage);
                Router::redirect('user.confirmation', 'register');*/
            }
        }
    }
    public function getZipCode($return = false)
    {
    	$query = $this->getConnection()->createQuery('SELECT DISTINCT l.zipCode FROM Locality l ORDER BY l.zipCode ASC');
		$zipCode = $query->getResult();
		$arrayZipCode = [];
		foreach($zipCode as $code)
		{
			array_push($arrayZipCode, strval($code["zipCode"]));
		}
    	if (!$return) echo json_encode($arrayZipCode);
    	else return $arrayZipCode;
    }
    public function getCity()
    {
    	$cities = $this->getConnection()->getRepository('Locality')->findByZipCode($_POST['zipCode']);
		$arrayCity = [];
		foreach($cities as $city)
		{
			array_push($arrayCity, utf8_encode($city->getNameCity()));
		}
    	echo json_encode($arrayCity);
    }
    public function getStreet()
    {
    	$query = $this->getConnection()->createQuery("
    		SELECT a.street
    		FROM Address a JOIN a.idLocality l
    		WHERE l.zipCode = '".$_POST['zipCode']."'");

		$streets = $query->getResult();
		$arrayStreet = [];
		foreach($streets as $street)
		{
			array_push($arrayStreet, utf8_encode($street['street']));
		}
		echo json_encode($arrayStreet);
    }
    public function addPicture()
    {
        $imageData = $_POST['file'];
        $ext = $_POST['extension'];
        $nameFinal = $_POST['name'].'-'.$_POST['type'].'.'.$ext;
        $filteredData = substr($imageData, strpos($imageData, ",")+1);
        $repertory = "web/pictures/Housing/cache/";
        //Décodage du base64
        $unencodedData = base64_decode($filteredData);
        //Renommage de l'image avec un nom unique
        $fp = fopen($repertory.$nameFinal, 'wb' );
        //Ecriture dans le fichier
        fwrite($fp, $unencodedData);
        fclose($fp );
    }
    public function deletePicture()
    {
        $repertory = "web/pictures/Housing/cache/";
        $error = unlink($repertory.$_POST['picture'].'-original.png');
        $error = unlink($repertory.$_POST['picture'].'-miniature.png');
        echo ($error) ? "success+successDeleteFile" : "warning+errorDeleteFile";
    }
    public function myHousing()
    {
        $properties = $this->getConnection()->getRepository('Property')->findByIdUser(Session::get('idUser'));
        $validatedHousing = [];
        $pendingHousing = [];
        foreach ($properties as $key => $property) {
            
            $results = $this->getConnection()->getRepository('Housing')->findByIdProperty($property->getId());
            $pendingHousing[$key]['housing'] = [];
            $validatedHousing[$key]['housing'] = [];
            foreach ($results as $housing) {
                if ($housing->getState() == 0)
                {
                    $pendingHousing[$key]['property'] = $property;
                    array_push($pendingHousing[$key]['housing'], $housing);
                }
                else
                {
                    $validatedHousing[$key]['property'] = $property;
                    array_push($validatedHousing[$key]['housing'], $housing);
                }
            }
            
        }  
        $this->render('housing.myHousing', compact("pendingHousing", "validatedHousing"));
    }
}
