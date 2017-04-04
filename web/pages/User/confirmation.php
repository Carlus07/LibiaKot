<?php

$title = (!$finalization) ? $translation['confirmationRegister'] : $translation['finalizationRegister'];
$message = (!$finalization) ? $translation['textRegister'] : $translation['textFinalization'];

echo '
<div class="container">
	<div class="contentError">
		<div class="row">
			<div class="error col-xs-offset-1 col-xs-10 col-xs-offset-1 text-center">
				<div class="col-xs-4">
					<img src="web/pictures/confirmationRegister.png" class="errorPicture img-responsive"/>
				</div>
				<div class="col-xs-8 messagePicture">
					<h3>'.$title.'</h3>
				</div>
			</div>
		</div>
		<div class="row text-center messageError">
			<p>'.$message.'</p>
		</div>
	</div>
</div>';
			      