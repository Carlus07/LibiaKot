<div class="container">
	<div class="row col-md-offset-1 col-md-10 col-md-offset-1"> 
		<fieldset>
        	<legend><span><i class="fa fa-check" aria-hidden="true"></i></span>Validée</legend>
        	<div class="frameHousing">
        		<?php
        			foreach ($validatedHousing as $property) {
        				if (!empty($property['housing']))
        				{
        		?>
	        				<div class='row'>
					    		<div class="col-xs-5">
					    			<input type="hidden" value="<?php echo $property['property']->getGPSPosition(); ?>">
					    			<div id="map-canvas" class="mapMyHousing"></div>
					    		</div>
					    		<div class="col-xs-7">
					    			<div>
						    			<i class="fa fa-pencil" aria-hidden="true"></i>
						    			<i class="fa fa-times" aria-hidden="true"></i>
						    		</div>
					    		</div>
					    	</div>
				<?php
						}
        			}
        		?>
		    	
		    </div>
        </fieldset>
        <fieldset>
        	<legend><span><i class="fa fa-refresh" aria-hidden="true"></i></span>En attente de validation</legend>
        		<?php
        			foreach ($pendingHousing as $property) {
        				if (!empty($property['housing']))
        				{
        		?>	
        		        	<div class="frameHousing">
		        				<div class='row'>
						    		<div class="col-xs-5">
						    			<input type="hidden" value="<?php echo $property['property']->getGPSPosition(); ?>">
						    			<div id="map-canvas" class="mapMyHousing"></div>
						    		</div>
						    		<div class="col-xs-6">
						    			<h4 class="text-center">Namur</h4>
						    		</div>
						    		<div class="col-xs-1 divChange">
						    			<div class="divChangeProperty">
							    			<i class="fa fa-pencil" aria-hidden="true"></i>
						    				<i class="fa fa-times" aria-hidden="true"></i>
						    			</div>
						    		</div>
						    	</div>
						    </div>
				<?php
						}
        			}
        		?>
		    	
        </fieldset>
	</div>
</div>