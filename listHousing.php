<?php
require_once "src/Models/Entities/Property.php";
require_once "src/Models/Entities/Housing.php";
require_once "src/Models/Entities/User.php";

session_start();
$housings = $_SESSION['housings'];
unset($_SESSION['housings']);
?>
<style>
.picture{
	width:100px;
	border-radius: 50%;
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

	  		<?php 
	  			$idUser = [];
	  			$idProperty = [];
	  			foreach ($housings as $key => $housing)
	  			{
	  				if ($housing->getIdProperty()->getIdUser()->getId() == $idUser)
	  				{
	  					$idUser = $housing->getIdProperty()->getIdUser()->getId();
	  				?>
	  				<div class="owner">
	  					<div class="row">
	  						<div class="pictureOwner col-xs-4">
							<?php 
								$avatar = (empty($housing->getIdProperty()->getIdUser()->getPicture())) ? "web/pictures/avatar.png" : $housing->getIdProperty()->getIdUser()->getPicture();
								echo '<img class="picture" src="'.$avatar.'">'
							?>
	  						</div>
	  						<div class='infoOwner col-xs-8'>
	  							<div class="row">
	  								<div class="col-xs-6">
	  								</div>
	  								<div class="col-xs-6">
	  								</div>
	  							</div>
	  						</div>
	  					</div>
		  			</div>
	  				<?php
	  				}
	  				if ($housing->getIdProperty()->getId() == $idProperty)
	  				{
	  					$idProperty = $housing->getIdProperty()->getId();
	  				}
	  		?>
	  			<div class="housing">
  					<div class="row">
  						<div class="summary col-xs-4">
  							<p>Ceci est un test pour voir comment le contenu ce place en fonction de la page d'impression"</p>
  						</div>
  						<div class="information col-xs-8">
  							<div class="row infoProperty col-xs-12">
  								<p>Ceci est un test pour voir comment le contenu ce place en fonction de la page d'impression"</p>
  							</div>
  							<div class="row infoHousing col-xs-12">
  								<p>Ceci est un test pour voir comment le contenu ce place en fonction de la page d'impression"</p>
  							</div>
  							<div class="row rent col-xs-12">
  								<p>Ceci est un test pour voir comment le contenu ce place en fonction de la page d'impression"</p>
  							</div>
  							<div class="row equipment col-xs-12">
  								<p>Ceci est un test pour voir comment le contenu ce place en fonction de la page d'impression"</p>
  							</div>
  						</div>
  					</div>
	  			</div>
			<?php
		  		}
		  	?>
		  	</div>
  		</div>
  		<script type="text/javascript" src="web/js/libs/jquery-3.1.1.min.js"></script>
      	<script type="text/javascript" src="web/js/libs/bootstrap.min.js"></script>
      	<script>
      		//window.print();
      	</script>
  	</body>
</html>