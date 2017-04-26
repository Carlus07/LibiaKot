<div class="container">
  <div class="card">
    <p id="profile-name" class="profile-name-card"><?php echo $translation['titleRegister']; ?></p>
    <p id="profile-name" class="profile-name-card"></p>
    <input type="hidden" class="admin" value="<?php echo (!$admin) ? "false" : "true"; ?>"/>
    <p class="inputNeeded">* <?php echo $translation['requiredField']; ?></p>
    <input type="hidden" id="errorRegister" value="<?php echo (!empty($errors)) ? $errors : ""; ?>"/>
    <form class="form-signin" action="<?php echo (!$admin) ? "?w=user.register" : "?w=user.add"; ?>" method="POST">
      <input type="text" name="lastName" id="lastName" class="form-control" placeholder="*<?php echo $translation['lastName']; ?>" value="<?php echo (!empty($user)) ? $user->getName() : ""; ?>" required autofocus>
      <input type="text" name="firstName" id="firstName" class="form-control" placeholder="*<?php echo $translation['firstName']; ?>" value="<?php echo (!empty($user)) ? $user->getFirstName() : ""; ?>" required autofocus>
      <div class="text-center">
        <label class="radio-inline">
          <input name="gender" checked="<?php echo (!empty($user) && $user->getGender() == true) ? "true" : "false"; ?>" value="1" type="radio"><?php echo $translation['man']; ?>
        </label>
        <label class="radio-inline">
          <input name="gender" <?php echo (!empty($user) && $user->getGender() == false) ? 'checked="true"' : ""; ?>" value="0" type="radio"><?php echo $translation['woman']; ?>
        </label>
      </div><br>
      <input type="email" name="mail" id="mail" class="form-control" placeholder="*<?php echo $translation['mail']; ?>" value="<?php echo (!empty($user)) ? $user->getMail() : ""; ?>"  <?php echo (!$admin) ? "required" : ""; ?> autofocus>
    <?php 
      if(!$admin)
      {
    ?>
      <input type="password" name="password" id="password" class="form-control" placeholder="*<?php echo $translation['password']; ?>" required>
      <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="*<?php echo $translation['confirmPassword']; ?>" required>
    <?php
      }
    ?>
      <input type="text" name="street" id="street" class="form-control" placeholder="*<?php echo $translation['street']; ?>" value="<?php echo (!empty($user)) ? $user->getStreet() : ""; ?>" required>
      <input type="number" name="number" id="number" class="form-control" placeholder="*<?php echo $translation['number']; ?>" value="<?php echo (!empty($user)) ? $user->getNumber() : ""; ?>" required>
      <input type="text" name="city" id="city" class="form-control" placeholder="*<?php echo $translation['city']; ?>" value="<?php echo (!empty($user)) ? $user->getCity() : ""; ?>" required>
      <input type="number" name="zipCode" id="zipCode" class="form-control" placeholder="*<?php echo $translation['zipCode']; ?>" value="<?php echo (!empty($user)) ? $user->getZipCode() : ""; ?>" required>
      <input type="text" name="phone" id="phone" class="form-control" placeholder="*<?php echo $translation['phone']; ?>" value="<?php echo (!empty($user)) ? $user->getPhone() : ""; ?>" required>
      <input type="text" name="secondPhone" id="secondPhone" class="form-control" placeholder="<?php echo $translation['secondPhone']; ?>">
      <button class="btn btn-lg btn-success btn-signin buttonRegister" type="submit"><?php echo $translation["register"]; ?></button>
    </form><!-- /form -->
  </div><!-- /card-container -->
</div>