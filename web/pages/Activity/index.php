<div class="container">
	<div class="row text-center">			
		<img src="web/pictures/search.png" class="searchPicture img-responsive"/>

		<div class="form-group col-xs-offset-3 col-xs-6 col-xs-offset-3">
            <select class="searchBoxActivity form-control">
                <option value=""><?php echo $translation['selectionActivity']; ?></option>
            	<?php
					foreach($activities as $activity) {
						echo '<option value="'.ucfirst($activity["name"]).'">'.$translation[$activity["label"]].'</option>';
					} 
				?>
            </select>
        </div>
    </div>
	<div class="row">
		<?php
			$langue = '<input class="language" type="hidden" value="';
			foreach($langs as $lang) {
				$langue = $langue.$lang->getId().'+';
			}
			$langue = $langue . '"/>';
			echo $langue;
		?>
		<div class="tabResult">
			<table id="tableLabel" class="table table-hover">
				<thead>
				    <tr>
				      	<th>#</th>
				      	<th><?php echo $translation['label']; ?></th>
				      	<?php
							foreach($langs as $lang) {
								echo '<th>'.utf8_encode($lang->getName()).'</th>';
							}
						?>
				    </tr>
				</thead>
				<tbody class="dataLabel">
				</tbody>
			</table>
		</div>
	</div>
	<div id="dialog-confirm">
</div>
<?php
