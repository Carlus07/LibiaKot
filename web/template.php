<?php
namespace web;
use Models\Session;
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="icon" type="image/x-icon" href="web/pictures/icon.ico" />
		<title><?php echo $title ?></title>
		<link rel="stylesheet" href="web/css/font-awesome.min.css" />
		<link rel="stylesheet" href="web/css/bootstrap.min.css" />
		<link rel="stylesheet" href="web/css/bootstrap-theme.min.css" />
		<link rel="stylesheet" href="web/css/owl.carousel.css">
		<link rel="stylesheet" href="web/css/jquery-ui.css">
		<link rel="stylesheet" href="web/css/owl.theme.css">
		<link rel="stylesheet" href="web/css/bootstrap-datetimepicker.min.css" />
		<link rel="stylesheet" href="web/css/main.css" />
		<link rel="stylesheet" href="web/css/user.css" />
	</head>
  	<body>
  		<div class="main">
  			<div class="header">
  				<div class="topBar">
  					<div class="container">
  						<div class="row">
  							<div class="top-left col-xs-3">
  								<ul class="list-inline">
  									<?php
  										foreach($languages as $language)
  										{
  											echo '<li><a href="?o=lang.change&l='.$language->getId().'"><img class="flag" src="'.$language->getFlag().'"/></a></li>';
  										}
	  								?>
				                </ul>
  							</div>
			                <div class="top-right col-xs-9">
			                	<ul class="">
			                  		<li class="account-login">
			                  			<?php
			                  				echo $topBar;
			                  			?>
			                  		</li>
			                	</ul>
			              	</div>
  						</div>
  					</div>
  				</div>
  				<div class="navTop">
  					<div class="container">
  						<div class="row navTopResult">
	  						<?php
                  				echo $navTop;
                  			?>
	          		 	</div>
  					</div>
  				</div>
  			</div>
  			<div class="navBar">
	  			<div class="container">
					<nav class="navbar navbar-custom col-md-offset-1 col-md-10 col-md-offset-1">
					  	<?php
              				echo $navBar;
              			?>
					</nav>
				</div>
			</div>
	  		<div class="content">
	  			<?php
      				echo $content;
      			?>
	      	</div>
	    	<div class="footer clearfix">
	        	<div class="container">
		          	<div class="footerResult row text-center">
		            	<?php
		            		echo $footer;
		            	?>
		          	</div>
	        	</div>
        	</div>
        	<div class="copyRight clearfix">
	        	<div class="container">
	          		<div class="row">
	            		<div class="col-xs-12">
	              			<p>©Projet Logement Namur by Carlus</p>
	            		</div>
	          		</div> 
	          	</div>    
	       	</div>
      	</div>
      	<script type="text/javascript" src="web/js/libs/jquery-3.1.1.min.js"></script>
      	<script type="text/javascript" src="web/js/libs/bootstrap.min.js"></script>
      	<script type="text/javascript" src="web/js/libs/owl.carousel.min.js"></script>
      	<script type="text/javascript" src="web/js/libs/bootstrap-notify.min.js"></script>
      	<script type="text/javascript" src="web/js/libs/mindmup-editabletable.js"></script>
      	<script type="text/javascript" src="web/js/libs/jquery-ui.min.js"></script>
      	<script type="text/javascript" src="web/js/libs/moment.min.js"></script>
      	<script type="text/javascript" src="web/js/libs/bootstrap-datetimepicker.min.js"></script>
      	<script type="text/javascript" src="web/js/Main.js"></script>
		<?php
			echo $moduleJs;
		?>
	</body>
</html>