<?php
include 'functions.php';
$msg = "Changing Password";

if (isset($_POST['change_password']))
{
	var_dump ($_POST);
	if ($_POST['new_password'] == $_POST['new_password_confirm'])
	{
		$password_updated = $tuffy_user->update_password($_SESSION['user']['id'], $_POST['curr_password'], $_POST['new_password']);
		if (!$password_updated)
		{
			$msg = "current password doesn't match";
		}
		else
		{
			$msg = "successfully updated password";
		}
	}
	else
	{
		$msg = "new passwords did not match";
	}
}


$title = 'Tuffy Bay';
$css_files = array('bootstrap.css');
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<div class = "container">
	<div>
		<h2 style = "text-align: center"><?php echo $msg; ?></h2>
	</div>

	<div class = "row" style = "min-height: 400px; padding: 50px 100px; border-radius: 6px">
		<div class = "col-xs-4"></div>
		<div class = "col-xs-4">
		<?php if ($password_updated): ?>

		<?php else: ?>
		<form method = "post">
			<div class="form-group">
			    <label for="InputCurrPassword">Current Password</label>
			    <input type="password" class="form-control" id="InputCurrPassword" placeholder="Password" name = "curr_password" required>
			</div>
			<div class="form-group">
			    <label for="InputNewPassword">New Password</label>
			    <input type="password" class="form-control" id="InputNewPassword" placeholder="Password" name = "new_password" required>
			</div>
			<div class="form-group">
			    <label for="InputNewPassword2">Confirm New Password</label>
			    <input type="password" class="form-control" id="InputNewPassword2" placeholder="Password" name = "new_password_confirm" required>
			</div>
			<br>
		  	<div align = "center">
		  		<button type="submit" class="btn btn-primary" name = "change_password" style = "width: 55%">change password</button>
		  	</div>
		</form>
		<?php endif; ?>

		</div>
		<div class = "col-xs-4"></div>
	</div>

</div>

<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>