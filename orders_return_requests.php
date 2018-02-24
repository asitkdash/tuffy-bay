<?php
include 'functions.php';

//if user is not logged in, kick them out
if(!$tuffy_user->is_loggedin())
{
	header("Location: http://" .$_SERVER['SERVER_NAME']);
	exit;
}

$return_requests = array();
$orders = $tuffy_inventory->display_orders($_SESSION['user']['id']);
foreach ($orders as $item_order)
{
	if ($item_order['return_request'] == 1)
	{
		array_push($return_requests, $item_order);
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
			<li><a href="/orders_completed.php">Completed Orders</a></li>
			<li class="active"><a href="#">Return Requests</a></li>
			<li><a href="/orders_completed_returns.php">Completed Returns</a></li>
		</ul>
	</div>



	<h2>Returns requests: </h2>

	<table class="table">
		<tr>
			<th>Name</th>
			<th>amount</th>
			<th>Price</th>
			<th>Description</th>
			<th>payment method</th>
			<th>return reason</th>
			<th>Date ordered</th>
		</tr>
	<?php foreach($return_requests as $item): ?>
	<div class="row">
		<div class = "col-xs-12">
		<tr>
			<td><a href="/item_page.php?itemid=<?php echo $item['inventory_id'];?>"><?php echo $item['name']?></a></td>
			<td><?php echo $item['amount']?></td>
			<td>$<?php echo $item['price']?></td>
			<td><?php echo $item['description']?></td>
			<td><?php echo $item['payment_used']?></td>
			<td><?php echo $item['return_reason'] ?></td>
			<td><?php echo get_time_ago(strtotime($item['date_ordered'])); ?></td>
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