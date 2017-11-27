<?php
include 'functions.php';

//if user is not logged in, kick them out
if(!$tuffy_user->is_loggedin())
{
	header("Location: http://" .$_SERVER['SERVER_NAME']);
	exit;
}

$finished_orders = array();
$orders = $tuffy_inventory->display_orders($_SESSION['user']['id']);
foreach ($orders as $item_order)
{
	//completed orders that aren't being returned
	if ($item_order['has_arrived'] == 1 && $item_order['return_request'] == 0)
	{
		array_push($finished_orders, $item_order);
	}
}


$title = 'Tuffy Bay';
$css_files = array('bootstrap.css');
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<div class = "container-fluid">
	<div>
		<ul class = "nav nav-tabs">
			<li><a href="/orders.php">Open Orders (in delivery)</a></li>
			<li class="active"><a href="#">Completed Orders</a></li>
			<li><a href="/orders_return_requests.php">Return Requests</a></li>
			<li><a href="/orders_completed_returns.php">Completed Returns</a></li>
		</ul>
	</div>



	<h2>Completed Orders: </h2>

	<table class="table">
		<tr>
			<th>Name</th>
			<th>amount</th>
			<th>Price</th>
			<th>Description</th>
			<th>payment method</th>
			<th>Date ordered</th>
			<th>Date Arrived</th>
			<th>actions</th>
		</tr>
	<?php foreach($finished_orders as $item): ?>
	<div class="row">
		<div class = "col-xs-12">
		<tr>
			<td><a href="/item_page.php?itemid=<?php echo $item['inventory_id'];?>"><?php echo $item['name']?></a></td>
			<td><?php echo $item['amount']?></td>
			<td>$<?php echo $item['price']?></td>
			<td><?php echo $item['description']?></td>
			<td><?php echo $item['payment_used']?></td>
			<td><?php echo get_time_ago(strtotime($item['date_ordered'])); ?></td>
			<td><?php echo get_time_ago(strtotime($item['date_arrived'])); ?></td>
			<td>
			<form method="post" action="return_request.php">
				<input hidden name="order_id" = value = "<?php echo $item['id']; ?>">
				<button type = "submit" name = "asked_return" class = "btn btn-info">return</button>
			</form>
			</td>
		</tr>
		</div>
	</div>
	<?php endforeach;?>
	</table>
</div>


<!--
  Page Contents go in here
-->

<!--FOOTER-->
<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>