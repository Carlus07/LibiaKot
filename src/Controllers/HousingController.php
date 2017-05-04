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
            if (isset($_GET['m']) && ($_GET['m'] == "updateHousing")) $accomodation = $this->getConnection()->getRepository("Housing")->find($_GET['id']);
            else if (isset($_GET['m']) && ($_GET['m'] == "updateProperty")) $accomodation = $this->getConnection()->getRepository("Property")->find($_GET['id']);
            else $accomodation = null;

            if (isset($_GET['m']) && ($_GET['m'] == "updateHousing"))
            {
                $equipments = $this->getConnection()->getRepository("HousingEquipment")->findByIdHousing($_GET['id']);
                $housingEquipment = [];
                foreach ($equipments as $equipment) {
                    $housingEquipment[$equipment->getIdEquipment()->getIdLabel()->getLabel()] = true;
                }
                $pictures = $this->getConnection()->getRepository("Picture")->findByIdHousing($_GET['id']);
            }
            $users = null;
            if (Session::get('Role') == 3)
            {
                $role = $this->getConnection()->getRepository("Role")->findByDescription("owner");
                $users = $this->getConnection()->getRepository("User")->findByIdRole($role[0]);
            }
            $menus = array();
            $typeRepo = static::getConnection()->getRepository("Type");
            $types = $typeRepo->findAll();
            $subTypeRepo = static::getConnection()->getRepository("SubType");
            $subTypes = $subTypeRepo->findAll();     

            foreach($types as $type)
            {
                $menus[$type->getIdLabel()->getLabel()][Language::getLabelTranslation($type->getIdLabel())][$type->getId()] = array();
            }
            foreach($subTypes as $subType)
            {
                $menus[$subType->getIdType()->getIdLabel()->getLabel()][Language::getLabelTranslation($subType->getIdType()->getIdLabel()->getId())][$subType->getIdType()->getId()][$subType->getId()] = Language::getLabelTranslation($subType->getIdLabel());
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
            $this->render('housing.add.equipment', compact("zipCodes", "associativeArray", "menus", "errors", "accomodation", "housingEquipment", "pictures", "users"));
        } 
        else 
        {
            $property = ($_POST['method'] == "updateProperty") ? $this->getConnection()->getRepository('Property')->find($_POST['id']) : new Property;
            $housing = ($_POST['method'] == "updateHousing") ? $this->getConnection()->getRepository('Housing')->find($_POST['id']) : new Housing;
            $validation = new Validation;
            if ($_POST['method'] != "updateHousing")
            {
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
                    $user = (Session::get('Role') == 3) ? $this->getConnection()->getRepository("User")->find($_POST['idUser']) : $this->getConnection()->getRepository("User")->find(Session::get('idUser'));
                    $property->setIdUser($user);
                    if (Session::get('Role') == 2) $property->setState(0);
                    if (Session::get('Role') == 3) $property->setState(1);
                    if ($_POST['method'] == "updateProperty") $property->setState(2);
                    $this->getConnection()->persist($property);
                    $this->getConnection()->flush();
                }
            }
            if ($_POST['method'] != "updateProperty")
            {
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
                    if ($_POST['method'] == "updateHousing") $housing->setState(2);
                    if ($_POST['id'] == "") 
                    {
                        $housing->setIdProperty($property);
                        $housingReference = $this->getConnection()->getRepository('Housing')->findOneBy(array(), array('reference' => 'DESC'));
                        if (!empty($housingReference))
                        {
                            $reference = $housingReference->getReference()+1;
                            $housing->setReference($reference);
                        }
                        else $housing->setReference(1);
                    }
                    $this->getConnection()->persist($housing);
                    $this->getConnection()->flush();   
                    if ($_POST['method'] == "updateHousing")
                    {
                        $housingEquipments = $this->getConnection()->getRepository('HousingEquipment')->findByIdHousing($_POST['id']);
                        foreach ($housingEquipments as $housingEquipment) {
                            $this->getConnection()->remove($housingEquipment);
                        }
                        $this->getConnection()->flush();
                    }
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
                            $namePicture = explode("-",$pictureCache);
                            if (strstr($pictureCache,'-miniature')) rename('web/pictures/Housing/cache/'.$pictureCache, "web/pictures/Housing/miniature/".$pictureCache);
                            else 
                            {
                                //list($width, $height, $type, $attr) = getimagesize('Views/Images/Albums/EnAttente/'.$image);
                                //$paysage = ($width > $height) ? true : false;
                                rename('web/pictures/Housing/cache/'.$pictureCache, "web/pictures/Housing/original/".$pictureCache);
                                $picture = new Picture;
                                $picture->setName($namePicture[0].".png");
                                $picture->setDateUpload(new \DateTime("now"));
                                $picture->setIdHousing($housing);
                                $this->getConnection()->persist($picture);
                                $this->getConnection()->flush(); 
                            }
                        }
                    }
                }
            }
            if (Session::get('Role') == 2) $this->myHousing();
            if (Session::get('Role') == 3) $this->listHousings();
                /*$translation = Language::translation("mail");
                $redirection = Navi::getRedirection($translation, true, "http://localhost/Projet/mail.php?fn=".$user->getFirstName()."&l=".Session::get("Language")."&m=register&t=".$user->getToken());
                $contentMessage = Navi::getContentMail($translation, true, $user->getFirstName(), "register", "http://localhost/Projet/index.php?p=user.confirmation&t=".$user->getToken()."&m=register");
                Mail::sendMail($translation["subjectRegister"], $user->getMail(), $redirection, $contentMessage);
                Router::redirect('user.confirmation', 'register');*/
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
        $error = true;
        if ($_POST['status'] == "new")
        {
            $repertory = "web/pictures/Housing/cache/";
            $error = unlink($repertory.$_POST['picture'].'-original.png');
            $error = unlink($repertory.$_POST['picture'].'-miniature.png');
        }
        else
        {
            $error = unlink('web/pictures/Housing/original/'.$_POST['picture'].'-original.png');
            $error = unlink('web/pictures/Housing/miniature/'.$_POST['picture'].'-miniature.png');
            if ($error)
            {
                $query = $this->getConnection()->createQuery("
                    SELECT p FROM Picture p
                    WHERE p.name LIKE '".$_POST['picture']."%'
                ");
                $pictures = $query->getResult();
                foreach ($pictures as $picture) {
                    $this->getConnection()->remove($picture);
                }
                $this->getConnection()->flush();
            }
        }
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
                $pictures = $this->getConnection()->getRepository('Picture')->findByIdHousing($housing->getId());
                if (!empty($pictures))
                {
                    $namePicture = explode('.',$pictures[0]->getName());
                    $picture = "web/pictures/Housing/miniature/".$namePicture[0]."-miniature.".$namePicture[1];
                }
                else $picture =  "web/pictures/iconLarge.png";
                
                if ($housing->getState() == 0)
                {
                    $pendingHousing[$key]['picture'] = $picture;
                    $pendingHousing[$key]['property'] = $property;
                    array_push($pendingHousing[$key]['housing'], $housing);
                }
                else
                {
                    $validatedHousing[$key]['picture'] = $picture;
                    $validatedHousing[$key]['property'] = $property;
                    array_push($validatedHousing[$key]['housing'], $housing);
                }
            }
        }  
        $query = static::getConnection()->createQuery("
            SELECT p 
            FROM Property p
            WHERE p.idProperty NOT IN (SELECT IDENTITY(h.idProperty) FROM Housing h)
        ");
        $properties = $query->getResult();

        $pendingHousing['title'] = "waitingValidation";
        $validatedHousing['title'] = "valid";
        $pendingHousing['icon'] = "refresh";
        $validatedHousing['icon'] = "check";
        $accomodations['validatedHousing'] = $validatedHousing;
        $accomodations['pendingHousing'] = $pendingHousing;
        $this->render('housing.myHousing', compact("accomodations", "properties"));
    }
    public function deleteHousing($idHousing = null)
    {
        $errorDelete = false;
        $idHousing = (isset($_POST['idHousing'])) ? $_POST['idHousing'] : $idHousing; 
        $housing = $this->getConnection()->getRepository('Housing')->find($idHousing);
        $housingEquipments = $this->getConnection()->getRepository('HousingEquipment')->findByIdHousing($idHousing);
        $pictures = $this->getConnection()->getRepository('Picture')->findByIdHousing($idHousing);

        foreach($housingEquipments as $housingEquipment)
        {
            if (!empty($housingEquipment)) 
            {
                $this->getConnection()->remove($housingEquipment);
                $this->getConnection()->flush();
            }
        }
        foreach($pictures as $picture)
        {
            if (!empty($picture)) 
            {
                $namePicture = explode('.', $picture->getName());
                $error = unlink('web/pictures/Housing/miniature/'.$namePicture[0].'-miniature.'.$namePicture[1]);
                $error = unlink('web/pictures/Housing/original/'.$namePicture[0].'-original.'.$namePicture[1]);
                $errorDelete = ($error) ? false : true;
                $this->getConnection()->remove($picture);
                $this->getConnection()->flush();
            }
        }
        if (!$errorDelete)
        {
            if (!empty($housing)) 
            {
                
                $this->getConnection()->remove($housing);
                $this->getConnection()->flush();
                if (isset($_POST['idHousing'])) echo "success+successDeleteHousing";
            }
            else
            {
                echo "warning+errorDelete";
            }
        }
    }
    public function deleteProperty($id)
    {
        $errorDelete = false;
        $idProperty = (isset($_POST['idProperty'])) ? $_POST['idProperty'] : $id;
        $property = $this->getConnection()->getRepository('Property')->find($idProperty);
        $results = $this->getConnection()->getRepository('Housing')->findByIdProperty($idProperty);
        foreach ($results as $housing) {
            if (!empty($housing))
            {
                $this->deleteHousing($housing->getId());
            }
        }
        if (!$errorDelete)
        {
            if (!empty($property)) 
            {
                
                $this->getConnection()->remove($property);
                $this->getConnection()->flush();
                echo "success+successDeleteProperty";
            }
            else
            {
                echo "warning+errorDelete";
            }
        }
    }
    public function listHousings()
    {
        if (isset($_GET['r']) && (($_GET['r'] % 12) == 0))
        {
            $housings = $this->getConnection()->getRepository('Housing')->findByState(1); 
            $size = sizeof($housings);

            $offset = (isset($_GET['r'])) ? $_GET['r'] : 12;
            $limit = $offset - 12;
            $dql = "SELECT h FROM Housing h WHERE h.state = 1 ORDER BY h.reference ASC";
            $query = $this->getConnection()->createQuery($dql)
                           ->setFirstResult($limit)
                           ->setMaxResults($offset);

            $results = $query->getResult();
            $housings = [];
            foreach ($results as $key => $housing) {
                $pictures = $this->getConnection()->getRepository('Picture')->findByIdHousing($housing->getId());
                if (!empty($pictures))
                {
                    $namePicture = explode('.',$pictures[0]->getName());
                    $picture = "web/pictures/Housing/miniature/".$namePicture[0]."-miniature.".$namePicture[1];
                }
                else $picture =  "web/pictures/iconLarge.png";
                $housings[$key]['picture'] = $picture;

                $reference = $housing->getReference();
                for ($i = 0; $i < 5-floor(log10($reference) + 1); $i++)
                {
                    $reference = '0'.$reference;
                }
                $reference = "LK ".$reference;
                $housings[$key]['reference'] = $reference;
                $housings[$key]["id"] = $housing->getId();
                $housings[$key]["idProperty"] = $housing->getIdProperty()->getId();
            }
            $this->render('housing.list', compact('housings', 'size'));
        }
        else
        {
            $this->render('error.index');
        }
    }
    public function viewHousing()
    {
        if ((isset($_GET['id'])) && ($_GET['id'] > 0))
        {
            $housing = $this->getConnection()->getRepository('Housing')->find($_GET['id']);
            $pictures = $this->getConnection()->getRepository('Picture')->findByIdHousing($housing->getId());
            $this->render('housing.viewHousing', compact('housing','pictures'));
        }
        else
        {
            $this->render('error.index');
        }
    }
}
