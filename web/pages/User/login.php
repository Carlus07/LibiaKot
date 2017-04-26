<div class="container">
   	<div class="card">
        <input type="hidden" id="errorLogin" value="<?php echo (!empty($error)) ? $error : ""; ?>"/>
        <img class="profile-img-card" src="web/pictures/avatar.png" />
        <p class="profile-name-card"></p>
        <form class="form-signin" action="?r=user.login" method="POST">
            <input type="email" name="mail" id="mail" class="form-control" placeholder="<?php echo $translation['mail']; ?>" value="<?php echo (!empty($mail)) ? $mail : ""; ?>" required autofocus>
            <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo $translation["password"]; ?>" required>
            <button class="btn btn-lg btn-success btn-signin" type="submit"><?php echo $translation["connection"]; ?></button>
            <div class="text-center">
                <?php
                    echo '<a href="?p=user.register" class="createAccount">'.$translation["register"].'</a> '.$translation["or"].' <a href="?p=user.reset" class="resetPassword">'.$translation["resetPassword"].'</a>';
                ?>
            </div>
         </form>
     </div>
</div>