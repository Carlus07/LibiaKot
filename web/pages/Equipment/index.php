<div class="container">
	<div class="row col-md-offset-1 col-md-10 col-md-offset-1"> 
		<input type="hidden" id="errorEquipment" value="<?php echo (!empty($errors)) ? $errors : ""; ?>"/>
		<input type="file" name="fileUpdateLogo" id="fileUpdateLogo" style="display:none;">
		<ul class="tableEquipment list-group">
			<li class="col-xs-6">
				<ul class="list-group" id="equipmentSort">
					<?php
						foreach($associativeArray as $idCategory => $value)
						{
							echo '<li class="list-group-item active" data-idCategory="'.$idCategory.'">'.$translation[$value['category']].'</li>';
							if (isset($value['equipment']))
							{
								foreach ($value['equipment'] as $idEquipment => $equipment) {
									$src = (isset($equipment["icon"])) ? $equipment["icon"] : '';
									echo '<li class="list-group-item equipment" data-idEquipment="'.$idEquipment.'">
										<div class="col-xs-12"><img class="pictureEquipment" src="'.$src.'"/>'.$translation[$equipment["name"]].'
										</div>
										<div class="dropdown dropdownSettingEquipment col-xs-11">
									        <a href="#" data-toggle="dropdown" class="linkEquipmentSetting dropdown-toggle">
									        	<i class="fa fa-cog settingEquipment" aria-hidden="true"></i>
									        </a>
									        <ul class="col-xs-11 dropdown-menu">
									            <li class="updateLogo"><a href="#">'.$translation['changeLogo'].'</a></li>
									            <li class="deleteEquipment"><a href="#">'.$translation['deleteEquipment'].'</a></li>
									        </ul>
									    </div>
									</li>';
								}
							}
						}
					?>
				</ul>
			</li >
			<li class="col-xs-6">
				<ul class="list-group" id="equipmentFree">
					<?php
						foreach($notAssociatedEquipment as $idEquipment => $value)
						{
							$name = ($translation[$value["name"]] != '') ? $translation[$value['name']] : $value['name'];
							echo '<li class="list-group-item equipment" data-idEquipment="'.$idEquipment.'">
										<div class="col-xs-12"><img class="pictureEquipment" src="'.$value['picture'].'"/>'.$name.'
										</div>
										<div class="dropdown dropdownSettingEquipment col-xs-11">
									        <a href="#" data-toggle="dropdown" class="linkEquipmentSetting dropdown-toggle">
									        	<i class="fa fa-cog settingEquipment" aria-hidden="true"></i>
									        </a>
									        <ul class="col-xs-11 dropdown-menu">
									            <li class="updateLogo"><a href="#">'.$translation['changeLogo'].'</a></li>
									            <li class="deleteEquipment"><a href="#">'.$translation['deleteEquipment'].'</a></li>
									        </ul>
									    </div>
									</li>';
						}
					?>
					<li><div class="list-group-item list-group-item-action divAddEquipment text-center"><i class="fa fa-plus-circle addEquipment" aria-hidden="true"></i></div></li>
				</ul>
			</li>
		</ul>
	</div>
</div>