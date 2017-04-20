<div class="container text-center">
  <div class="frameHousing">
    <img src="web/pictures/housing.png" class="housingPicture img-responsive"/>
    <p class="inputNeeded">* <?php echo $translation['requiredField']; ?></p>
    <input type="hidden" id="errorAddHousing" value="<?php echo (!empty($errors)) ? $errors : ""; ?>"/>
    <form action="?w=housing.addHousing" method="POST">
      <input type="hidden" id="code" value="" name="code"/>
      <fieldset>
        <legend><span>1</span><?php echo $translation['addressProperty']; ?></legend>
        <div class='row'>
          <div class='col-sm-4'>
            <div class='form-group'>
              <label for="zipCode">*<?php echo $translation['zipCode']; ?></label>
              <select class="form-control" id="zipCode" name="zipCode">
                <option value="null">----</option>
                <?php
                foreach($zipCodes as $zipCode)
                {
                  echo '<option value="'.$zipCode.'">'.$zipCode.'</option>';
                }
                ?>
              </select>
            </div>
          </div>
          <div class='col-sm-8'>
            <div class='form-group'>
              <label for="city">*<?php echo $translation['city']; ?></label>
              <select class="form-control" id="city" name="city" disabled>
                <option>----</option>
              </select>
            </div>
          </div>
        </div>
        <div class='row'>
          <div class='col-sm-9'>
            <div class='form-group'>
              <label for="street">*<?php echo $translation['address']; ?></label>
              <input class="form-control" id="street" name="street" required="true" type="text" disabled/>
            </div>
          </div>
          <div class='col-sm-3'>
            <div class='form-group'>
              <label for="number">*<?php echo $translation['number']; ?></label>
              <input class="form-control" id="number" name="number" required="true" size="4" type="number" disabled/>
            </div>
          </div>
        </div>
        <div id="map-canvas" class="mapAddHousing"></div>
        <input type="hidden" name="GPSPosition" class="GPSPosition" value="" />
      </fieldset>
      <fieldset>
        <legend><span>2</span><?php echo $translation['detailProperty']; ?></legend>
        <div class='row'>
          <div class='col-sm-12'>
            <div class="form-group">
              <label for="easeNearby"><?php echo $translation['easeNearby']; ?></label>
              <textarea class="form-control" name="easeNearby" id="easeNearby" style="resize:none;" rows="3"></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="domiciliation">*<?php echo $translation['domiciliation']; ?></label>
              <select class="form-control" id="domiciliation" name="domiciliation">
                <option value="0"><?php echo $translation['no']; ?></option>
                <option value="1"><?php echo $translation['yes']; ?></option>
                <option value="2"><?php echo $translation['onCondition']; ?></option>
              </select>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="targetAudience">*<?php echo $translation['targetAudience']; ?></label>
              <select class="form-control" id="targetAudience" name="targetAudience">
                <option value="0"><?php echo $translation['everybody']; ?></option>
                <option value="1"><?php echo $translation['onlyGirls']; ?></option>
                <option value="2"><?php echo $translation['onlyBoys']; ?></option>
                <option value="3"><?php echo $translation['onlyHighSchool']; ?></option>
                <option value="4"><?php echo $translation['onlyUniversity']; ?></option>
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
                  <label for="garden"><?php echo $translation['no']; ?></label>
                  <input type="checkbox" data-state="false" name="garden" class="checkBox" id="garden">
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
                  <label for="terrace"><?php echo $translation['no']; ?></label>
                  <input type="checkbox" data-state="false" name="terrace" class="checkBox" id="terrace">
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
                  <label for="bicycleParking"><?php echo $translation['no']; ?></label>
                  <input type="checkbox" data-state="false" name="bicycleParking" class="checkBox" id="bicycleParking">
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
                  <label for="carParking"><?php echo $translation['no']; ?></label>
                  <input type="checkbox" data-state="false" name="carParking" class="checkBox" id="carParking">
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
                  <label for="disabledAccess"><?php echo $translation['no']; ?></label>
                  <input type="checkbox" data-state="false" name="disabledAccess" class="checkBox" id="disabledAccess">
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
                  <label for="smoker"><?php echo $translation['no']; ?></label>
                  <input type="checkbox" data-state="false" name="smoker" class="checkBox" id="smoker">
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
                  <label for="realizedPEB"><?php echo $translation['no']; ?></label>
                  <input type="checkbox" data-state="false" name="realizedPEB" class="checkBox" id="realizedPEB">
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
                  <label for="animalAllowded"><?php echo $translation['no']; ?></label>
                  <input type="checkbox" data-state="false" name="animalAllowded" class="checkBox" id="animalAllowded">
                </div>
              </li>
            </ul>
          </div>
        </div>
        <fieldset>
          <legend><span>3</span><?php echo $translation['addHousing']; ?></legend>
          <div class="alert alert-info infoHousing" role="alert">
            <span><i class="fa fa-info-circle" aria-hidden="true"></i><?php echo '  '.$translation['infoProperty']; ?></span>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="housingType">*<?php echo $translation['housingType']; ?></label>
                <select class="form-control" id="housingType" name="housingType">
                  <?php
                    foreach ($menus as $labelType => $idType) {
                      foreach ($idType as $idSubType => $subMenu) {
                        if (empty($subMenu)) echo '<option value="'.key($idType).'">'.$labelType.'</option>';
                        foreach ($subMenu as $idSubMenu => $labelSubMenu) {
                            echo '<option value="'.key($idType).'+'.$idSubMenu.'">'.$labelSubMenu.'</option>';
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
                  <input type='text' id="availability" name="availability" class="form-control">
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
                <input type="number" class="form-control" id="capacity" size="4" name="capacity">
                <div class="input-group-addon"><?php echo $translation['peoples']; ?></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 infoSpaceAvailable fade">
              <label for="spaceAvailable">*<?php echo $translation['spaceAvailable']; ?></label>
              <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                <input type="number" class="form-control" id="spaceAvailable" size="4" name="spaceAvailable">
                <div class="input-group-addon"><?php echo $translation['spaces']; ?></div>
              </div>
            </div>
          </div>
        </fieldset>
        <fieldset>
          <legend><span>4</span><?php echo $translation['housingDetails']; ?></legend>
          <div class="row">
            <div class="col-sm-6">
              <label for="area">*<?php echo $translation['area']; ?></label>
              <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                <input type="number" class="form-control" id="area" name="area" required="true">
                <div class="input-group-addon">m²</div>
              </div>
            </div>
            <div class='col-sm-6'>
              <div class='form-group'>
                <label for="floor"><?php echo $translation['floor']; ?></label>
                <input class="form-control" id="floor" name="floor" required="false" size="4" type="number"/>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="bathroom">*<?php echo $translation['bathroom']; ?></label>
                <select class="form-control" id="bathroom" name="bathroom">
                  <option value="1"><?php echo $translation['common']; ?></option>
                  <option value="2"><?php echo $translation['privateRoom']; ?></option>
                  <option value="3"><?php echo $translation['privateSeparate']; ?></option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="kitchen">*<?php echo $translation['kitchen']; ?></label>
                <select class="form-control" id="kitchen" name="kitchen">
                  <option value="1"><?php echo $translation['common']; ?></option>
                  <option value="2"><?php echo $translation['privateRoom']; ?></option>
                  <option value="3"><?php echo $translation['privateSeparate']; ?></option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="heating">*<?php echo $translation['heating']; ?></label>
                <select class="form-control" id="heating" name="heating">
                  <option value="1"><?php echo $translation['central']; ?></option>
                  <option value="2"><?php echo $translation['electric']; ?></option>
                  <option value="3"><?php echo $translation['gaz']; ?></option>
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
                  echo '<li>';
                  echo '<div class="col-xs-2">';
                  $src = (isset($equipment["icon"])) ? $equipment["icon"] : '';
                  echo '<img class="equipmentLogo" src="'.$src.'"/>';
                  echo '</div>';
                  echo '<div class="col-xs-7">';
                  echo '<span class="labelEquipment">'.$translation[$equipment["name"]].'</span>';
                  echo '</div>';
                  echo '<div class="col-xs-3" style="z-index:2;">';
                  echo '<label for="'.$equipment["name"].'">'.$translation['no'].'</label>';
                  echo '<input type="checkbox" data-state="false" name="'.$equipment["name"].'" class="checkBox" id="'.$equipment["name"].'">';
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
          <legend><span>5</span><?php echo $translation['addPictures']; ?></legend>
          <div class="row">
            <div class="col-xs-12">
              <div class="alert alert-info infoHousing" role="alert">
                <span><i class="fa fa-info-circle" aria-hidden="true"></i><?php echo '  '.$translation['infoPicture']; ?></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 divUploadPictures">
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
          <legend><span>6</span><?php echo $translation['rent']; ?></legend>
          <div class="row">
            <div class="col-sm-6">
              <label for="rent">*<?php echo $translation['rent']; ?></label>
              <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                <input type="number" class="form-control" id="sm" name="rent" required="true">
                <div class="input-group-addon">€</div>
              </div>
            </div>
            <div class="col-sm-6">
              <label for="charge">*<?php echo $translation['charge']; ?></label>
              <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                <input type="number" class="form-control" id="sm" name="charge" required="true">
                <div class="input-group-addon">€</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <label for="deposit">*<?php echo $translation['deposit']; ?></label>
              <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                <input type="number" class="form-control" id="sm" name="deposit" required="true">
                <div class="input-group-addon">€</div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="rentalDuration">*<?php echo $translation['rentalDuration']; ?></label>
                <select class="form-control" id="rentalDuration" name="rentalDuration">
                  <option value="1"><?php echo '12 '.$translation['month']; ?></option>
                  <option value="2"><?php echo '10 '.$translation['month']; ?></option>
                  <option value="3"><?php echo $translation['firstSemester']; ?></option>
                  <option value="4"><?php echo $translation['secondSemester']; ?></option>
                  <option value="5"><?php echo $translation['summerHoliday']; ?></option>
                  <option value="6"><?php echo $translation['other']; ?></option>
                </select>
              </div>
            </div>
          </div>
          <div class='row'>
          <div class='col-sm-12'>
            <div class="form-group">
              <label for="rentComment"><?php echo $translation['rentComment']; ?></label>
              <textarea class="form-control" name="rentComment" id="rentComment" style="resize:none;" rows="3"></textarea>
            </div>
          </div>
        </div>
        </fieldset>
        <button class="btn btn-lg btn-success btn-signin" style="width:100%;" type="submit"><?php echo $translation["saveAdvert"]; ?></button>
      </form>
    </div>
  </div>
