<?php include('view/includes/head.php'); ?>

<main class="login-container">
  <section class="login-container-form">
    <h3>Login to CMS</h3>
    <div class="login-input-container">
      <label>Username</label>
      <div class="login-input"><i class="icon fa fa-user"></i><input type="text" value="luuk12345" form-tag="username" placeholder="Username"></div>
    </div>
    <div class="login-input-container">
      <label>Password</label>
      <div class="login-input"><i class="icon fa fa-key"></i><input type="password" value="wachtwoord" form-tag="password" placeholder="Password"></div>
    </div>
  </section>
  <section class="login-container-button">
    <a id="login-button" class="login-button" href="javascript:;">Sign in</a>
  </section>
  <section class="error">
    <div class="error-message" id="error"></div>
  </section>
</main>

<?php include('view/includes/footer.php'); ?>
