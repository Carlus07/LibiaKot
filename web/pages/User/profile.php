<div class="container">
	<div class="row col-md-offset-1 col-md-10 col-md-offset-1"> 
		<input type="file" name="fileUploadAvatar" id="fileUploadAvatar" style="display:none;">
		<?php $idUser = (isset($_GET['id'])) ? $_GET['id'] : $_SESSION['idUser']; ?>
		<input type="hidden" name="idUser" id="idUser" value="<?php echo $idUser; ?>"/>
		<div class="col-sm-4 text-center" style="margin-top:90px;">
			<div class="row text-center">
				<img class="avatar" src="<?php echo ($user->getPicture() != null) ? $user->getPicture() : "web/pictures/avatar.png"; ?>" />
			</div>
			<div class="row">
				<i class="fa fa-refresh updateAvatar" aria-hidden="true"></i>
			</div>
		</div>
		<div class="col-sm-8">	
			<div class="row" style="margin-top:40px;">
				<h3 class="text-center"><?php echo $user->getFirstName()." ".$user->getName(); ?></h3>
			   	<div class="panel panel-default">
				   	<div class="panel-heading">
				      	<h6 class="panel-title text-center"><?php echo $translation['coordinated']; ?></h6>
				   	</div>
				   	<table class="table tableProfile">
				      	<tr>
				      		<th><i class="fa fa-map-signs" aria-hidden="true"></i></i></th>
					        <th><?php echo $translation['street']; ?></th>
					        <td value="text" data-column="street"><?php echo $user->getStreet();?></td>
				      	</tr>
				      	<tr>
				      		<th><i class="fa fa-home" aria-hidden="true"></i></i></th>
					        <th><?php echo $translation['number']; ?></th>
					        <td value="number" data-column="number"><?php echo $user->getNumber();?></td>
				      	</tr>
				      	<tr>
				      		<th><i class="fa fa-address-card" aria-hidden="true"></i></th>
				         	<th><?php echo $translation['city']; ?></th>
				         	<td value="text" data-column="city"><?php echo $user->getCity();?></td>
				      	</tr>
				      	<tr>
				      		<th><i class="fa fa-map-marker" aria-hidden="true"></i></th>
				         	<th><?php echo $translation['zipCode']; ?></th>
				         	<td value="number" data-column="zipCode"><?php echo $user->getZipCode();?></td>
				      	</tr>
				      	<tr>
				      		<th><i class="fa fa-phone" aria-hidden="true"></i></th>
				         	<th><?php echo $translation['phone']; ?></th<?php echo $translation['street']; ?>></th>
				         	<td value="phone" data-column="phone"><?php echo $user->getPhone();?></td>
				      	</tr>
				      	<tr>
				      		<th><i class="fa fa-envelope" aria-hidden="true"></i></th>
				         	<th><?php echo $translation['mail']; ?></th>
				         	<th value="mail"><?php echo $user->getMail();?></th>
				      	</tr>
				   	</table>
				</div>
			      
			   	</div>
			</div>
		</div>
		<div class="col-sm-offset-4 col-sm-8 text-center">
			<?php if (!isset($_GET['id']))
			{
				echo '<a href="?p=user.changepassword"><button class="btn btn-lg btn-success btn-signin buttonUpdatePassword" type="submit">'.$translation["editPassword"].'</button></a>';
			}
			?>
			<button class="btn btn-lg btn-success btn-signin buttonEdition" data-option="edit" type="submit"><?php echo $translation['editAccount']; ?></button>
		</div>
	</div>
</div>