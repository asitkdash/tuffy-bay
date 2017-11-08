<?php
include 'functions.php';
$msg = "Changing Email Address";

if (isset($_POST['change_email']))
{
	$email_updated = $tuffy_user->update_email($_SESSION['user']['id'], $_POST['new_email']);
	if (!$email_updated)
	{
		$msg = "email already used";
	}
	else
	{
		$msg = "successfully updated email";
		header("Location: http://" .$_SERVER['SERVER_NAME']."/manage_user.php");
		exit;
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
		<?php if ($email_updated): ?>

		<?php else: ?>
		<form method = "post">
			<div class="form-group">
			    <label for="InputEmail">Enter new email address</label>
			    <input type="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" placeholder="Enter email" name = "new_email" required>
			    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
		  	</div>
		  	<br>
		  	<div align = "center">
		  		<button type="submit" class="btn btn-primary" name = "change_email" style = "width: 55%">change email</button>
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