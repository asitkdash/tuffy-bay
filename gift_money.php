<?php
include 'functions.php';

//if user is not logged in, kick them out
if(!$tuffy_user->is_loggedin())
{
  header("Location: http://" .$_SERVER['SERVER_NAME']);
  exit;
}

if (isset($_POST['user_search']))
{
	echo $_POST['user_to_gift'];
	$found_user = $tuffy_user->check_user_existance($_POST['user_to_gift']);
}
else if (isset($_POST['gift_to_user']))
{
	$sender_id = $_SESSION['user']['id'];
	$amount = $_POST['money_to_give'];
	$send_to = $_POST['user_to_gift_2'];

	$gifted = $tuffy_user->gift_money($amount, $sender_id, $send_to);
	if ($gifted)
	{
		$msg = "Successfully gifted money";
	}
	else
	{
		$msg = "Error, money was not gifted";
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

		<?php if (!isset($found_user) || !$found_user): ?>
			<?php if (isset($found_user) && !$found_user): ?>
			user not found, try again.
			<?php endif; ?>
		<form method = "post">
			<div align = "center">
			<div class="form-group">
				<label for="InputUsername">Gift to</label>
				<input type="text" name="user_to_gift" id="InputUsername" placeholder="Enter username" required>
			</div>
			
			
			<button type = "submit" class = "btn btn-primary" name = "user_search" style = "min-width: 55%">Search user</button>
			</div>
		</form>
		<?php endif; ?>
		

		<?php if ($found_user): ?>
		<h4>Found user: <?php echo $_POST['user_to_gift']; ?></h4>

		You have $<?php echo $_SESSION['user']['money'] ?>

		<?php 
		$user = $_POST['user_to_gift'];
		?>

		<form method = "post">
			<input type="text" name="user_to_gift_2" value="<?php echo $user; ?>" hidden>
			<div class="form-group">
			    <label for="InputMoney">Enter amount to gift in $</label>
			    <input type="number" class="form-control" id="InputMoney" placeholder="Enter amount" name = "money_to_give" required max="<?php echo $_SESSION['user']['money']; ?>" min = "1" step="0.01">
		  	</div>
		  	<br>
		  	<div align = "center">
		  		<button type="submit" class="btn btn-primary" name = "gift_to_user" style = "min-width: 55%">Gift money to "<?php echo $user; ?>"</button>
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