<div class="container">
  <div class="card">
    <p id="profile-name" class="profile-name-card"><?php echo $translation['titleChangePassword']; ?></p>
    <p id="profile-name" class="profile-name-card"></p>
    <p class="inputNeeded">* <?php echo $translation['requiredField']; ?></p>
    <input type="hidden" id="errorPassword" value="<?php echo (!empty($errors)) ? $errors : ""; ?>"/>
    <form class="form-signin" action="?w=user.changepassword" method="POST">
        <input type="password" name="oldPassword" id="oldPassword" class="form-control" placeholder="*<?php echo $translation['oldPassword']; ?>" required>
        <input type="password" name="password" id="password" class="form-control" placeholder="*<?php echo $translation['newPassword']; ?>" required>
        <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="*<?php echo $translation['confirmPassword']; ?>" required>
      <button class="btn btn-lg btn-success btn-signin buttonRegister" type="submit"><?php echo $translation["change"]; ?></button>
    </form>
  </div>
</div>