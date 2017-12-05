<?php 
	include 'functions.php';

	//if user is not logged in, kick them out
	if(!$tuffy_user->is_loggedin())
	{
		header("Location: http://" .$_SERVER['SERVER_NAME']);
		exit;
	}

	if (isset($_POST['cancel_order']))
	{
		$tuffy_inventory->cancel_order($_POST['order']);
	}
	
	$in_delivery_orders = array();
	$orders = $tuffy_inventory->display_orders($_SESSION['user']['id']);
	foreach ($orders as $item_order)
	{
		if ($item_order['has_arrived'] == 0 && !$item_order['return_approved'])
		{
			array_push($in_delivery_orders, $item_order);
		}
	}

	$title = 'Success purchase'; # Enter title of page
	$css_files = array('bootstrap.css');
	include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<div class="container-fluid">
	<div>
		<ul class = "nav nav-tabs">
			<li class="active"><a href="#">Open Orders (in delivery)</a></li>
			<li><a href="/orders_completed.php">Completed Orders</a></li>
			<li><a href="/orders_return_requests.php">Return Requests</a></li>
			<li><a href="/orders_completed_returns.php">Completed Returns</a></li>
		</ul>
	</div>
	<h2>Open orders(in delivery): </h2>

	<table class="table">
		<tr>
			<th>Name</th>
			<th>amount</th>
			<th>Price</th>
			<th>Description</th>
			<th>payment method</th>
			<th>Date ordered</th>
			<th>actions</th>
		</tr>
	<?php foreach($in_delivery_orders as $item): ?>
	<div class="row">
		<div class = "col-xs-12">
		<tr>

			<td><a href="/item_page.php?itemid=<?php echo $item['inventory_id'];?>"><?php echo $item['name']?></a></td>
			<td><?php echo $item['amount']?></td>
			<td>$<?php echo $item['price']?></td>
			<td><?php echo $item['description']?></td>
			<td><?php echo $item['payment_used']?></td>
			<td><?php echo get_time_ago(strtotime($item['date_ordered'])); ?></td>
			<td>
			<form method="post">
				<!--info-->
				<input type="text" name="order[id]" value = "<?php echo $item['id']; ?>" hidden>
				<input type="text" name="order[user_id]" value = "<?php echo $item['user_id']; ?>" hidden>
				<input type="text" name="order[inventory_id]" value = "<?php echo $item['inventory_id']; ?>" hidden>
				<input type="text" name="order[name]" value = "<?php echo $item['name']; ?>" hidden>
				<input type="text" name="order[price]" value = "<?php echo $item['price']; ?>" hidden>
				<input type="text" name="order[amount]" value = "<?php echo $item['amount']; ?>" hidden>
				<input type="text" name="order[description]" value = "<?php echo $item['description']; ?>" hidden>
				<input type="text" name="order[payment_used]" value = "<?php echo $item['payment_used']; ?>" hidden>
				<button type = "submit" name = "cancel_order" class = "btn btn-info">cancel</button>
			</form>
			</td>
		</tr>
		</div>
	</div>
	<?php endforeach;?>
	</table>

</div>


<?php 
	$js_files = array();
	include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>
