<?php
include 'functions.php';
$index = 0;

//if not admin or higher kick them out
if ($_SESSION['user']['type'] < 1 && !$tuffy_user->is_loggedin())
{
	header("Location: http://" .$_SERVER['SERVER_NAME']);
	/* Make sure that code below does not get executed when we redirect. */
	exit;
}



if (isset($_POST['return_items']))
{
	if ($_POST['selected_orders'] != null)
	{
		foreach($_POST['selected_orders'] as $order)
		{
			if (isset($order['checked']) && $order['checked'] == "true")
			{
				$tuffy_inventory->approve_return_request($order);
			}
		}
	}
}

$return_list = $tuffy_inventory->display_return_requests();
$title = 'Tuffy Bay';
$css_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<a href="/admin_page.php" class = "btn btn-info" style = "min-width: 50px; margin-left: 10px">Admin Page</a>
<!--item display/editing/deleting-->
<div style="border: 1px solid #eee;padding: 10px 20px;margin:10px;">
	<h4 style = "padding: 0px 5px 10px 0px"><strong>Return requests:</strong></h4>

	<form method = "post">
		<table class = "table">
			<tr>
				<th></th>
				<th>User</th>
				<th>Name</th>
				<th>Price</th>
				<th>Amount</th>
				<th>Description</th>
				<th>payment method</th>
				<th>reason</th>
				<th>Date ordered</th>
			</tr>
			<?php foreach($return_list as $item): ?>
			<tr>
				<td><input class="form-check-input" type="checkbox" value="true" name = "<?php echo 'selected_orders['.$index.'][checked]'; ?>"></td>
				<td><?php echo $tuffy_user->get_username($item['user_id']); ?></td>
				<td><?php echo $item['name']?></td>
				<td>$<?php echo $item['price']?></td>
				<td><?php echo $item['amount']?></td>
				<td><?php echo $item['description']?></td>
				<td><?php echo $item['payment_used']?></td>
				<td><?php echo $item['return_reason']?></td>
				<td><?php echo get_time_ago(strtotime($item['date_ordered'])); ?></td>


				<!--info-->
				<input type="text" name="selected_orders[<?php echo $index; ?>][id]" value = "<?php echo $item['id']; ?>" hidden>
				<input type="text" name="selected_orders[<?php echo $index; ?>][user_id]" value = "<?php echo $item['user_id']; ?>" hidden>
				<input type="text" name="selected_orders[<?php echo $index; ?>][inventory_id]" value = "<?php echo $item['inventory_id']; ?>" hidden>
				<input type="text" name="selected_orders[<?php echo $index; ?>][name]" value = "<?php echo $item['name']; ?>" hidden>
				<input type="text" name="selected_orders[<?php echo $index; ?>][price]" value = "<?php echo $item['price']; ?>" hidden>
				<input type="text" name="selected_orders[<?php echo $index; ?>][amount]" value = "<?php echo $item['amount']; ?>" hidden>
				<input type="text" name="selected_orders[<?php echo $index; ?>][description]" value = "<?php echo $item['description']; ?>" hidden>
				<input type="text" name="selected_orders[<?php echo $index; ?>][payment_used]" value = "<?php echo $item['payment_used']; ?>" hidden>

				<?php $index++; ?>
			</tr>
		<?php endforeach; ?>
		</table>

		<button type = "submit" name = "return_items">Return items</button>
	</form>
</div>

<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>