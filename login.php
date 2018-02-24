<?php
include 'functions.php';

if ($tuffy_user->is_loggedin()){
  header("Location: http://" .$_SERVER['SERVER_NAME']);
  /* Make sure that code below does not get executed when we redirect. */
  exit;
}

$msg = "";
if ( isset($_POST['login_user']) )
{
  $tuffy_user->login_user($_POST['login-username'], $_POST['login-password']);
  if (!$tuffy_user->login_usernameFound){$msg="username does not exist";}
  else if (!$tuffy_user->login_correctPassword){$msg="wrong password";}
}

$title = 'Tuffy Bay';
$css_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<div class="login_sec">
  <div class="container">
    <ol class="breadcrumb">
      <li><a href="index.php">Home</a></li>
      <li class="active">Login</li>
    </ol>
    <h2>Login</h2>
    <div class="col-md-6 log">
      <p>Welcome, please enter the following to continue.</p>
      <p style = "color:red"><?php echo $msg; ?></p>
        <form method = "post">
          <h5>Username:</h5>
          <input type="text" name = "login-username" required>
          <h5>Password:</h5>
          <input type="password" name = "login-password" required>
          <input type="submit" value="Login" name = "login_user">
          <a class="acount-btn" href="account.php">Create an Account</a>
      </form>
    </div>
    <div class="clearfix"></div>
  </div>
</div>

<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>