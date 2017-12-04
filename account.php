<?php
include 'functions.php';

if ($tuffy_user->is_loggedin()){
    header("Location: http://" .$_SERVER['SERVER_NAME'].'/manage_user.php');
    /* Make sure that code below does not get executed when we redirect. */
    exit;
}

$msg = "";
$msg2 = "";
$msg3 = "";
if(isset($_POST['register_user']))
{
	if($_POST['register-password'] != $_POST['password_confirm'])
	{
		$msg = "passwords do not match";
	}
	else
	{
		$tuffy_user->register_user($_POST['register-username'], $_POST['register-password'], $_POST['register-email'], $_POST['sec_question'], $_POST['sec_answer']);
	    if ($tuffy_user->register_usernameTaken)
	    {
	    	$msg = "username already taken. ";
		}
		if ($tuffy_user->register_emailTaken)
		{
			$msg2 = "email already taken.";
		}
	}
}
else if (isset($_POST['login_user']))
{
  $tuffy_user->login_user($_POST['login-username'], $_POST['login-password']);
  if (!$tuffy_user->login_usernameFound){$msg3="username does not exist";}
  else if (!$tuffy_user->login_correctPassword){$msg3="wrong password";}
}

$title = 'Tuffy Bay';
$css_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<div class="container">
	  <ol class="breadcrumb">
		  <li><a href="index.php">Home</a></li>
		  <li class="active">Account</li>
		 </ol>
	 <div class="registration">
		 <div class="registration_left">
			 <h2>new user? <span> create an account </span></h2>
			 <!-- [if IE]
				< link rel='stylesheet' type='text/css' href='ie.css'/>
			 [endif] -->

			 <!-- [if lt IE 7]>
				< link rel='stylesheet' type='text/css' href='ie6.css'/>
			 <! [endif] -->
			 <script>
				(function() {

				// Create input element for testing
				var inputs = document.createElement('input');

				// Create the supports object
				var supports = {};

				supports.autofocus   = 'autofocus' in inputs;
				supports.required    = 'required' in inputs;
				supports.placeholder = 'placeholder' in inputs;

				// Fallback for autofocus attribute
				if(!supports.autofocus) {

				}

				// Fallback for required attribute
				if(!supports.required) {

				}

				// Fallback for placeholder attribute
				if(!supports.placeholder) {

				}

				// Change text inside send button on submit
				var send = document.getElementById('register-submit');
				if(send) {
					send.onclick = function () {
						this.innerHTML = '...Sending';
					}
				}

			 })();
			 </script>
			 <div style = "color:red">
			 	<?php echo $msg.$msg2; ?>
			 </div>
			 <div class="registration_form">
			 <!-- Form -->
				<form id="registration_form" method="post">
					<div>
						<label>
							<input placeholder="username:" type="text" tabindex="1" name = "register-username" required autofocus>
						</label>
					</div>
					<div>
						<label>
							<input placeholder="email address:" type="email" tabindex="2" name = "register-email" required>
						</label>
					</div>
					<div>
						<label>
							<input placeholder="password" type="password" tabindex="3" name = "register-password" required>
						</label>
					</div>
					<div>
						<label>
							<input placeholder="retype password" type="password" tabindex="4" name ="password_confirm" required>
						</label>
					</div>
					<div class="form-group">
					  	<label for="sel1"><strong>Security Question:</strong></label>
					  	<select class="form-control" id="sel1" name = "sec_question" required tabindex="5">
					    	<option>What school did you attend for sixth grade?</option>
					    	<option>What was your favorite sport in high school?</option>
					    	<option>What is your favorite movie?</option>
					    	<option>What is the name of your favorite childhood friend?</option>
					    	<option>What was your childhood nickname?</option>
					    	<option>In what city or town did your mother and father meet?</option>
					    	<option>What is your favorite team?</option>
					  	</select>
					  	<label>
							<input placeholder="answer" type="text" tabindex="6" name ="sec_answer" required>
						</label>
					</div>
					
					<div>
						<input type="submit" value="create an account" id="register-submit" name = "register_user">
					</div>
				</form>
				<!-- /Form -->
			 </div>
		 </div>
		 <div class="registration_left">
			 <h2>existing user</h2>
			 <div style = "color:red">
			 	<?php echo $msg3; ?>
			 </div>
			 <div class="registration_form">
			 <!-- Form -->
				<form id="registration_form" method="post">
					<div>
						<label>
							<input placeholder="username:" type="text" tabindex="6" name = "login-username" required>
						</label>
					</div>
					<div>
						<label>
							<input placeholder="password" type="password" tabindex="7" name = "login-password" required>
						</label>
					</div>
					<div>
						<input type="submit" value="sign in" id="register-submit" name = "login_user">
					</div>
				</form>
			 <!-- /Form -->
			 </div>
		 </div>
		 <div class="clearfix"></div>
	 </div>
</div>

<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>