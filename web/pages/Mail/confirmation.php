<?php
$title = "";
$message = "";
if (!$finalization)
{
	switch($page)
	{
		case 'register' :
		{
			$title = $translation['confirmationRegister'];
			$message = '<p>'.$translation['textRegisterFirst'].'</p><p>'.$translation['textRegisterSecond'].'</p><p>'.$translation['textRegisterThird'].'</p>';
			break;
		}
		case 'changepassword' :
		{
			$title = $translation['confirmationCP'];
			$message = '<p>'.$translation['textRegisterFirst'].'</p><p>'.$translation['textRegisterSecond'].'</p><p>'.$translation['textRegisterThird'].'</p>';
			break;
		}
	};
}
else
{
	switch($page)
	{
		case 'register' :
		{
			$title = $translation['finalizationRegister'];
			$message = '<p>'.$translation['textFinalizationRegisterFirst'].'</p><p>'.$translation['textFinalizationRegisterSecond'].'</p>';
			break;
		}
		case 'changepassword' :
		{
			$title = $translation['finalizationCP'];
			$message = '<p>'.$translation['textFinalizationCP'].'</p>';
			break;
		}
	};
}

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
			'.$message.'
		</div>
	</div>
</div>';
			      