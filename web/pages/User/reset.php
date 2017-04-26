<div class="container">
  <div class="card">
    <p id="profile-name" class="profile-name-card"><?php echo $translation['resetPassword']; ?></p>
    <p id="profile-name" class="profile-name-card"></p>
    <p class="inputNeeded">* <?php echo $translation['requiredField']; ?></p>
    <input type="hidden" id="errorPassword" value="<?php echo (!empty($errors)) ? $errors : ""; ?>"/>
    <form class="form-signin" action="?w=user.reset" method="POST">
        <input type="email" name="mail" id="mail" class="form-control" placeholder="*<?php echo $translation['mail']; ?>" value="<?php echo (!empty($user)) ? $user->getMail() : ""; ?>" required autofocus>
        <button class="btn btn-lg btn-success btn-signin buttonRegister" type="submit"><?php echo $translation["sendMail"]; ?></button>
    </form>
  </div>
</div>