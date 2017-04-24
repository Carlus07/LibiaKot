<div class="container text-center">
  <div class="frameHousing">
    <img src="web/pictures/housing.png" class="housingPicture img-responsive"/>
    <p class="inputNeeded">* <?php echo $translation['requiredField']; ?></p>
    <input type="hidden" id="errorAddHousing" value="<?php echo (!empty($errors)) ? $errors : ""; ?>"/>
    <form action="?w=housing.addHousing" method="POST">
      <input type="hidden" id="id" name="id" value="<?php if (isset($_GET['id'])) echo $_GET['id']; ?>" />
      <input type="hidden" id="method" name="method" value="<?php if (isset($_GET['m'])) echo $_GET['m']; ?>" />
      <input type="hidden" id="code" value="" name="code"/>
      <?php 
      $i = 1;
      $initialized = false;
      if (!isset($_GET['m']) || ((isset($_GET['m']) && ($_GET['m'] == 'updateProperty'))))
      {
        $initialized = (isset($_GET['m']) && ($_GET['m'] == 'updateProperty'));
        ?>
        <fieldset>
          <legend><span><?php echo $i;?></span><?php echo $translation['addressProperty']; ?></legend>
          <?php $i++; ?>
          <div class='row'>
            <div class='col-sm-4'>
              <div class='form-group'>
                <label for="zipCode">*<?php echo $translation['zipCode']; ?></label>
                <select class="form-control" id="zipCode" name="zipCode">
                  <option value="null">----</option>
                  <?php
                  foreach($zipCodes as $zipCode)
                  {
                    echo (($initialized) && ($accomodation->getZipCode() == $zipCode)) ? '<option value="'.$zipCode.'" selected>'.$zipCode.'</option>' : '<option value="'.$zipCode.'">'.$zipCode.'</option>';   
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class='col-sm-8'>
              <div class='form-group'>
                <label for="city">*<?php echo $translation['city']; ?></label>
                <select class="form-control" id="city" name="city" <?php echo ($initialized) ? "" : "disabled"; ?>>
                  <?php echo ($initialized) ? "<option>".$accomodation->getCity()."</option>" : "<option>----</option>"; ?>
                </select>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-9'>
              <div class='form-group'>
                <label for="street">*<?php echo $translation['address']; ?></label>
                <input class="form-control" id="street" name="street" required="true" type="text" <?php echo ($initialized) ? "" : "disabled";?> value="<?php echo ($initialized) ? utf8_encode($accomodation->getStreet()) : ""; ?>"/>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='form-group'>
                <label for="number">*<?php echo $translation['number']; ?></label>
                <input class="form-control" id="number" name="number" required="true" size="4" type="number" <?php echo ($initialized) ? "" : "disabled"; ?> value="<?php echo ($initialized) ? $accomodation->getNumber() : ""; ?>"/>
              </div>
            </div>
          </div>
          <div id="map-canvas" class="mapAddHousing"></div>
          <input type="hidden" name="GPSPosition" class="GPSPosition" value="<?php echo ($initialized) ? $accomodation->getGPSPosition() : ""; ?>" />
        </fieldset>
        <fieldset>
          <legend><span><?php echo $i;?></span><?php echo $translation['detailProperty']; ?></legend>
          <?php $i++; ?>
          <div class='row'>
            <div class='col-sm-12'>
              <div class="form-group">
                <label for="easeNearby"><?php echo $translation['easeNearby']; ?></label>
                <textarea class="form-control" name="easeNearby" id="easeNearby" style="resize:none;" rows="3"><?php echo ($initialized) ? utf8_encode($accomodation->getEaseNearby()) : ""; ?></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="domiciliation">*<?php echo $translation['domiciliation']; ?></label>
                <select class="form-control" id="domiciliation" name="domiciliation">
                  <option value="0" <?php echo (($initialized) && ($accomodation->getDomiciliation() == 0)) ? "selected" : ""; ?>><?php echo $translation['no']; ?></option>
                  <option value="1" <?php echo (($initialized) && ($accomodation->getDomiciliation() == 1)) ? "selected" : ""; ?>><?php echo $translation['yes']; ?></option>
                  <option value="2" <?php echo (($initialized) && ($accomodation->getDomiciliation() == 2)) ? "selected" : ""; ?>><?php echo $translation['onCondition']; ?></option>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="targetAudience">*<?php echo $translation['targetAudience']; ?></label>
                <select class="form-control" id="targetAudience" name="targetAudience">
                  <option value="0" <?php echo (($initialized) && ($accomodation->getTargetAudience() == 0)) ? "selected" : ""; ?>><?php echo $translation['everybody']; ?></option>
                  <option value="1" <?php echo (($initialized) && ($accomodation->getTargetAudience() == 1)) ? "selected" : ""; ?>><?php echo $translation['onlyGirls']; ?></option>
                  <option value="2" <?php echo (($initialized) && ($accomodation->getTargetAudience() == 2)) ? "selected" : ""; ?>><?php echo $translation['onlyBoys']; ?></option>
                  <option value="3" <?php echo (($initialized) && ($accomodation->getTargetAudience() == 3)) ? "selected" : ""; ?>><?php echo $translation['onlyHighSchool']; ?></option>
                  <option value="4" <?php echo (($initialized) && ($accomodation->getTargetAudience() == 4)) ? "selected" : ""; ?>><?php echo $translation['onlyUniversity']; ?></option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <ul>
                <li>
                  <div class="col-xs-2">
                    <img src="web\pictures\Equipment\jardin.png" class="equipmentLogo">
                  </div>
                  <div class="col-xs-7">
                    <span class="labelEquipment"><?php echo $translation['garden']; ?></span>
                  </div>
                  <div class="col-xs-3">
                    <label for="garden"><?php echo (($initialized) && ($accomodation->getGarden())) ? $translation['yes'] : $translation['no']; ?></label>
                    <input type="checkbox" <?php echo (($initialized) && ($accomodation->getGarden())) ? 'checked data-state="true"' : 'data-state="false"' ?> name="garden" class="checkBox" id="garden">
                  </div>
                </li>
                <li>
                  <div class="col-xs-2">
                    <img src="web\pictures\Equipment\15b332b6927.png" class="equipmentLogo">
                  </div>
                  <div class="col-xs-7">
                    <span class="labelEquipment"><?php echo $translation['terrace']; ?></span>
                  </div>
                  <div class="col-xs-3">
                    <label for="terrace"><?php echo (($initialized) && ($accomodation->getTerrace())) ? $translation['yes'] : $translation['no']; ?></label>
                    <input type="checkbox" <?php echo (($initialized) && ($accomodation->getTerrace())) ? 'checked data-state="true"' : 'data-state="false"' ?> name="terrace" class="checkBox" id="terrace">
                  </div>
                </li>
                <li>
                  <div class="col-xs-2">
                    <img src="web\pictures\Equipment\15b33312f3e.png" class="equipmentLogo">
                  </div>
                  <div class="col-xs-7">
                    <span class="labelEquipment"><?php echo $translation['bicycleParking']; ?></span>
                  </div>
                  <div class="col-xs-3">
                    <label for="bicycleParking"><?php echo (($initialized) && ($accomodation->getBicycleParking())) ? $translation['yes'] : $translation['no']; ?></label>
                    <input type="checkbox" <?php echo (($initialized) && ($accomodation->getBicycleParking())) ? 'checked data-state="true"' : 'data-state="false"' ?> name="bicycleParking" class="checkBox" id="bicycleParking">
                  </div>
                </li>
                <li>
                  <div class="col-xs-2">
                    <img src="web\pictures\Equipment\15b3332ba1c.png" class="equipmentLogo">
                  </div>
                  <div class="col-xs-7">
                    <span class="labelEquipment"><?php echo $translation['carParking']; ?></span>
                  </div>
                  <div class="col-xs-3">
                    <label for="carParking"><?php echo (($initialized) && ($accomodation->getCarParking())) ? $translation['yes'] : $translation['no']; ?></label>
                    <input type="checkbox" <?php echo (($initialized) && ($accomodation->getCarParking())) ? 'checked data-state="true"' : 'data-state="false"' ?> name="carParking" class="checkBox" id="carParking">
                  </div>
                </li>
              </ul>
            </div>
            <div class="col-sm-6">
              <ul>
                <li>
                  <div class="col-xs-2">
                    <img src="web\pictures\Equipment\pmr.png" class="equipmentLogo">
                  </div>
                  <div class="col-xs-7">
                    <span class="labelEquipment"><?php echo $translation['disabledAccess']; ?></span>
                  </div>
                  <div class="col-xs-3">
                    <label for="disabledAccess"><?php echo (($initialized) && ($accomodation->getDisabledAccess())) ? $translation['yes'] : $translation['no']; ?></label>
                    <input type="checkbox" <?php echo (($initialized) && ($accomodation->getDisabledAccess())) ? 'checked data-state="true"' : 'data-state="false"' ?> name="disabledAccess" class="checkBox" id="disabledAccess">
                  </div>
                </li>
                <li>
                  <div class="col-xs-2">
                    <img src="web\pictures\Equipment\smoke.png" class="equipmentLogo">
                  </div>
                  <div class="col-xs-7">
                    <span class="labelEquipment"><?php echo $translation['smoker']; ?></span>
                  </div>
                  <div class="col-xs-3">
                    <label for="smoker"><?php echo (($initialized) && ($accomodation->getSmoker())) ? $translation['yes'] : $translation['no']; ?></label>
                    <input type="checkbox" <?php echo (($initialized) && ($accomodation->getSmoker())) ? 'checked data-state="true"' : 'data-state="false"' ?> name="smoker" class="checkBox" id="smoker">
                  </div>
                </li>
                <li>
                  <div class="col-xs-2">
                    <img src="web\pictures\Equipment\peb.png" class="equipmentLogo">
                  </div>
                  <div class="col-xs-7">
                    <span class="labelEquipment"><?php echo $translation['realizedPEB']; ?></span>
                  </div>
                  <div class="col-xs-3">
                    <label for="realizedPEB"><?php echo (($initialized) && ($accomodation->getPEB())) ? $translation['yes'] : $translation['no']; ?></label>
                    <input type="checkbox" <?php echo (($initialized) && ($accomodation->getPEB())) ? 'checked data-state="true"' : 'data-state="false"' ?> name="realizedPEB" class="checkBox" id="realizedPEB">
                  </div>
                </li>
                <li>
                  <div class="col-xs-2">
                    <img src="web\pictures\Equipment\pet.png" class="equipmentLogo">
                  </div>
                  <div class="col-xs-7">
                    <span class="labelEquipment"><?php echo $translation['animalAllowded']; ?></span>
                  </div>
                  <div class="col-xs-3">
                    <label for="animalAllowded"><?php echo (($initialized) && ($accomodation->getAnimal())) ? $translation['yes'] : $translation['no']; ?></label>
                    <input type="checkbox" <?php echo (($initialized) && ($accomodation->getAnimal())) ? 'checked data-state="true"' : 'data-state="false"' ?> name="animalAllowded" class="checkBox" id="animalAllowded">
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <?php
        }
        if (!isset($_GET['m']) || ((isset($_GET['m']) && (($_GET['m'] == 'updateHousing') || ($_GET['m'] == 'addHousing')))))
        {
          $initialized = (isset($_GET['m']) && ($_GET['m'] == 'updateHousing'));
          ?>
          <fieldset>
            <legend><span><?php echo $i;?></span><?php echo ($initialized) ? $translation['updateHousing'] : $translation['addHousing']; ?></legend>
            <?php 
            $i++; 
            if (!isset($_GET['m']))
            {
              echo "<div class='alert alert-info infoHousing' role='alert'>
              <span><i class='fa fa-info-circle' aria-hidden='true'></i>  ".$translation['infoProperty']."</span>
            </div>";
          } 
          ?>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="housingType">*<?php echo $translation['housingType']; ?></label>
                <select class="form-control" id="housingType" name="housingType">
                  <?php
                  foreach ($menus as $label => $menu) {
                    foreach ($menu as $labelType => $idType) {
                      foreach ($idType as $idSubType => $subMenu) {
                        if (empty($subMenu)) 
                        {
                          echo (($initialized) && ($accomodation->getIdType()->getId() == key($idType))) ? '<option selected data-type="'.$label.'"  value="'.key($idType).'">'.$labelType.'</option>' : '<option data-type="'.$label.'"  value="'.key($idType).'">'.$labelType.'</option>';
                        }
                        foreach ($subMenu as $idSubMenu => $labelSubMenu) {
                          echo (($initialized) && (null != ($accomodation->getIdSubType())) && ($accomodation->getIdSubType()->getId() == $idSubMenu)) ? '<option selected data-type="'.$label.'" value="'.key($idType).'+'.$idSubMenu.'">'.$labelSubMenu.'</option>' : '<option data-type="'.$label.'" value="'.key($idType).'+'.$idSubMenu.'">'.$labelSubMenu.'</option>';
                        }
                      }
                    }
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="availability">*<?php echo $translation['availability']; ?></label>
                <div class='input-group date' id='datetimepicker'>
                  <input type='text' id="availability" value="<?php echo ($initialized) ? $accomodation->getAvailability()->format('Y-m-d') : "" ?>" name="availability" class="form-control">
                  <span class="input-group-addon spanCalendar">
                    <span><i class="fa fa-calendar" aria-hidden="true"></i></span>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="alert alert-info infoHousing fade" role="alert">
                <span><strong><?php echo $translation['apartment']; ?> : </strong><?php echo $translation['infoApartment']; ?></span><br>
                <span><strong><?php echo $translation['house']; ?> : </strong><?php echo $translation['infoHouse']; ?></span><br>
                <span><strong><?php echo $translation['flatsharing']; ?> : </strong><?php echo $translation['infoFlatsharing']; ?></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 infoCapacity fade">
              <label for="capacity">*<?php echo $translation['capacity']; ?></label>
              <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                <input type="number" class="form-control" id="capacity" size="4" value="<?php echo ($initialized) ? $accomodation->getCapacity() : "" ?>" name="capacity">
                <div class="input-group-addon"><?php echo $translation['peoples']; ?></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 infoSpaceAvailable fade">
              <label for="spaceAvailable">*<?php echo $translation['spaceAvailable']; ?></label>
              <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                <input type="number" class="form-control" id="spaceAvailable" value="<?php echo ($initialized) ? $accomodation->getSpaceAvailable() : "" ?>" size="4" name="spaceAvailable">
                <div class="input-group-addon"><?php echo $translation['spaces']; ?></div>
              </div>
            </div>
          </div>
        </fieldset>
        <fieldset>
          <legend><span><?php echo $i;?></span><?php echo $translation['housingDetails']; ?></legend>
          <?php $i++; ?>
          <div class="row">
            <div class="col-sm-6">
              <label for="area">*<?php echo $translation['area']; ?></label>
              <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                <input type="number" class="form-control" id="area" value="<?php echo ($initialized) ? $accomodation->getArea() : "" ?>" name="area" required="true">
                <div class="input-group-addon">m²</div>
              </div>
            </div>
            <div class='col-sm-6'>
              <div class='form-group'>
                <label for="floor"><?php echo $translation['floor']; ?></label>
                <input class="form-control" id="floor" name="floor" required="false" value="<?php echo ($initialized) ? $accomodation->getFloor() : "" ?>" size="4" type="number"/>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="bathroom">*<?php echo $translation['bathroom']; ?></label>
                <select class="form-control" id="bathroom" name="bathroom">
                  <option value="1" <?php echo (($initialized) && ($accomodation->getBathroom() == 1)) ? "selected" : ""; ?>><?php echo $translation['common']; ?></option>
                  <option value="2" <?php echo (($initialized) && ($accomodation->getBathroom() == 2)) ? "selected" : ""; ?>><?php echo $translation['privateRoom']; ?></option>
                  <option value="3" <?php echo (($initialized) && ($accomodation->getBathroom() == 3)) ? "selected" : ""; ?>><?php echo $translation['privateSeparate']; ?></option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="kitchen">*<?php echo $translation['kitchen']; ?></label>
                <select class="form-control" id="kitchen" name="kitchen">
                  <option value="1" <?php echo (($initialized) && ($accomodation->getKitchen() == 1)) ? "selected" : ""; ?>><?php echo $translation['common']; ?></option>
                  <option value="2" <?php echo (($initialized) && ($accomodation->getKitchen() == 2)) ? "selected" : ""; ?>><?php echo $translation['privateRoom']; ?></option>
                  <option value="3" <?php echo (($initialized) && ($accomodation->getKitchen() == 3)) ? "selected" : ""; ?>><?php echo $translation['privateSeparate']; ?></option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="heating">*<?php echo $translation['heating']; ?></label>
                <select class="form-control" id="heating" name="heating">
                  <option value="1" <?php echo (($initialized) && ($accomodation->getHeating() == 1)) ? "selected" : ""; ?>><?php echo $translation['central']; ?></option>
                  <option value="2" <?php echo (($initialized) && ($accomodation->getKitchen() == 2)) ? "selected" : ""; ?>><?php echo $translation['electric']; ?></option>
                  <option value="3" <?php echo (($initialized) && ($accomodation->getKitchen() == 3)) ? "selected" : ""; ?>><?php echo $translation['gaz']; ?></option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <?php
            foreach($associativeArray as $idCategory => $value)
            {
              echo '<div class="col-sm-6">';
              echo '<h4 style="color:#55ab26;">'.$translation[$value['category']].'</h4>';
              echo '<ul>';
              if (isset($value['equipment']))
              {
                foreach ($value['equipment'] as $idEquipment => $equipment)
                {
                  $method = "get".ucfirst($equipment["name"]);
                  echo '<li>';
                  echo '<div class="col-xs-2">';
                  $src = (isset($equipment["icon"])) ? $equipment["icon"] : '';
                  echo '<img class="equipmentLogo" src="'.$src.'"/>';
                  echo '</div>';
                  echo '<div class="col-xs-7">';
                  echo '<span class="labelEquipment">'.$translation[$equipment["name"]].'</span>';
                  echo '</div>';
                  echo '<div class="col-xs-3" style="z-index:2;">';
                  echo (($initialized) && (isset($housingEquipment[$equipment['name']]))) ? '<label for="'.$equipment["name"].'">'.$translation['yes'].'</label>' :'<label for="'.$equipment["name"].'">'.$translation['no'].'</label>';
                  echo (($initialized) && (isset($housingEquipment[$equipment['name']]))) ? '<input type="checkbox" checked data-state="true" name="'.$equipment["name"].'" class="checkBox" id="'.$equipment["name"].'">' : '<input type="checkbox" data-state="false" name="'.$equipment["name"].'" class="checkBox" id="'.$equipment["name"].'">';
                  echo '</div>';
                  echo '</li>';
                }
              }
              echo '</ul>';
              echo '</div>';
            }
            ?>
          </div>
        </fieldset>
        <fieldset>
          <legend><span><?php echo $i;?></span><?php echo $translation['addPictures']; ?></legend>
          <?php $i++; ?>
          <div class="row">
            <div class="col-xs-12">
              <div class="alert alert-info infoHousing" role="alert">
                <span><i class="fa fa-info-circle" aria-hidden="true"></i><?php echo '  '.$translation['infoPicture']; ?></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 divUploadPictures">
            <?php
              if (isset($pictures))
              {
                  foreach ($pictures as $picture) {
                  $split = explode('.',$picture->getName());
                  $repertory = 'web/pictures/Housing/miniature/'.$split[0].'-miniature.'.$split[1];
                  echo '<div value="'.$split[0].'" alt="old" class="carre">
                      <img class="vignette" src="'.$repertory.'" style="max-width:175px;">
                      <i class="fa fa-times-circle cancelUploadHousing" aria-hidden="true"></i>
                    </div>';
                }
              }
            ?>
            </div>
          </div>
          <div class="row">
            <input type="file" name="fileUploadPictures" id="fileUploadPictures" multiple style="display:none;">
            <div class="col-xs-12">
              <div class="btn btn-lg btn-success btn-signin buttonAddPicture"><span c><i class="fa fa-plus-circle" aria-hidden="true"></i><?php echo '  '.$translation['addPictureTitle']; ?></span></div>
            </div>
          </div>
        </fieldset>
        <fieldset>
          <legend><span><?php echo $i;?></span><?php echo $translation['rent']; ?></legend>
          <?php $i++; ?>
          <div class="row">
            <div class="col-sm-6">
              <label for="rent">*<?php echo $translation['rent']; ?></label>
              <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                <input type="number" class="form-control" id="sm" name="rent" value="<?php echo ($initialized) ? $accomodation->getRent() : "" ?>" required="true">
                <div class="input-group-addon">€</div>
              </div>
            </div>
            <div class="col-sm-6">
              <label for="charge">*<?php echo $translation['charge']; ?></label>
              <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                <input type="number" class="form-control" id="sm" name="charge" value="<?php echo ($initialized) ? $accomodation->getCharge() : "" ?>" required="true">
                <div class="input-group-addon">€</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <label for="deposit">*<?php echo $translation['deposit']; ?></label>
              <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                <input type="number" class="form-control" id="sm" name="deposit" value="<?php echo ($initialized) ? $accomodation->getDeposit() : "" ?>" required="true">
                <div class="input-group-addon">€</div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="rentalDuration">*<?php echo $translation['rentalDuration']; ?></label>
                <select class="form-control" id="rentalDuration" name="rentalDuration">
                  <option value="1" <?php echo (($initialized) && ($accomodation->getRentalDuration() == 1)) ? "selected" : ""; ?>><?php echo '12 '.$translation['month']; ?></option>
                  <option value="2" <?php echo (($initialized) && ($accomodation->getRentalDuration() == 2)) ? "selected" : ""; ?>><?php echo '10 '.$translation['month']; ?></optio
                  <option value="3" <?php echo (($initialized) && ($accomodation->getRentalDuration() == 3)) ? "selected" : ""; ?>><?php echo $translation['firstSemester']; ?></option>
                  <option value="4" <?php echo (($initialized) && ($accomodation->getRentalDuration() == 4)) ? "selected" : ""; ?>><?php echo $translation['secondSemester']; ?></option>
                  <option value="5" <?php echo (($initialized) && ($accomodation->getRentalDuration() == 5)) ? "selected" : ""; ?>><?php echo $translation['summerHoliday']; ?></option>
                  <option value="6" <?php echo (($initialized) && ($accomodation->getRentalDuration() == 6)) ? "selected" : ""; ?>><?php echo $translation['other']; ?></option>
                </select>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-12'>
              <div class="form-group">
                <label for="rentComment"><?php echo $translation['rentComment']; ?></label>
                <textarea class="form-control" name="rentComment" id="rentComment" style="resize:none;" rows="3"><?php echo ($initialized) ? utf8_encode($accomodation->getRentComment()) : "" ?></textarea>
              </div>
            </div>
          </div>
        </fieldset>
        <?php
      }
      ?>
      <div class="row">
        <button class="btn btn-lg btn-success btn-signin" style="width:100%;margin-top:20px;" type="submit"><?php echo ($initialized) ? $translation["updateAdvert"] : $translation["saveAdvert"] ?></button>
      </div>
    </form>
  </div>
</div>
