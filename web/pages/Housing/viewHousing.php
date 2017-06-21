

<div class="container text-center">
	<div class="row col-md-offset-1 col-md-10 col-md-offset-1"> 
		<?php 
		$reference = $housing->getReference();
		for ($i = 0; $i < 5-floor(log10($reference) + 1); $i++)
		{
			$reference = '0'.$reference;
		}
		$now = new DateTime('now');
		$result = $now->diff($housing->getAvailability());
		$availability = ($result->invert == 0) ? $translation['availableOn'].$housing->getAvailability()->format('d-m-Y') : $translation['availableNow'];
		$capacity = ($housing->getCapacity() > 1) ? $housing->getCapacity().' '.$translation['place'].'s' : $housing->getCapacity().' '.$translation['place'];
		$spaceAvailable = ($housing->getSpaceAvailable() > 1) ? $housing->getSpaceAvailable().' '.$translation['remainingPlaces'] : $translation['oneRemainingPlace'];
		$pictureOwner = (empty($housing->getIdProperty()->getIdUser()->getPicture())) ? "web/pictures/avatar.png" : $housing->getIdProperty()->getIdUser()->getPicture();
		$easeNearby = (empty($housing->getIdProperty()->getEaseNearby())) ? '/' : utf8_decode(utf8_decode($housing->getIdProperty()->getEaseNearby()));
		$rentComment = (empty($housing->getRentComment())) ? '/' : utf8_decode(utf8_encode($housing->getRentComment()));
		?>
		<fieldset>
			<legend><span><i class="fa fa-home" aria-hidden="true"></i></span><?php echo 'LK '.$reference; ?></legend>
			<div class="col-sm-8">
				<div class="container col-sm-12 carousel">
					<div id="owl-example" class="owl-carousel">
						<?php
						if (empty($pictures))
						{
							echo '<div class="item"><img src="web/pictures/iconLarge.png"></div>';
						}
						else
						{
							foreach ($pictures as $picture) {
								$namePicture = explode('.',$picture->getName());
								$pictureSrc = "web/pictures/Housing/miniature/".$namePicture[0]."-miniature.".$namePicture[1];
								echo '<div class="item"><img src="'.$pictureSrc.'"></div>';
							}
						}
						?>
					</div>
				</div>
			</div>
			<div class="col-sm-4 text-center divSummaryHousing">
				<div class="summaryHousing">
					<div class="row">
						<h3 class="typeHousing"><?php echo $translation[$housing->getIdType()->getIdLabel()->getLabel()];?></h3>
					</div>
					<div class="row">
						<h4 style="margin:0;"><span><i class="fa fa-map-marker" aria-hidden="true"></i></span><?php echo $housing->getIdProperty()->getCity();?></h4>
					</div>
					<div class="row">
						<h4 style="margin:0;"><?php echo $housing->getRent()+$housing->getCharge();?><span><i class="fa fa-eur" aria-hidden="true"></i></span></h4>
					</div>

					<?php 
					if (($housing->getIdType()->getIdLabel()->getLabel() == "apartment") || ($housing->getIdType()->getIdLabel()->getLabel() == "house"))
					{
						echo '<div class="row">
						<h4 style="margin:0;"><span><i class="fa fa-users" aria-hidden="true"></i></span>'.$capacity.'</h4>
					</div>';
					} 
					if ($housing->getIdType()->getIdLabel()->getLabel() == "flatsharing")
					{
						echo '<div class="row">
						<h4 style="margin:0;"><span><i class="fa fa-users" aria-hidden="true"></i></span>'.$capacity.'</h4>
					</div>';
						echo '<div class="row">
						<h4 style="margin:0;"><span><i class="fa fa-child" aria-hidden="true"></i></span>'.$spaceAvailable.'</h4>
					</div>';
					} 
				?>			
					<div class="row">
						<h4 style="margin:0;"><span><i class="fa fa-calendar" aria-hidden="true"></i></span><?php echo $availability;?></h4>
					</div>
					<div class="row">
						<h3 class="typeHousing" style="margin-top:20px;"><?php echo $translation['owner'];?></h3>
					</div>
					<div class="infoOwner">
						<a href="?p=user.profile&id=<?php echo $housing->getIdProperty()->getIdUser()->getId(); ?>">
							<div class="row">
								<img class="pictureOwner" src="<?php echo $pictureOwner;?>"/>
							</div>
							<div class="row">
								<h4 style="margin:0;"><?php echo utf8_encode($housing->getIdProperty()->getIdUser()->getFirstName()).' '.utf8_decode(utf8_encode($housing->getIdProperty()->getIdUser()->getName()));?></h4>
							</div><br>
							<div class="row">
								<h4 style="margin:0;color:#55ab26;"><i class="fa fa-envelope" aria-hidden="true"></i><?php echo $translation['contact'];?></h4>
							</div>
						</a>
					</div>
				</div>
			</div>	
		</fieldset>
		<fieldset>
			<legend><span><i class="fa fa-map-signs" aria-hidden="true"></i></span><?php echo $translation['location'];?></legend>
			<div id="map-canvas" class="mapViewHousing col-sm-12"></div>
			<input type="hidden" name="GPSPosition" class="GPSPosition" value="<?php echo $housing->getIdProperty()->getGPSPosition(); ?>" />
			<div class="row">
				<h5><?php echo $translation['fullAddress'].$housing->getIdProperty()->getNumber().', '.$housing->getIdProperty()->getStreet().' - '.$housing->getIdProperty()->getZipCode().' '.$housing->getIdProperty()->getCity();?></h5>
				<div class="col-sm-4">
					<h5><?php echo $translation['easeNearby'].' :';?></h5>
				</div>
				<div class="col-sm-8">
					<p><?php echo $easeNearby;?></p>
				</div>
			</div>
		</fieldset>
		<fieldset>
			<legend><span><i class="fa fa-map-marker" aria-hidden="true"></i></span><?php echo $translation['propertyInfo'];?></legend>
			<div class="row">
				<div class="col-sm-6">
					<ul>
						<li>
							<div class="col-xs-2">
								<img src="web\pictures\Equipment\jardin.png" class="equipmentLogo">
							</div>
							<div class="col-xs-7" style="margin-top:5px;">
								<span class="labelEquipment"><?php echo $translation['garden']; ?></span>
							</div>
							<div class="col-xs-3">
								<h4 style="margin:0;">
									<?php echo ($housing->getIdProperty()->getGarden()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span style="background-color:#c9302c"><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
								</h4>
							</div>
						</li>
						<li> 
							<div class="col-xs-2">
								<img src="web\pictures\Equipment\15b332b6927.png" class="equipmentLogo">
							</div>
							<div class="col-xs-7" style="margin-top:5px;">
								<span class="labelEquipment"><?php echo $translation['terrace']; ?></span>
							</div>
							<div class="col-xs-3">
								<h4 style="margin:0;">
									<?php echo ($housing->getIdProperty()->getTerrace()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span style="background-color:#c9302c"><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
								</h4>
							</div>
						</li>
						<li>
							<div class="col-xs-2">
								<img src="web\pictures\Equipment\15b33312f3e.png" class="equipmentLogo">
							</div>
							<div class="col-xs-7" style="margin-top:5px;">
								<span class="labelEquipment"><?php echo $translation['bicycleParking']; ?></span>
							</div>
							<div class="col-xs-3">
								<h4 style="margin:0;">
									<?php echo ($housing->getIdProperty()->getBicycleParking()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span style="background-color:#c9302c"><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
								</h4>
							</div>
						</li>
						<li>
							<div class="col-xs-2">
								<img src="web\pictures\Equipment\15b3332ba1c.png" class="equipmentLogo">
							</div>
							<div class="col-xs-7" style="margin-top:5px;">
								<span class="labelEquipment"><?php echo $translation['carParking']; ?></span>
							</div>
							<div class="col-xs-3">
								<h4 style="margin:0;">
									<?php echo ($housing->getIdProperty()->getCarParking()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span style="background-color:#c9302c"><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
								</h4>
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
							<div class="col-xs-7" style="margin-top:5px;">
								<span class="labelEquipment"><?php echo $translation['disabledAccess']; ?></span>
							</div>
							<div class="col-xs-3">
								<h4 style="margin:0;">
									<?php echo ($housing->getIdProperty()->getDisabledAccess()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span style="background-color:#c9302c"><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
								</h4>
							</div>
						</li>
						<li>
							<div class="col-xs-2">
								<img src="web\pictures\Equipment\smoke.png" class="equipmentLogo">
							</div>
							<div class="col-xs-7" style="margin-top:5px;">
								<span class="labelEquipment"><?php echo $translation['smoker']; ?></span>
							</div>
							<div class="col-xs-3">
								<h4 style="margin:0;">
									<?php echo ($housing->getIdProperty()->getSmoker()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span style="background-color:#c9302c"><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
								</h4>
							</div>
						</li>
						<li>
							<div class="col-xs-2">
								<img src="web\pictures\Equipment\peb.png" class="equipmentLogo">
							</div>
							<div class="col-xs-7" style="margin-top:5px;">
								<span class="labelEquipment"><?php echo $translation['realizedPEB']; ?></span>
							</div>
							<div class="col-xs-3">
								<h4 style="margin:0;">
									<?php echo ($housing->getIdProperty()->getPEB()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span style="background-color:#c9302c"><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
								</h4>
							</div>
						</li>
						<li>
							<div class="col-xs-2">
								<img src="web\pictures\Equipment\pet.png" class="equipmentLogo">
							</div>
							<div class="col-xs-7" style="margin-top:5px;">
								<span class="labelEquipment"><?php echo $translation['animalAllowded']; ?></span>
							</div>
							<div class="col-xs-3">
								<h4 style="margin:0;">
									<?php echo ($housing->getIdProperty()->getAnimal()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span style="background-color:#c9302c"><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
								</h4>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</fieldset>
		<fieldset>
			<legend><span><i class="fa fa-bed" aria-hidden="true"></i></span><?php echo $translation['housingInfo'];?></legend>
			<div class="row">
				<div class="col-sm-4">
					<h5><?php echo $translation['domiciliation'].' :';?></h5>
				</div>
				<div class="col-sm-8">
					<p>
						<?php 
						if ($housing->getIdProperty()->getDomiciliation() == 0) echo $translation['no'];
						if ($housing->getIdProperty()->getDomiciliation() == 1) echo $translation['yes'];
						if ($housing->getIdProperty()->getDomiciliation() == 2) echo $translation['onCondition'];
						?>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<h5><?php echo $translation['targetAudience'].' :';?></h5>
				</div>
				<div class="col-sm-8">
					<p>
						<?php 
						if ($housing->getIdProperty()->getTargetAudience() == 0) echo $translation['everybody'];
						if ($housing->getIdProperty()->getTargetAudience() == 1) echo $translation['onlyGirls'];
						if ($housing->getIdProperty()->getTargetAudience() == 2) echo $translation['onlyBoys'];
						if ($housing->getIdProperty()->getTargetAudience() == 3) echo $translation['onlyHighSchool'];
						if ($housing->getIdProperty()->getTargetAudience() == 4) echo $translation['onlyUniversity'];
						?>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<h5><?php echo $translation['area'].' :';?></h5>
				</div>
				<div class="col-sm-8">
					<p><?php echo $housing->getArea().' m²';?></p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<h5><?php echo $translation['floor'].' :';?></h5>
				</div>
				<div class="col-sm-8">
					<p><?php echo $housing->getFloor();?></p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<h5><?php echo $translation['bathroom'].' :';?></h5>
				</div>
				<div class="col-sm-8">
					<p>
						<?php 
						if ($housing->getBathroom() == 1) echo $translation['common'];
						if ($housing->getBathroom() == 2) echo $translation['privateRoom'];
						if ($housing->getBathroom() == 3) echo $translation['privateSeparate'];
						?>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<h5><?php echo $translation['kitchen'].' :';?></h5>
				</div>
				<div class="col-sm-8">
					<p>
						<?php 
						if ($housing->getKitchen() == 1) echo $translation['common'];
						if ($housing->getKitchen() == 2) echo $translation['privateRoom'];
						if ($housing->getKitchen() == 3) echo $translation['privateSeparate'];
						?>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<h5><?php echo $translation['heating'].' :';?></h5>
				</div>
				<div class="col-sm-8">
					<p>
						<?php 
						if ($housing->getHeating() == 1) echo $translation['central'];
						if ($housing->getHeating() == 2) echo $translation['electric'];
						if ($housing->getHeating() == 3) echo $translation['gaz'];
						?>
					</p>
				</div>
			</div>
		</fieldset>
		<fieldset>
			<legend><span><i class="fa fa-cogs" aria-hidden="true"></i></span><?php echo $translation['equipmentHousing'];?></legend>
			<div class="row">
				<?php
				foreach($equipments as $key => $category)
				{
					echo '<div class="col-sm-6">';
					echo '<h4 style="color:#55ab26;">'.$translation[$key].'</h4>';
					echo '<ul>';

					if (isset($category))
					{
						foreach ($category as $equipment)
						{
							echo '<li>';
							echo '<div class="col-xs-2">';
							$src = (isset($equipment["picture"])) ? $equipment["picture"] : '';
							echo '<img class="equipmentLogo" src="'.$src.'"/>';
							echo '</div>';
							echo '<div class="col-xs-7">';
							echo '<span class="labelEquipment">'.$translation[$equipment["label"]].'</span>';
							echo '</div>';
							echo '<div class="col-xs-3" style="z-index:2;">';
							echo '<h4 style="margin:0;"><span><i class="fa fa-check" aria-hidden="true"></i></span></h4>';
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
			<legend><span><i class="fa fa-eur" aria-hidden="true"></i></span><?php echo $translation['rent']; ?></legend>
			<div class="row">
				<div class="col-sm-4">
					<h5><?php echo $translation['rent'].' :';?></h5>
				</div>
				<div class="col-sm-8">
					<p><?php echo $housing->getRent().' €';?></p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<h5><?php echo $translation['charge'].' :';?></h5>
				</div>
				<div class="col-sm-8">
					<p><?php echo $housing->getCharge().' €';?></p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<h5><?php echo $translation['deposit'].' :';?></h5>
				</div>
				<div class="col-sm-8">
					<p><?php echo $housing->getDeposit().' €';?></p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<h5><?php echo $translation['rentalDuration'].' :';?></h5>
				</div>
				<div class="col-sm-8">
					<p>
						<?php 
						if ($housing->getRentalDuration() == 1) echo '12 '.$translation['month'];
						if ($housing->getRentalDuration() == 2) echo '10 '.$translation['month'];
						if ($housing->getRentalDuration() == 3) echo $translation['firstSemester'];
						if ($housing->getRentalDuration() == 4) echo $translation['secondSemester'];
						if ($housing->getRentalDuration() == 5) echo $translation['summerHoliday'];
						if ($housing->getRentalDuration() == 6) echo $translation['other'];
						?>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<h5><?php echo $translation['rentComment'].' :';?></h5>
				</div>
				<div class="col-sm-8">
					<p><?php echo $rentComment;?></p>
				</div>
			</div>
		</fieldset>
	<?php
		if ((isset($_GET['request'])) && ($_SESSION['Role'] == 3))
		{
	?>
		<fieldset>
			<legend><span><i class="fa fa-check" aria-hidden="true"></i></span>Validation</legend>
			<div class="row selection">
				<div class="col-sm-4">
					<a href="#" class="confirmHousing" value="<?php echo $housing->getId(); ?>"><button onclick="return false;" class="btn btn-lg btn-success btn-signin buttonUpdatePassword" type=""><i class="fa fa-check" aria-hidden="true"></i><?php echo '  '.$translation["confirmation"]; ?></button></a>
				</div>
				<div class="col-sm-4">
					<a href="#" class="mail" value="<?php echo $housing->getIdProperty()->getIdUser()->getId(); ?>"><button onclick="return false;" class="btn btn-lg btn-success btn-signin buttonUpdatePassword" type="text"><i class="fa fa-envelope" aria-hidden="true"></i><?php echo '  '.$translation["contactOwner"]; ?></button></a>
				</div>
				<div class="col-sm-4">
					<a href="#" class="deleteHousing" value="<?php echo $housing->getId(); ?>"><button onclick="return false;" style="background-color:#c9302c;" class="btn btn-lg btn-success btn-signin buttonUpdatePassword" type=""><i class="fa fa-remove" aria-hidden="true"></i><?php echo '  '.$translation["removeRequest"]; ?></button></a>
				</div>
			</div>
			<div class="col-sm-12 message" style="display: none">
				<form action="?w=user.sendMessage" method="POST">
					<input type="hidden" value="<?php echo $housing->getIdProperty()->getIdUser()->getId(); ?>" class="idOwner" name="idOwner"/>
					<div class='form-group'>
		                <label for="object"><?php echo $translation['object']; ?></label>
		                <input class="form-control" id="object" name="object" required="true" type="text"/>
		            </div>
              		<div class="form-group">
	                	<label for="message"><?php echo $translation['message']; ?></label>
	                	<textarea class="form-control" name="message" id="message" style="resize:none;" rows="3"></textarea>
              		</div>
              		<div class="col-sm-push-4 col-sm-4">
						<button class="btn btn-lg btn-success btn-signin buttonUpdatePassword" type="submit"><i class="fa fa-send" aria-hidden="true"></i><?php echo '  '.$translation["sendMessage"]; ?></button>
					</div>
              	</form>
			</div>
		</fieldset>
	<?php
		}
	?>
	</div>
	<div id="dialog-confirm">
	</div>
</div>