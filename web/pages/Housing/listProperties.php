<div class="container">
	<div class="row col-md-offset-1 col-md-10 col-md-offset-1"> 
		<div class="row col-sm-12 text-center">
<?php
	foreach ($properties as $property) {
		?>
			<div class="frameUser col-sm-4">
				<div class="row">
			    	<div class="col-sm-12 text-center">
			    	    <img class="img-responsive pictureUserList" src="<?php echo $property['picture'] ?>"/>
			    	</div>
			    </div>
			    <div class="row">
			    	<div class="col-sm-12">
						<h4 class="text-center titleHousing"><?php echo $property['zipCode'].' - '.$property['city']; ?></h4>
					</div>
					<div class="col-sm-12">
						<h5 class="text-center titleHousing"><?php echo $property['number'].', '.$property['street']; ?></h5>
					</div>
				</div>
				<div class="row text-center">
					<div class="col-xs-4">
						<a style="color:black" href="?p=housing.addHousing&m=updateProperty&id=<?php echo $property['idProperty']; ?>"><i class="fa fa-pencil-square-o pencil" aria-hidden="true"></i></a>
					</div>
					<div class="col-xs-4">
						<a style="color:black" href="?p=housing.addHousing&m=addHousing&id=<?php echo $property['idProperty']; ?>"><i class="fa fa-plus updateProperty" aria-hidden="true"></i></a>
					</div>
					<div class="col-xs-4 text-center">
						<a class="deleteProperty" style="color:black" href="#" value="<?php echo $property['idProperty']; ?>"><i class="fa fa-times remove" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		<?php
	}
		$pagination = ceil($size / 12);
		$active = (isset($_GET['r'])) ? $_GET['r'] / 12 : 1;
		if ($pagination > 1)
		{
?>
			</div>
			<div class="col-sm-12 text-center">
				<nav aria-label="Page navigation example">
				  <ul class="pagination">
				    <li class="page-item">
				      <a class="page-link" href="?p=housing.listProperties&r=<?php echo ($active == 1) ? 12 : (($active-1)*12); ?>" aria-label="Previous">
				        <span aria-hidden="true">&laquo;</span>
				        <span class="sr-only">Previous</span>
				      </a>
				    </li>
				    <?php 
				    	for($i = 1; $i <= $pagination; $i++)
				    	{
				    		$class = ($active != $i) ? ' ' : ' active';
				    		$redirection = $i*12;
				    		echo '<li class="page-item '.$class.'"><a class="page-link" href="?p=housing.listProperties&r='.$redirection.'">'.$i.'</a></li>';
				    	}
				 	?>
				    <li class="page-item">
				      <a class="page-link" href="?p=housing.listProperties&r=<?php echo ($active == $pagination) ? ($active*12) : (($active+1)*12); ?>" aria-label="Next">
				        <span aria-hidden="true">&raquo;</span>
				        <span class="sr-only">Next</span>
				      </a>
				    </li>
				  </ul>
				</nav>
			</div>
	<?php
		}
		else echo '</div>';
	?>
	</div>
	<div id="dialog-confirm">
	</div>
</div>
