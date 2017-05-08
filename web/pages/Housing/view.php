<div class="container">
	<div class="row col-md-offset-1 col-md-10 col-md-offset-1"> 
		<div class="col-sm-12 text-center">
			<div class="col-md-4 col-sm-4 hidden-xs">
				<div class="row frameSearch" >
					<div class="col-sm-12" style="margin-bottom: 15px;">
						<h4 style="margin:0;margin-bottom: 15px;"><span><i class="fa fa-search" aria-hidden="true"></i></span><?php echo 'Référence';?></h4>
			            <div class="input-group mb-2 mr-sm-2 mb-sm-0">
			            	<div class="input-group-addon">LK</div>
			            	<input type="number" class="form-control" id="area" name="area">
			            </div>
					</div>
					<div class="col-sm-12">
						<h4 style="margin:0;margin-bottom: 15px;"><span><i class="fa fa-home" aria-hidden="true"></i></span><?php echo 'Type';?></h4>
			            <div class="form-group">
	                		<select class="form-control" id="housingType" name="housingType">
	                		<option value=""></option>
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
					<div class="col-sm-12" style="margin-bottom: 15px;">
						<h4 style="margin:0;margin-bottom: 15px;"><span><i class="fa fa-euro" aria-hidden="true"></i></span><?php echo 'Loyer';?></h4>
			            <input type="text" id="amountRent" class="amount" readonly>
			            <div id="slider-range"></div>
					</div>
					<div class="col-sm-12" style="margin-bottom: 15px;">
						<h4 style="margin:0;margin-bottom: 15px;"><span><i class="fa fa-bed" aria-hidden="true"></i></span><?php echo 'Nombre de chambre minimum';?></h4>
			            <input type="text" id="amountBedroom" class="amount" readonly>
			            <div id="slider-range-min"></div>
					</div>
				</div>
			</div>
			<?php
				if (empty($housings))
				{
			?>
					<div class="col-sm-8 col-xs-12">
						<h5>No Result...</h5>
					</div>
			<?php
				}
				else
				{
			?>
					
			<?php
					foreach ($housings as $housing) {
						$reference = $housing->getReference();
		                for ($i = 0; $i < 5-floor(log10($reference) + 1); $i++)
		                {
		                    $reference = '0'.$reference;
		                }
		                $reference = "LK ".$reference;
		                $now = new DateTime('now');
						$result = $now->diff($housing->getAvailability());
						$availability = ($result->invert == 0) ? $translation['availableOn'].$housing->getAvailability()->format('d-m-Y') : $translation['availableNow'];
			?>
						<div class=" col-md-4 col-sm-4 col-xs-12">
							<div class="row frameUser" style="padding:5px;margin:0px;cursor:pointer;">
								<div class="col-sm-12">
									<h4 style="margin:0;"><span><i class="fa fa-tags" aria-hidden="true"></i></span><?php echo $reference;?></h4>
								</div>
		    					<div class="col-sm-12 text-center" style="margin-bottom: 15px;">
						    	    <img class="img-responsive pictureUserList" src="<?php echo $pictures[$housing->getId()]; ?>"/>
						    	</div>
						    	<div class="col-sm-12">
									<h4 style="margin:0;color:#55ab26;"><?php echo $translation[$housing->getIdType()->getIdLabel()->getLabel()];?></h4>
								</div>
								<?php
								if($housing->getCapacity() > 1)
								{
									echo '<div class="col-sm-12">';
									echo '<h5 style="margin:0;">';
									for ($i = 1; $i <= $housing->getCapacity(); $i++)
									{
										echo '<span><i class="fa fa-child" aria-hidden="true"></i></span>';
									}
									echo '</h5>';
									echo '</div>';
								}
							?>
								<div class="col-sm-12">
									<h5 style="margin:0;"><span><i class="fa fa-map-marker" aria-hidden="true"></i></span><?php echo $housing->getIdProperty()->getCity().'  -  '.($housing->getRent()+$housing->getCharge());?><span><i class="fa fa-eur" aria-hidden="true"></i></span></h5>
								</div>
								<div class="col-sm-12">
									<h5 style="margin:0;font-size:12px"><span><i class="fa fa-calendar" aria-hidden="true"></i></span><?php echo $availability;?></h5>
								</div>
							</div>
						</div>
			<?php
					}
			?>

			<?php
				}
			?>
			</div>
		</div>
	</div>
</div>