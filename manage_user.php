<?php
include 'functions.php';
	
//if user is not logged in, kick them out
if(!$tuffy_user->is_loggedin())
{
	header("Location: http://" .$_SERVER['SERVER_NAME']);
	exit;
}

$user_id = $_SESSION['user']['id'];

if (isset($_POST['money_to_add']))
{
	$money_updated = $tuffy_user->add_money($user_id, $_POST['money_to_add']);
	if ($money_updated){ $msg = "$".$_POST['money_to_add']." has been added to balance"; }
	else { $msg = "SQL error"; }
}
else if (isset($_POST['add_credit_card']))
{
	$security_code = $_POST['security_code'];
    $card_num = $_POST['creditCard1'] . $_POST['creditCard2'] . $_POST['creditCard3'] . $_POST['creditCard4'];
	$tuffy_user->insert_card_info($_SESSION['user']['id'], $card_num, $security_code);
}

$title = 'Tuffy Bay';
$css_files = array('bootstrap.css');
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<div class = "container">
	<div class = "row">
		<div class = "col-xs-3"></div>
		<div class = "col-xs-6" style = "background: #fff;padding: 10px 0px 0px 0px;border-radius: 3px;border: 1px solid #ccc">
			<div style = "border-bottom: 1px solid #eee; padding: 20px 40px">
				<strong>Email:</strong><br> <?php echo $_SESSION['user']['email']; ?>
				<a style = "color: #1AA1D9;" href="/change_email">change email</a><br>
			</div>
			<div style = "border-bottom: 1px solid #eee; padding: 20px 40px">
				<strong>Username:</strong><br> <?php echo $_SESSION['user']['username'] ?>
				<a style = "color: #1AA1D9;" href="/change_password">change password</a><br>
			</div>
			<div style = "border-bottom: 1px solid #eee; padding: 20px 40px">
				<strong>Tuffy Money:</strong><br> $<?php echo $_SESSION['user']['money'] ?>
				<br>
			</div>
			<div style = "border-bottom: 1px solid #eee; padding: 20px 40px">
			<?php if ($_SESSION['user']['credit_card_num'] !== null): ?>
				<strong>Credit Card:</strong><br> <?php echo "**** **** **** ".substr($_SESSION['user']['credit_card_num'], -4); ?><br>
			
			<?php else: ?>
			<strong>Add a Credit Card</strong>
			<form method="post">
			  Credit Card Number:
			      <input type="number" min="1000" max="9999" name="creditCard1" required/>
			      -
			      <input type="number" min="1000" max="9999" name="creditCard2" required/>
			      -
			      <input type="number" min="1000" max="9999" name="creditCard3" required/>
			      -
			      <input type="number" min="1000" max="9999"  name="creditCard4" required/>
			      <br />

			      Security Code: <input type="number" name="security_code" required><br>
			      Card Expiry: <input class="inputCard" name="expiry" id="expiry" type="month" required/><br>
			   
			  <button type="submit" name="add_credit_card">Add card</button>
			</form>
			<?php endif; ?>
			</div>
		</div>
		<div class = "col-xs-3"></div>
	</div>
	<h3>Add money to account (for testing)</h3>
	<form method = "post">
		<label>amount to add to balance: </label>
		<input type="number" name="money_to_add">

		<button type="submit" name = "add_money">add money</button>
	</form>

	<h2 style="color:red"><?php echo $msg; ?></h2>
</div>


<?php
  $js_files = array();
  include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>