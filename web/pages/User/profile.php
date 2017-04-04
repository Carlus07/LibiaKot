<div class="container">
	<div class="row col-md-offset-1 col-md-10 col-md-offset-1"> 
		<input type="file" name="fileUploadAvatar" id="fileUploadAvatar" style="display:none;">
		<div class="col-sm-4 text-center" style="margin-top:40px;">
			<div class="row text-center">
				<img class="avatar" src="<?php echo ($user->getPicture() != null) ? $user->getPicture() : "web/pictures/avatar.png"; ?>" />
			</div>
			<div class="row">
				<i class="fa fa-refresh updateAvatar" aria-hidden="true"></i>
			</div>
		</div>
		<div class="col-sm-8">	
			<div class="row text-center" style="margin-top:40px;">
				<h3><?php echo $user->getFirstName()." ".$user->getName(); ?></h3>
			   	<div class="panel panel-default">
				   	<div class="panel-heading">
				      	<h6 class="panel-title">
				         Coordonnée
				      	</h6>
				   	</div>
				   	<table class="table">
				      	<tr>
				      		<th><i class="fa fa-address-card" aria-hidden="true"></i></th>
					        <th>Adresse</th>
					        <th><?php echo $user->getNumber().', '.$user->getStreet();?></th>
				      	</tr>
				      	<tr>
				      		<th><i class="fa fa-map-marker" aria-hidden="true"></i></th>
				         	<th>Localité</th>
				         	<th><?php echo $user->getZipCode().' '.$user->getCity();?></th>
				      	</tr>
				      	<tr>
				      		<th><i class="fa fa-phone" aria-hidden="true"></i></th>
				         	<th>Téléphone</th>
				         	<th><?php echo $user->getPhone();?></th>
				      	</tr>
				      	<tr>
				      		<th><i class="fa fa-envelope" aria-hidden="true"></i></th>
				         	<th>Adresse Mail</th>
				         	<th><?php echo $user->getMail();?></th>
				      	</tr>
				   	</table>
				</div>
			      
			   	</div>
			</div>
		</div>
		<div class="col-sm-offset-4 col-sm-8 text-center">
			<button class="btn btn-lg btn-success btn-signin buttonUpdatePassword" type="submit">Modifier votre mot de passe</button>
			<button class="btn btn-lg btn-success btn-signin buttonEdition" type="submit">Editer votre compte</button>
		</div>
	</div>
</div>
<?php
?>