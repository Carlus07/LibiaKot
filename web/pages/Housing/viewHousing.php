<div class="container text-center">
	<div class="row col-md-offset-1 col-md-10 col-md-offset-1"> 
<?php 
    $reference = $housing->getReference();
    for ($i = 0; $i < 5-floor(log10($reference) + 1); $i++)
    {
        $reference = '0'.$reference;
    }
    $now = new DateTime('now');
	$test = $now->diff($housing->getAvailability());
	$availability = ($test->invert == 0) ? 'Disponible le : '.$housing->getAvailability()->format('d-m-Y') : "Disponible maintenant";
	$capacity = ($housing->getCapacity() > 1) ? $housing->getCapacity().' places' : $housing->getCapacity().' place';
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
			<div class="col-sm-4 text-center">
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
				?>
				<div class="row">
					<h4 style="margin:0;"><span><i class="fa fa-calendar" aria-hidden="true"></i></span><?php echo $availability;?></h4>
				</div>
			</div>	
	    </fieldset>
	    <fieldset>
	        <legend><span><i class="fa fa-map-marker" aria-hidden="true"></i></span>Localisation</legend>
	    </fieldset>
    </div>
</div>