<?php
include 'functions.php';

//if not admin or higher kick them out
if ($_SESSION['user']['type'] < 1 || !$tuffy_user->is_loggedin())
{
	header("Location: http://" .$_SERVER['SERVER_NAME']);
	/* Make sure that code below does not get executed when we redirect. */
	exit;
}

//confirm that the order has been delivered to customer
if (isset($_POST['confirm_delivery']))
{
	$tuffy_inventory->complete_delivery($_POST['order_id']);
}

//display orders that are currently in delivery
$in_delivery_orders = array();
$orders = $tuffy_inventory->display_orders("all");
foreach ($orders as $item_order)
{
	if ($item_order['has_arrived'] == 0 && !$item_order['return_approved'])
	{
		array_push($in_delivery_orders, $item_order);
	}
}

$title = 'Tuffy Bay';
$css_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<div class = "container-fluid">
<a href="/admin_page.php" class = "btn btn-info" style = "min-width: 50px; margin-left: 10px">Admin Page</a>
<div style="border: 1px solid #eee;padding: 10px 20px;margin:10px;">
	<h2>Open orders(in delivery): </h2>
	<br>

	<table class="table">
		<tr>
			<th>Username</th>
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
			<td><?php echo $tuffy_user->get_username($item['user_id']); ?></td>
			<td><a href="/item_page.php?itemid=<?php echo $item['inventory_id'];?>"><?php echo $item['name']?></a></td>
			<td><?php echo $item['amount']?></td>
			<td>$<?php echo $item['price']?></td>
			<td><?php echo $item['description']?></td>
			<td><?php echo $item['payment_used']?></td>
			<td><?php echo get_time_ago(strtotime($item['date_ordered'])); ?></td>
			<td>
			<form method="post">
				<input hidden name="order_id" = value = "<?php echo $item['id']; ?>">
				<button type = "submit" name = "confirm_delivery" class = "btn btn-info">I have delivered this item</button>
			</form>
			</td>
		</tr>
		</div>
	</div>
	<?php endforeach;?>
	</table>
</div>
</div>


<!--
  Page Contents go in here
-->

<!--FOOTER-->
<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>