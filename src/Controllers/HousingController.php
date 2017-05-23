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
use Controllers\Tools\Navi;
use Controllers\Tools\Validation;
use Controllers\Tools\Router;
use Models\Session;

class HousingController extends Controller {

    public function view() 
    {
        if (isset($_GET['r']) && (($_GET['r'] % 10) == 0))
        {
            if ((isset($_GET['t'])) || (isset($_GET['id'])))
            {
                $value['t'] = (isset($_GET['t'])) ? $_GET['t'] : "";
                $value['id'] = (isset($_GET['id'])) ?  $_GET['id'] : "";
                $value['r'] = $_GET['r'];
                Session::set('settings', $value);

                $offset = (isset($_GET['r'])) ? $_GET['r'] : 10;
                $limit = $offset - 10;

                $type = (isset($_GET['t'])) ? "SubType" : "Type";
                $id = (isset($_GET['t'])) ? $_GET['t'] : $_GET['id'];
                $request = $this->getConnection()->getRepository($type)->find($id);
                $idType = "id".$type;
                $housings = $this->getConnection()->getRepository("Housing")->findBy(array($idType => $request, 'state' => '1', 'visibility' => '1'));
                $size = sizeof($housings);

                $param = "h.".$idType;
                $dql = "SELECT h FROM Housing h WHERE h.state = 1 AND h.visibility = 1 AND ".$param." = ".$request->getId()." ORDER BY h.reference ASC";
                $query = $this->getConnection()->createQuery($dql)
                                ->setFirstResult($limit)
                                ->setMaxResults($offset);
                $housings = $query->getResult();
            }
            else if (!isset($_GET['id']) && !(isset($_GET['t'])))
            {
                $housings = $this->getConnection()->getRepository("Housing")->findBy(array('state' => '1', 'visibility' => '1'));
                $size = 0;
            }

            if (!empty($housings))
            {
                $pictures = [];
                foreach ($housings as $housing) {
                    $results = $this->getConnection()->getRepository('Picture')->findByIdHousing($housing->getId());
                    if (!empty($results))
                    {
                        $namePicture = explode('.',$results[0]->getName());
                        $picture = "web/pictures/Housing/miniature/".$namePicture[0]."-miniature.".$namePicture[1];
                    }
                    else $picture =  "web/pictures/iconLarge.png";
                    $pictures[$housing->getId()] = $picture;
                }
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
            $this->render('housing.view', compact("housings", "pictures", "menus", "size"));
        }
        else
        {
            $this->render('error.index');
        }
    }
    public function addHousing($errors = "") 
    {
        if (empty($_POST)) 
        {   
            $value['m'] = (isset($_GET['m'])) ? $_GET['m'] : "";
            Session::set('settings', $value);

            if (isset($_GET['m']) && ($_GET['m'] == "updateHousing")) 
            {
                $accomodation = $this->getConnection()->getRepository("Housing")->find($_GET['id']);
                if (($accomodation->getIdProperty()->getIdUser()->getId() != Session::get('idUser')) && (Session::get('Role') != 3))
                {
                    $this->render('error.index');
                }
            }
            else if (isset($_GET['m']) && ($_GET['m'] == "updateProperty")) 
            {
                $accomodation = $this->getConnection()->getRepository("Property")->find($_GET['id']);
                if (($accomodation->getIdUser()->getId() != Session::get('idUser')) && (Session::get('Role') != 3))
                {
                    $this->render('error.index');
                }
            }
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
            if (($_POST['method'] != "updateHousing") && ($_POST['method'] != "addHousing"))
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
                $validation->number($property->getNumber());

                if ($validation->isErrors()) {
                    $errors = $validation->getErrors();
                    unset($_POST);
                    $this->addHousing($errors);
                }
                else
                {
                    if (Session::get('Role') == 3)
                    {
                        if ((isset($_POST['idUser'])) && (!empty($_POST['idUser'])))
                        {
                            $property->setState(1);
                            $user = $this->getConnection()->getRepository('User')->find($_POST['idUser']);
                            $property->setIdUser($user);
                        }
                    }
                    if (Session::get('Role') == 2) 
                    {
                        $property->setState(0);
                        $user = $this->getConnection()->getRepository("User")->find(Session::get('idUser'));
                        $property->setIdUser($user);
                    }
                    if (($_POST['method'] == "updateProperty") && (Session::get('Role') == 2)) $property->setState(2);
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
                if ($_POST['capacity'] == "") $housing->setCapacity(1);
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
                $housing->setVisibility(1);
                
                $validation->number($housing->getArea());
                $validation->number($housing->getFloor());
                $validation->number($housing->getRent());
                $validation->number($housing->getCharge());
                $validation->number($housing->getDeposit());
                
                if ($validation->isErrors()) {
                    $errors = $validation->getErrors();
                    unset($_POST);
                    $this->addHousing($errors);
                }
                else
                {    
                    if (Session::get('Role') == 2) $housing->setState(0);
                    if (Session::get('Role') == 3) $housing->setState(1);   
                    if (($_POST['method'] == "updateHousing") && (Session::get('Role') == 2)) $housing->setState(2);
                    
                    if ((isset($_POST['id'])) && (!empty($_POST['id']))) 
                    {
                        $result = $this->getConnection()->getRepository('Housing')->find($_POST['id']);
                        $property = $this->getConnection()->getRepository('Property')->find($result->getIdProperty());
                    }
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
            if (Session::get('Role') == 2) 
            {
                $user = $this->getConnection()->getRepository("User")->find(Session::get('idUser'));
                if (!empty($user->getMail()))
                {
                    $translation = Language::translation("mail");
                    if ($_POST['method'] != "updateProperty")
                    {
                        if ($_POST['method'] == "updateHousing")
                        {
                            $redirection = Navi::getRedirection($translation, true, "http://".$_SERVER["SERVER_NAME"]."/mail.php?fn=".$user->getFirstName()."&l=".Session::get("Language")."&m=updateHousing");
                            $contentMessage = Navi::getContentMail($translation, true, $user->getFirstName(), "updateHousing");
                            if (!Mail::sendMail($translation["subjectUpdateHousing"], $user->getMail(), $redirection, $contentMessage))
                            {
                                $this->render('error.mail');
                            }
                            $redirection = Navi::getRedirection($translation, true, "http://".$_SERVER["SERVER_NAME"]."/mail.php?fn=Cathy&l=".Session::get("Language")."&m=newRequest");
                            $contentMessage = Navi::getContentMail($translation, true, 'Cathy', "newRequest");
                            if (!Mail::sendMail($translation["newRequest"], 'carlmath@hotmail.com', $redirection, $contentMessage))
                            {
                                $this->render('error.mail');
                            }
                            Router::redirect('mail.confirmation', 'addHousing');
                        }
                        else
                        {
                            $redirection = Navi::getRedirection($translation, true, "http://".$_SERVER["SERVER_NAME"]."/mail.php?fn=".$user->getFirstName()."&l=".Session::get("Language")."&m=addHousing");
                            $contentMessage = Navi::getContentMail($translation, true, $user->getFirstName(), "addHousing");
                            if (!Mail::sendMail($translation["subjectAddHousing"], $user->getMail(), $redirection, $contentMessage))
                            {
                                $this->render('error.mail');
                            }
                            $redirection = Navi::getRedirection($translation, true, "http://".$_SERVER["SERVER_NAME"]."/mail.php?fn=Cathy&l=".Session::get("Language")."&m=newRequest");
                            $contentMessage = Navi::getContentMail($translation, true, 'Cathy', "newRequest");
                            if (!Mail::sendMail($translation["newRequest"], 'carlmath@hotmail.com', $redirection, $contentMessage))
                            {
                                $this->render('error.mail');
                            }
                            Router::redirect('mail.confirmation', 'addHousing');
                        }
                    }
                    else
                    {
                        $this->myHousing();
                    }
                }
            }
            if (Session::get('Role') == 3) 
            {
                if ($_POST['method'] == "updateHousing") $this->listHousings(12);
                if ($_POST['method'] == "updateProperty") $this->listProperties(12);
                if ($_POST['method'] == "addHousing") $this->listProperties(12);
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
            $validate = 0;
            $pending = 0;
            foreach ($results as $housing) {
                $picture =  "web/pictures/iconLarge.png";
                $resuls = $this->getConnection()->getRepository('Picture')->findByIdHousing($housing->getId());
                if (!empty($resuls[0]))
                {
                    $namePicture = explode('.',$resuls[0]->getName());
                    $picture = "web/pictures/Housing/miniature/".$namePicture[0]."-miniature.".$namePicture[1];
                }
                if (($housing->getState() == 0) || ($housing->getState() == 2))
                {
                    $pictures['pendingHousing'][$pending] = $picture;
                    $pendingHousing[$key]['property'] = $property;
                    array_push($pendingHousing[$key]['housing'], $housing);
                    $pending++;
                }
                else
                {
                    $pictures['validatedHousing'][$validate] = $picture;
                    $validatedHousing[$key]['property'] = $property;
                    array_push($validatedHousing[$key]['housing'], $housing);
                    $validate++;
                }
            }
        }  
        $query = static::getConnection()->createQuery("
            SELECT p 
            FROM Property p
            WHERE p.idUser = ".Session::get('idUser')." AND p.idProperty NOT IN (SELECT IDENTITY(h.idProperty) FROM Housing h)
        ");
        $properties = $query->getResult();

        $pendingHousing['title'] = "waitingValidation";
        $validatedHousing['title'] = "valid";
        $pendingHousing['icon'] = "refresh";
        $validatedHousing['icon'] = "check";
        $accomodations['validatedHousing'] = $validatedHousing;
        $accomodations['pendingHousing'] = $pendingHousing;
        $this->render('housing.myHousing', compact("accomodations", "properties", "pictures"));
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
    public function listHousings($get = null)
    {
        if ((isset($_GET['r']) && (($_GET['r'] % 12) == 0)) || !empty($get))
        {
            $value['r'] = (isset($_GET['r'])) ? $_GET['r'] : $get;
            Session::set('settings', $value);

            $housings = $this->getConnection()->getRepository('Housing')->findBy(array('state' => '1', 'visibility' => '1'));
            $size = sizeof($housings);

            $offset = (isset($_GET['r'])) ? $_GET['r'] : ((!empty($get)) ? $get : 12);
            $limit = $offset - 12;
            $dql = "SELECT h FROM Housing h WHERE h.state = 1 AND h.visibility = 1 ORDER BY h.reference ASC";
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
            $value['id'] = $_GET['id'];
            Session::set('settings', $value);

            $housing = $this->getConnection()->getRepository('Housing')->find($_GET['id']);
            if (!empty($housing))
            {
                $pictures = $this->getConnection()->getRepository('Picture')->findByIdHousing($housing->getId());
                $results = $this->getConnection()->getRepository('HousingEquipment')->findByIdHousing($housing->getId());
                $equipments = [];
                foreach ($results as $key => $equipment) {
                    $category = $equipment->getIdEquipment()->getIdCategory()->getIdLabel()->getLabel();
                    $array['label'] = $equipment->getIdEquipment()->getIdLabel()->getLabel();
                    $array['picture'] = $equipment->getIdEquipment()->getIcon();
                    $equipments[$category][$key] = $array;
                }
                $this->render('housing.viewHousing.equipment', compact('housing','pictures', 'equipments'));
            }
            else
            {
                $this->render('error.index');
            }
        }
        else
        {
            $this->render('error.index');
        }
    }
    public function request($get = null)
    {
        if ((isset($_GET['r']) && (($_GET['r'] % 12) == 0)) || !empty($get))
        {
            $value['r'] = (isset($_GET['r'])) ? $_GET['r'] : $get;
            Session::set('settings', $value);
            $housings = $this->getConnection()->getRepository('Housing')->findByState([0, 2]); 
            $size = sizeof($housings);

            $offset = (isset($_GET['r'])) ? $_GET['r'] : ((!empty($get)) ? $get : 12);
            $limit = $offset - 12;
            $dql = "SELECT h FROM Housing h WHERE h.state = 0 OR h.state = 2 ORDER BY h.reference ASC";
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
            $this->render('housing.request', compact('housings', 'size'));
        }
        else
        {
            $this->render('error.index');
        }
    }
    public function confirmHousing()
    {
        $housing = $this->getConnection()->getRepository('Housing')->find($_POST['idHousing']);
        if ($housing != null)
        {
            $housing->setState(1);
            $property = $this->getConnection()->getRepository('Housing')->find($housing->getId());
            $property->setState(1);
            $this->getConnection()->persist($housing);
            $this->getConnection()->persist($property);
            $this->getConnection()->flush();
            echo "success+successConfirmHousing";
        }
        else echo "warning+errorConfirmHousing";
    }

    public function getHousingByReference()
    {
        $housing = $this->getConnection()->getRepository('Housing')->findByReference($_POST['reference']);
        if (!empty($housing[0]))
        {
            $language = new LangController;
            $result['id'] = $housing[0]->getId();
            $result['reference'] = $housing[0]->getReference();
            $result['rent'] = $housing[0]->getRent()+$housing[0]->getCharge();
            $result['city'] = $housing[0]->getIdProperty()->getCity();
            $result['label'] = $language->getTraductionByLabel($housing[0]->getIdType()->getIdLabel()->getLabel());
            $result['capacity'] = $housing[0]->getCapacity();
            $now = new \DateTime('now');
            $diff = $now->diff($housing[0]->getAvailability());
            $availability = ($diff->invert == 0) ?  $language->getTraductionByLabel('availableOn').$housing[0]->getAvailability()->format('d-m-Y') : $language->getTraductionByLabel('availableNow');
            $result['availability'] = $availability;
            $pictures = $this->getConnection()->getRepository('Picture')->findByIdHousing($housing[0]->getId());
            if (!empty($pictures))
            {
                $namePicture = explode('.',$pictures[0]->getName());
                $picture = "web/pictures/Housing/miniature/".$namePicture[0]."-miniature.".$namePicture[1];
            }
            else $picture =  "web/pictures/iconLarge.png";
            $result['picture'] = $picture;
            echo json_encode($result);
        }
        else echo json_encode('');
    }
    public function getHousingByOthers()
    {
        $offset = ($_POST['offset'] == 0) ? 10 : $_POST['offset'];
        $limit = $offset - 10;
        $type = explode("+", $_POST['type']);
        $typeRequest = (isset($type[1])) ? " AND h.idSubType = ".$type[1] : " AND h.idType = '".$type[0]."'";
        $rentDurationRequest = (empty($_POST['rentDuration'])) ? '' : " AND h.rentalDuration = ".$_POST['rentDuration'];
        $query = $this->getConnection()->createQuery("
                    SELECT h
                    FROM Housing h
                    WHERE h.rent >= '".$_POST['rent'][0]."' AND h.charge <= '".$_POST['rent'][1]."' AND h.capacity >= '".$_POST['bedroom']."'".$typeRequest.$rentDurationRequest
                );
        $results = $query->getResult();
        $size = sizeof($results);

        $query = $this->getConnection()->createQuery("
                    SELECT h
                    FROM Housing h
                    WHERE h.rent >= '".$_POST['rent'][0]."' AND h.charge <= '".$_POST['rent'][1]."' AND h.capacity >= '".$_POST['bedroom']."'".$typeRequest.$rentDurationRequest
                )->setFirstResult($limit)->setMaxResults($offset);

        $results = $query->getResult();

        if (!empty($results))
        {
            $housings = [];
            $now = new \DateTime('now');
            $language = new LangController;
            foreach ($results as $key => $housing) {
                $housings[$key]['size'] = $size;
                $housings[$key]['id'] = $housing->getId();
                $housings[$key]['reference'] = $housing->getReference();
                $housings[$key]['rent'] = $housing->getRent()+$housing->getCharge();
                $housings[$key]['city'] = $housing->getIdProperty()->getCity();
                $housings[$key]['label'] = $language->getTraductionByLabel($housing->getIdType()->getIdLabel()->getLabel());
                $housings[$key]['capacity'] = $housing->getCapacity();
                $diff = $now->diff($housing->getAvailability());
                $availability = ($diff->invert == 0) ?  $language->getTraductionByLabel('availableOn').$housing->getAvailability()->format('d-m-Y') : $language->getTraductionByLabel('availableNow');
                $housings[$key]['availability'] = $availability;
                $pictures = $this->getConnection()->getRepository('Picture')->findByIdHousing($housing->getId());
                if (!empty($pictures))
                {
                    $namePicture = explode('.',$pictures[0]->getName());
                    $picture = "web/pictures/Housing/miniature/".$namePicture[0]."-miniature.".$namePicture[1];
                }
                else $picture =  "web/pictures/iconLarge.png";
                $housings[$key]['picture'] = $picture;
            }
            echo json_encode($housings);
        }
        else echo json_encode('');
    }
    public function search()
    {
        if (isset($_POST['search']))
        {
            $reference = explode('lk', strtolower($_POST['search']));
            $reference = (isset($reference[1])) ? intval($reference[1]) : 0;
            $result = $this->getConnection()->getRepository('Housing')->findByReference($reference);
            if (!empty($result))
            {
                $housing = $result[0];
                $pictures = $this->getConnection()->getRepository('Picture')->findByIdHousing($housing->getId());
                $results = $this->getConnection()->getRepository('HousingEquipment')->findByIdHousing($housing->getId());
                $equipments = [];
                foreach ($results as $key => $equipment) {
                    $category = $equipment->getIdEquipment()->getIdCategory()->getIdLabel()->getLabel();
                    $array['label'] = $equipment->getIdEquipment()->getIdLabel()->getLabel();
                    $array['picture'] = $equipment->getIdEquipment()->getIcon();
                    $equipments[$category][$key] = $array;
                }
                $this->render('housing.viewHousing.equipment', compact('housing','pictures', 'equipments'));
            }
            else
            {
                $this->render('error.search');
            }
        }
        else
        {
            $this->render('error.search');
        }
    }
    public function listHousing()
    {
        $housingTranslation = Language::translation("housing");
        $equipmentTranslation = Language::translation("equipment");
        $translation = array_merge($housingTranslation, $equipmentTranslation);
        $query = static::getConnection()->createQuery("
            SELECT u, p, h 
            FROM Housing h JOIN h.idProperty p JOIN p.idUser u
            WHERE h.state = 1 AND h.visibility = 1 ORDER BY u.idUser, p.idProperty ASC
        ");
        $housings = $query->getResult();
        $pictures = [];
        $type = [];
        foreach ($housings as $housing) 
        {
            $results = $this->getConnection()->getRepository('HousingEquipment')->findByIdHousing($housing->getId());
            $equipments = [];
            foreach ($results as $key => $equipment) {
                $category = $equipment->getIdEquipment()->getIdCategory()->getIdLabel()->getLabel();
                $array['label'] = $equipment->getIdEquipment()->getIdLabel()->getLabel();
                $array['picture'] = $equipment->getIdEquipment()->getIcon();
                $equipments[$category][$key] = $array;
            }
            $result = $this->getConnection()->getRepository('Picture')->findByIdHousing($housing->getId());
            if (!empty($result))
            {
                $namePicture = explode('.',$result[0]->getName());
                $picture = "web/pictures/Housing/miniature/".$namePicture[0]."-miniature.".$namePicture[1];
            }
            else $picture =  "web/pictures/iconLarge.png";
            $pictures[$housing->getId()] = $picture;
            $type[$housing->getId()] = $translation[$housing->getIdType()->getIdLabel()->getLabel()];
        }
        Session::set('type', $type);
        Session::set('pictures', $pictures);
        Session::set('housings', $housings);
        Session::set('translation', $translation);
        Session::set('equipments', $equipments);
    }
    public function listProperties($get = null)
    {
        if ((isset($_GET['r']) && (($_GET['r'] % 12) == 0)) || !empty($get))
        {
            $value['r'] = (isset($_GET['r'])) ? $_GET['r'] : $get;
            Session::set('settings', $value);

            $properties = $this->getConnection()->getRepository('Property')->findAll();
            $size = sizeof($properties);

            $offset = (isset($_GET['r'])) ? $_GET['r'] : ((!empty($get)) ? $get : 12);
            $limit = $offset - 12;
            $dql = "SELECT h FROM Property h ORDER BY h.idProperty DESC";
            $query = $this->getConnection()->createQuery($dql)
                           ->setFirstResult($limit)
                           ->setMaxResults($offset);

            $results = $query->getResult();
            $properties = [];
            foreach ($results as $key => $property) {
                $properties[$key]['picture'] = "web/pictures/iconLarge.png";
                $properties[$key]['city'] = $property->getCity();
                $properties[$key]['street'] = $property->getStreet();
                $properties[$key]['number'] = $property->getNumber();
                $properties[$key]['zipCode'] = $property->getZipCode();
                $properties[$key]["idProperty"] = $property->getId();
            }
            $this->render('housing.listProperties', compact('properties', 'size'));
        }
        else
        {
            $this->render('error.index');
        }
    }
    public function changeVisibility()
    {
        var_dump($_POST);
        $housing = $this->getConnection()->getRepository('Housing')->find($_POST['idHousing']);
        $housing->setVisibility(intval($_POST['state']));
        $this->getConnection()->persist($housing);
        $this->getConnection()->flush(); 
    }
}
