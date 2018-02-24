<?php
include 'functions.php';
$msg = "Changing Password";

//if user is not logged in, kick them out
if(!$tuffy_user->is_loggedin())
{
  header("Location: http://" .$_SERVER['SERVER_NAME']);
  exit;
}

$question_answered = false;
$security_question = $tuffy_user->get_security_question($_SESSION['user']['id']);

if (isset($_POST['answered_question']))
{
	$question_answered = true;
	$answer_correct = $tuffy_user->authenticate_security_question($_SESSION['user']['id'], $_POST['answer_to_question']);
	if (!$answer_correct)
	{
		$msg = "answer to question incorrect, try again.";
	}
}
if (isset($_POST['change_password']))
{
	//set these to true so user doesn't have to redo security answer
	$question_answered = true;
	$answer_correct = true;
	
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

		<?php elseif (!$question_answered || !$answer_correct): ?>
		<form method = "post">
			<div class="form-group">
			    <label for="InputAnswer"><?php echo $security_question; ?></label>
			    <input type="text" class="form-control" id="InputAnswer" placeholder="answer" name = "answer_to_question" required>
			</div>
			<div align="center">
				<button type = "submit" class = "btn btn-primary" name = "answered_question" style = "min-width: 55%">Submit</button>
			</div>
		</form>
		
		<?php elseif ($question_answered && $answer_correct): ?>
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
		  		<button type="submit" class="btn btn-primary" name = "change_password" style = "min-width: 55%">change password</button>
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