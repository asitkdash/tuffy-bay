<?php 
	include 'functions.php';

	//PHP LOGIC
	$item = $tuffy_inventory->inventory_get_item($_GET['itemid']);
	$cart_url = "Location: http://" .$_SERVER['SERVER_NAME'] . "/checkout.php";

	if (isset($_POST['add_to_cart']))
	{
		if (!$tuffy_user->is_loggedin())
		{
			$msg = "must log in first";
		}
		else
		{
			$tuffy_inventory->add_to_cart($_GET['itemid'], $_SESSION['user']['id'], $_POST['num_to_buy']);
			header($cart_url);
	      	exit;
		}
	}

	//HEADER
	$title = $item['name'];
	$css_files = array();
	include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>


<img style ="display: block;margin: 80px auto; width: 30%; " src="https://upload.wikimedia.org/wikipedia/commons/6/6a/A_blank_flag.png">

<table class="table">
	<tr>
		<th>Name</th>
		<th># in stock</th>
		<th>Price</th>
		<th>Description</th>
	</tr>
	<tr>
		<td><?php echo $item['name']?></td>
		<td><?php echo $item['count']?></td>
		<td>$<?php echo $item['price']?></td>
		<td><?php echo $item['description']?></td>
	</tr>
</table>

<form method="post">
	<label >number of items: </label>
	<input type="number" name="num_to_buy" value="1">
	<input type="submit" class="button" value="Add to cart" name="add_to_cart">
</form>

<h2 style="color:red"><?php echo $msg; ?></h2>

<!--FOOTER-->
<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>