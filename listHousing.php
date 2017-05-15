<?php
require_once "src/Models/Entities/Property.php";
require_once "src/Models/Entities/Housing.php";
require_once "src/Models/Entities/User.php";

session_start();
$housings = $_SESSION['housings'];
$translation = $_SESSION['translation'];
$pictures = $_SESSION['pictures'];
$type = $_SESSION['type'];
$equipments = $_SESSION['equipments'];

unset($_SESSION['housings']);
unset($_SESSION['translation']);
unset($_SESSION['pictures']);
unset($_SESSION['type']);
unset($_SESSION['equipments']);

?>
<style>
.picture{
	width:100px;
	border-radius: 50%;
}
legend {
	color: #55ab26;
	font-weight: bold;
	padding-top:10px;
}
legend > span{
	background: #55ab26;
	color:white;
	margin:10px;
    width:30px;
    height:30px;
    text-align:center;
    border-radius:50%;
    display: inline-block;
	position: relative;
	vertical-align: middle;
	padding:5px;
	font-size: 18px;
}
ul {
	margin:0;
	padding:0;
}
@media print {
    /*Styles Here*/
}
ul li {
	list-style-type:none;
}
.labelEquipment{
	margin-right: 20px;
	font-size:15px;
	font-weight: bold;
	vertical-align: middle;
	float: left;
}
.equipmentLogo{
	width:30px;
}
.pictureHousing{
	border-radius: 15px;
}
</style>
<!DOCTYPE HTML>
<html>
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="icon" type="image/x-icon" href="web/pictures/icon.ico" />
		<title>Liste des Logements</title>
		<link rel="stylesheet" href="web/css/font-awesome.min.css" />
		<link rel="stylesheet" href="web/css/bootstrap.min.css" />
		<link rel="stylesheet" href="web/css/bootstrap-theme.min.css" />
		<link rel="stylesheet" href="web/css/jquery-ui.css">
	</head>
  	<body>
  		<div class="main">
  			<div class="container">
  				<div class="title text-center">
  					<p style="font-weight: bold; font-size: 22px;">Liste des logements disponibles dans l'entité de Namur</p>
  				</div>
	  		<?php 
	  			$idUser = 0;
	  			$idProperty = 0;
	  			foreach ($housings as $key => $housing)
	  			{
	  				if ($housing->getIdProperty()->getIdUser()->getId() != $idUser)
	  				{
	  					$idUser = $housing->getIdProperty()->getIdUser()->getId();
	  		?>
	  				<div class="owner">
	  					<div class="row">
	  						<div class="pictureOwner col-xs-4 text-center">
							<?php 
								$avatar = (empty($housing->getIdProperty()->getIdUser()->getPicture())) ? "web/pictures/avatar.png" : $housing->getIdProperty()->getIdUser()->getPicture();
								echo '<img class="picture" src="'.$avatar.'">'
							?>
	  						</div>
	  						<div class='infoOwner col-xs-8' style="margin-top:15px;">
	  							<div class="row">
	  								<div class="col-xs-2 text-center">
	  									<p style="font-size:20px"><i class="fa fa-user-circle" aria-hidden="true"></i></p>
	  									<p style="font-size:20px;"><i class="fa fa-map-marker" aria-hidden="true"></i></p>
	  									<p style="font-size:20px;"><i class="fa fa-envelope" aria-hidden="true"></i></p>
	  								</div>
	  								<div class="col-xs-10">
	  								<?php
	  									$gender = ($housing->getIdProperty()->getIdUser()->getGender() == 1) ? "Monsieur" : "Madame";
	  									$mail = (empty($housing->getIdProperty()->getIdUser()->getMail())) ? "/" : $housing->getIdProperty()->getIdUser()->getMail();
	  									echo '<p>'.$gender.' '.$housing->getIdProperty()->getIdUser()->getFirstName().' '.$housing->getIdProperty()->getIdUser()->getName().'</p>';
	  									echo '<p>'.$housing->getIdProperty()->getIdUser()->getNumber().', '.$housing->getIdProperty()->getIdUser()->getStreet().' - '.$housing->getIdProperty()->getIdUser()->getZipCode().' '.$housing->getIdProperty()->getIdUser()->getCity().'</p>';
	  									echo '<p>'.$housing->getIdProperty()->getIdUser()->getPhone().' - '.$mail.'</p>';	
	  								?>
	  								</div>
	  							</div>
	  						</div>
	  					</div>
		  			</div>
	  				<?php
	  				}
	  				if ($housing->getIdProperty()->getId() != $idProperty)
	  				{
	  					$idProperty = $housing->getIdProperty()->getId();
	  					$easeNearby = (empty($housing->getIdProperty()->getEaseNearby())) ? '/' : utf8_encode($housing->getIdProperty()->getEaseNearby());
	  					$rentComment = (empty($housing->getRentComment())) ? '/' : utf8_encode($housing->getRentComment());
	  		?>
  					<div class="property">
	  					<div class="row">
			  				<fieldset>
								<legend style="color:#55ab26;text-align:center;"><span><i class="fa fa-home" aria-hidden="true"></i></span>Propriété</legend>
								<div class="row col-xs-12">
									<div class="col-xs-4">
										<ul>
											<li>
												<div class="row">
													<div class="col-xs-2">
														<img src="web\pictures\Equipment\jardin.png" class="equipmentLogo">
													</div>
													<div class="col-xs-7" style="margin-top:5px;">
														<span class="labelEquipment"><?php echo $translation['garden']; ?></span>
													</div>
													<div class="col-xs-3">
														<h4 style="margin:0;">
															<?php echo ($housing->getIdProperty()->getGarden()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
														</h4>
													</div>
												</div>
											</li>
											<li> 
												<div class="row">
													<div class="col-xs-2">
														<img src="web\pictures\Equipment\15b332b6927.png" class="equipmentLogo">
													</div>
													<div class="col-xs-7" style="margin-top:5px;">
														<span class="labelEquipment"><?php echo $translation['terrace']; ?></span>
													</div>
													<div class="col-xs-3">
														<h4 style="margin:0;">
															<?php echo ($housing->getIdProperty()->getTerrace()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
														</h4>
													</div>
												</div>
											</li>
											<li>
												<div class="row">
													<div class="col-xs-2">
														<img src="web\pictures\Equipment\15b33312f3e.png" class="equipmentLogo">
													</div>
													<div class="col-xs-7" style="margin-top:5px;">
														<span class="labelEquipment"><?php echo $translation['bicycleParking']; ?></span>
													</div>
													<div class="col-xs-3">
														<h4 style="margin:0;">
															<?php echo ($housing->getIdProperty()->getBicycleParking()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
														</h4>
													</div>
												</div>
											</li>
										</ul>
									</div>
									<div class="col-xs-4">
										<ul>
											<li>
												<div class="row">
													<div class="col-xs-2">
														<img src="web\pictures\Equipment\pmr.png" class="equipmentLogo">
													</div>
													<div class="col-xs-7" style="margin-top:5px;">
														<span class="labelEquipment"><?php echo $translation['disabledAccess']; ?></span>
													</div>
													<div class="col-xs-3">
														<h4 style="margin:0;">
															<?php echo ($housing->getIdProperty()->getDisabledAccess()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
														</h4>
													</div>
												</div>
											</li>
											<li>
												<div class="row">
													<div class="col-xs-2">
														<img src="web\pictures\Equipment\smoke.png" class="equipmentLogo">
													</div>
													<div class="col-xs-7" style="margin-top:5px;">
														<span class="labelEquipment"><?php echo $translation['smoker']; ?></span>
													</div>
													<div class="col-xs-3">
														<h4 style="margin:0;">
															<?php echo ($housing->getIdProperty()->getSmoker()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
														</h4>
													</div>
												</div>
											</li>
											<li>
												<div class="row">
													<div class="col-xs-2">
														<img src="web\pictures\Equipment\peb.png" class="equipmentLogo">
													</div>
													<div class="col-xs-7" style="margin-top:5px;">
														<span class="labelEquipment"><?php echo $translation['realizedPEB']; ?></span>
													</div>
													<div class="col-xs-3">
														<h4 style="margin:0;">
															<?php echo ($housing->getIdProperty()->getPEB()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
														</h4>
													</div>
												</div>
											</li>
										</ul>
									</div>
									<div class="col-xs-4">
										<ul>
											<li>
												<div class='row'>
													<div class="col-xs-2">
														<img src="web\pictures\Equipment\pet.png" class="equipmentLogo">
													</div>
													<div class="col-xs-7" style="margin-top:5px;">
														<span class="labelEquipment"><?php echo $translation['animalAllowded']; ?></span>
													</div>
													<div class="col-xs-3">
														<h4 style="margin:0;">
															<?php echo ($housing->getIdProperty()->getAnimal()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
														</h4>
													</div>
												</div>
											</li>
											<li>
												<div class="row">
													<div class="col-xs-2">
														<img src="web\pictures\Equipment\15b3332ba1c.png" class="equipmentLogo">
													</div>
													<div class="col-xs-7" style="margin-top:5px;">
														<span class="labelEquipment"><?php echo $translation['carParking']; ?></span>
													</div>
													<div class="col-xs-3">
														<h4 style="margin:0;">
															<?php echo ($housing->getIdProperty()->getCarParking()) ? '<span><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span><i class="fa fa-remove" aria-hidden="true"></i></span>'; ?>
														</h4>
													</div>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<div class="row text-center">
									<p style="font-size:18px;font-weight: bold;"><?php echo $translation['fullAddress'].$housing->getIdProperty()->getNumber().', '.$housing->getIdProperty()->getStreet().' - '.$housing->getIdProperty()->getZipCode().' '.$housing->getIdProperty()->getCity();?></p>
									<div class="col-xs-4">
										<p><?php echo $translation['easeNearby'].' :';?></p>
									</div>
									<div class="col-xs-8">
										<p><?php echo $easeNearby;?></p>
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend style="color:#55ab26;text-align:center;"><span><i class="fa fa-home" aria-hidden="true"></i></span>Logement(s)</legend>
								<div class="row col-xs-12">
								</div>
						</div>
					</div>
	  		<?php
	  				}
	  		?>		
	  			<div class="housing">
	  				<div class="row text-center">
	  					<?php 
	  						$capacity = ($housing->getCapacity() > 1) ? " - ".$housing->getCapacity()." places" : "";
	  						echo '<p style="font-size: 18px; font-weight: bold;">'. $type[$housing->getId()].$capacity.'</p>';
	  					?>
	  				</div>
  					<div class="row">
  						<div class="summary col-xs-2">
  							<img src="<?php echo $pictures[$housing->getId()]; ?>" class="pictureHousing img-responsive"/>
  						</div>
  						<div class="information col-xs-10">
  							<div class="infoHousing col-xs-12">
  								<div class="row">
									<div class="col-xs-3">
										<p><?php echo $translation['domiciliation'].' :';?></p>
									</div>
									<div class="col-xs-3">
										<p>
											<?php 
											if ($housing->getIdProperty()->getDomiciliation() == 0) echo $translation['no'];
											if ($housing->getIdProperty()->getDomiciliation() == 1) echo $translation['yes'];
											if ($housing->getIdProperty()->getDomiciliation() == 2) echo $translation['onCondition'];
											?>
										</p>
									</div>
									<div class="col-xs-3">
										<p><?php echo $translation['targetAudience'].' :';?></p>
									</div>
									<div class="col-xs-3">
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
									<div class="col-xs-3">
										<p><?php echo $translation['area'].' :';?></p>
									</div>
									<div class="col-xs-3">
										<p><?php echo $housing->getArea().' m²';?></p>
									</div>
									<div class="col-xs-3">
										<p><?php echo $translation['floor'].' :';?></p>
									</div>
									<div class="col-xs-3">
										<p><?php echo $housing->getFloor();?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-3">
										<p><?php echo $translation['bathroom'].' :';?></p>
									</div>
									<div class="col-xs-3">
										<p>
											<?php 
											if ($housing->getBathroom() == 1) echo $translation['common'];
											if ($housing->getBathroom() == 2) echo $translation['privateRoom'];
											if ($housing->getBathroom() == 3) echo $translation['privateSeparate'];
											?>
										</p>
									</div>
									<div class="col-xs-3">
										<p><?php echo $translation['kitchen'].' :';?></p>
									</div>
									<div class="col-xs-3">
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
									<div class="col-xs-3">
										<p><?php echo $translation['heating'].' :';?></p>
									</div>
									<div class="col-xs-3">
										<p>
											<?php 
											if ($housing->getHeating() == 1) echo $translation['central'];
											if ($housing->getHeating() == 2) echo $translation['electric'];
											if ($housing->getHeating() == 3) echo $translation['gaz'];
											?>
										</p>
									</div>
								</div>
  							</div>
  							<legend></legend>
  							<div class="rent col-xs-12">
  								<div class="row">
									<div class="col-xs-3">
										<p><?php echo $translation['rent'].' :';?></p>
									</div>
									<div class="col-xs-3">
										<p><?php echo $housing->getRent().' €';?></p>
									</div>
									<div class="col-xs-3">
										<p><?php echo $translation['charge'].' :';?></p>
									</div>
									<div class="col-xs-3">
										<p><?php echo $housing->getCharge().' €';?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-3">
										<p><?php echo $translation['deposit'].' :';?></p>
									</div>
									<div class="col-xs-3">
										<p><?php echo $housing->getDeposit().' €';?></p>
									</div>
									<div class="col-xs-3">
										<p><?php echo $translation['rentalDuration'].' :';?></p>
									</div>
									<div class="col-xs-3">
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
									<div class="col-xs-6">
										<p><?php echo $translation['rentComment'].' :';?></p>
									</div>
									<div class="col-xs-6">
										<p><?php echo $rentComment;?></p>
									</div>
								</div>
  							</div>
  							<div class="row equipment col-xs-12">
  							</div>
  						</div>
  						<div class="row">
							<?php
							foreach($equipments as $key => $category)
							{
								echo '<div class="col-xs-4">';
								echo '<p style="text-align:center; text-transform: uppercase;color:#55ab26;">'.$translation[$key].'</p>';
								echo '<ul>';

								if (isset($category))
								{
									foreach ($category as $equipment)
									{
										echo '<li class="row">';
										echo '<div class="col-xs-5 text-center">';
										$src = (isset($equipment["picture"])) ? $equipment["picture"] : '';
										echo '<img class="equipmentLogo" src="'.$src.'"/>';
										echo '</div>';
										echo '<div class="col-xs-7">';
										echo '<span class="labelEquipment">'.$translation[$equipment["label"]].'</span>';
										echo '</div>';
										echo '</li>';
									}
								}
								echo '</ul>';
								echo '</div>';
							}
							?>
						</div>
  					</div>
	  			</div>
	  			<legend></legend>
			<?php
		  		}
		  	?>
		  	</div>
  		</div>
  		<script type="text/javascript" src="web/js/libs/jquery-3.1.1.min.js"></script>
      	<script type="text/javascript" src="web/js/libs/bootstrap.min.js"></script>
      	<script>
      		window.print();
      	</script>
  	</body>
</html>