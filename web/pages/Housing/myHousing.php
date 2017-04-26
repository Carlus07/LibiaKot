<div class="container">
	<div class="row col-md-offset-1 col-md-10 col-md-offset-1"> 
	<?php
		foreach ($accomodations as $accomodation) {
	?>	
		<fieldset>
        	<legend><span><i class="fa fa-<?php echo $accomodation['icon']; ?>" aria-hidden="true"></i></span><?php echo $translation[$accomodation['title']]; ?></legend>
        	<?php
        			foreach ($accomodation as $property) {
        				if (!empty($property['housing']))
        				{
        		?>	
        		        	<div class="frameHousingView" style="width:80%">
		        				<div class='row'>
						    		<div class="col-sm-5">
						    			<input type="hidden" value="<?php echo $property['property']->getGPSPosition(); ?>">
						    			<div id="map-canvas" class="mapMyHousing"></div>
						    		</div>
						    		<div class="col-sm-7">
						    			<h4 class="text-center titleHousing"><?php echo $property['property']->getCity();?></h4>
						    			<table class="table tableProperty">
									      	<tr>
									      		<th><i class="fa fa-map-signs" aria-hidden="true"></i></i></th>
										        <th><?php echo $translation['address']; ?></th>
										        <td value="text" data-column="street"><?php echo $property['property']->getStreet();?></td>
									      	</tr>
									      	<tr>
									      		<th><i class="fa fa-home" aria-hidden="true"></i></i></th>
										        <th><?php echo $translation['number']; ?></th>
										        <td value="number" data-column="number"><?php echo $property['property']->getNumber();?></td>
									      	</tr>
									      	<tr>
									      		<th><i class="fa fa-address-card" aria-hidden="true"></i></th>
									         	<th><?php echo $translation['zipCode']; ?></th>
									         	<td value="text" data-column="zipCode"><?php echo $property['property']->getZipCode();?></td>
									      	</tr>
									      	<tr>
									      		<th><i class="fa fa-map-marker" aria-hidden="true"></i></th>
									         	<th><?php echo $translation['GPSPosition']; ?></th>
									         	<td value="text" data-column="GPSPosition"><?php echo $property['property']->getGPSPosition();?></td>
									      	</tr>
								   		</table>
						    		</div>
						    	</div>
						    	<div class="row text-center">
										<div class="col-xs-4">
							    			<a style="color:black" href="?p=housing.addHousing&m=updateProperty&id=<?php echo $property['property']->getId();?>"><i class="fa fa-pencil-square-o pencil" aria-hidden="true"></i></a>
							    		</div>
							    		<div class="col-xs-4">
							    			<a class="viewProperty" style="color:black" href="#" value="<?php echo $property['property']->getId();?>"><i class="fa fa-eye eye" aria-hidden="true"></i></a>
							    		</div>
							    		<div class="col-xs-4">
							    			<a class="removeProperty" style="color:black" href="#" value="<?php echo $property['property']->getId();?>"><i class="fa fa-times remove" aria-hidden="true"></i></a>
							    		</div>
							    	</div>
						    </div>
						<?php
        					foreach ($property['housing'] as $housing) {
        						$reference = $housing->getReference();
								for ($i = 0; $i < 5-floor(log10($reference) + 1); $i++)
								{
									$reference = '0'.$reference;
								}
								$reference = "LK ".$reference;
								//echo mktime($housing->getAvailability());
								$now = new DateTime('now');
								$test = $now->diff($housing->getAvailability());
								$availability = ($test->invert == 0) ? $housing->getAvailability()->format('d-m-Y') : "Maintenant";
        				?>	
        						<div class="frameHousingView" style="width:65%">
								    <div class="row">
								    	<div class="col-sm-5">
								    	    <img class="img-responsive" src="<?php echo $property['picture']; ?>"/>
								    	</div>
								    	<div class="col-sm-7">
							    			<h4 class="text-center titleHousing"><?php echo $reference; ?></h4>
							    			<table class="table tableProperty">
										      	<tr>
										      		<th><i class="fa fa-home" aria-hidden="true"></i></i></th>
											        <th><?php echo $translation['housingType']; ?></th>
											        <td value="text" data-column="housingType"><?php echo $translation[$housing->getIdType()->getIdLabel()->getLabel()];?></td>
										      	</tr>
										      	<tr>
										      		<th><i class="fa fa-calendar" aria-hidden="true"></i></i></th>
											        <th><?php echo $translation['availabilityHousing']; ?></th>
											        <td value="text" data-column="availability"><?php echo $availability;?></td>
										      	</tr>
										      	<tr>
										      		<th><i class="fa fa-arrows-alt" aria-hidden="true"></i></th>
										         	<th><?php echo $translation['area']; ?></th>
										         	<td value="text" data-column="area"><?php echo $housing->getArea().'m²';?></td>
										      	</tr>
										      	<tr>
										      		<th><i class="fa fa-eur" aria-hidden="true"></i></th>
										         	<th><?php echo $translation['rent']; ?></th>
										         	<td value="text" data-column="rent"><?php echo $housing->getRent().'€';?></td>
										      	</tr>
									   		</table>
						    			</div>
						    		</div>
									<div class="row text-center">
										<div class="col-xs-4">
							    			<a style="color:black" href="?p=housing.addHousing&m=updateHousing&id=<?php echo $housing->getId();?>"><i class="fa fa-pencil-square-o pencil" aria-hidden="true"></i></a>
							    		</div>
							    		<div class="col-xs-4">
							    			<a class="viewProperty" style="color:black" href="#" value="<?php echo $property['property']->getId();?>"><i class="fa fa-eye eye" aria-hidden="true"></i></a>
							    		</div>
							    		<div class="col-xs-4">
							    			<a class="removeHousing" style="color:black" href="#" value="<?php echo $housing->getId();?>"><i class="fa fa-times remove" aria-hidden="true"></i></a>
							    		</div>
							    	</div>
							   	</div>
							   	<div class="frameHousingView text-center" style="width:65%">
							   		<a href="?p=housing.addHousing&m=addHousing&id=<?php echo $property['property']->getId();?>"><div class="btn btn-lg btn-success btn-signin buttonAddPicture"><span c><i class="fa fa-plus-circle" aria-hidden="true"></i><?php echo '  '.$translation['addHousing']; ?></span></div></a>
							   	</div>
				<?php
							}
						}
        			}
        		?>
        </fieldset>
<?php
    	}
    if (!empty($properties))
    {
?>
    <fieldset>
    	<legend><span><i class="fa fa-home" aria-hidden="true"></i></span><?php echo $translation['withoutHousing']; ?></legend>
    	<?php
			foreach ($properties as $property) {
		?>	
	        	<div class="frameHousingView" style="width:80%">
    				<div class='row'>
			    		<div class="col-sm-5">
			    			<input type="hidden" value="<?php echo $property->getGPSPosition(); ?>">
			    			<div id="map-canvas" class="mapMyHousing"></div>
			    		</div>
			    		<div class="col-sm-7">
			    			<h4 class="text-center titleHousing"><?php echo $property->getCity();?></h4>
			    			<table class="table tableProperty">
						      	<tr>
						      		<th><i class="fa fa-map-signs" aria-hidden="true"></i></i></th>
							        <th><?php echo $translation['address']; ?></th>
							        <td value="text" data-column="street"><?php echo $property->getStreet();?></td>
						      	</tr>
						      	<tr>
						      		<th><i class="fa fa-home" aria-hidden="true"></i></i></th>
							        <th><?php echo $translation['number']; ?></th>
							        <td value="number" data-column="number"><?php echo $property->getNumber();?></td>
						      	</tr>
						      	<tr>
						      		<th><i class="fa fa-address-card" aria-hidden="true"></i></th>
						         	<th><?php echo $translation['zipCode']; ?></th>
						         	<td value="text" data-column="zipCode"><?php echo $property->getZipCode();?></td>
						      	</tr>
						      	<tr>
						      		<th><i class="fa fa-map-marker" aria-hidden="true"></i></th>
						         	<th><?php echo $translation['GPSPosition']; ?></th>
						         	<td value="text" data-column="GPSPosition"><?php echo $property->getGPSPosition();?></td>
						      	</tr>
					   		</table>
			    		</div>
			    	</div>
			    	<div class="row text-center">
						<div class="col-xs-4">
			    			<a style="color:black" href="?p=housing.addHousing&m=updateProperty&id=<?php echo $property->getId();?>"><i class="fa fa-pencil-square-o pencil" aria-hidden="true"></i></a>
			    		</div>
			    		<div class="col-xs-4">
			    			<a class="viewProperty" style="color:black" href="#" value="<?php echo $property['property']->getId();?>"><i class="fa fa-eye eye" aria-hidden="true"></i></a>
			    		</div>
			    		<div class="col-xs-4">
			    			<a class="removeProperty" style="color:black" href="#" value="<?php echo $property->getId();?>"><i class="fa fa-times remove" aria-hidden="true"></i></a>
			    		</div>
			    	</div>
		    	</div>
		    	<div class="frameHousingView text-center" style="width:65%">
			   		<a href="?p=housing.addHousing&m=addHousing&id=<?php echo $property->getId();?>"><div class="btn btn-lg btn-success btn-signin buttonAddPicture"><span c><i class="fa fa-plus-circle" aria-hidden="true"></i><?php echo '  '.$translation['addHousing']; ?></span></div></a>
			   	</div>
			<?php
			}
			?>
    </fieldset>
<?php
	}
?>
    <div id="dialog-confirm"></div>
	</div>
</div>