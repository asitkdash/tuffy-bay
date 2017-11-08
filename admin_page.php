<?php
include 'functions.php';

//if not admin or higher kick them out
if ($_SESSION['user']['type'] < 1 && !$tuffy_user->is_loggedin())
{
	header("Location: http://" .$_SERVER['SERVER_NAME']);
	/* Make sure that code below does not get executed when we redirect. */
	exit;
}

$inventory = $tuffy_inventory->inventory_display();

$title = 'Tuffy Bay';
$css_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<div class = "container">
	<div class = "row">
		<div class = "col-xs-4">
			<div style = "border-top: 2px solid #eee;border-bottom: 2px solid #eee;padding: 20px 70px 30px 70px;margin:10px;">
				<h3 style = "text-align:center;padding-bottom: 10px"><strong>admin actions</strong></h3>
				<a href="/admin_add_items.php" class = "btn btn-info" style = "min-width: 200px">Add new items</a>
				<br>
				<br>
				<a href="/admin_edit_items.php" class = "btn btn-info" style = "min-width: 200px">Edit items</a>
				<br>
				<br>
				<a href="/admin_return_items.php" class = "btn btn-info" style = "min-width: 200px">Return Requests</a>
			</div>
			
		</div>
		<div class = "col-xs-8">


			<!--item display/editing-->
			<div style="border: 1px solid #eee;padding: 10px 20px;margin:10px;">
				<h4 style = "padding: 0px 5px 10px 0px"><strong>current inventory:</strong></h4>
				<table class = "table">
					<tr>
						<th>Name</th>
						<th>Price</th>
						<th>Stock</th>
						<th>description</th>
					</tr>
					<?php foreach($inventory as $item): ?>
					<tr>
						<td><a href="/item_page.php?itemid=<?php echo $item['inventory_id'];?>"><?php echo $item['name']?></a></td>
						<td>$<?php echo $item['price']?></td>
						<td><?php echo $item['count']?></td>
						<td><?php echo $item['description']?></td>
					</tr>
				<?php endforeach; ?>
				</table>
			</div>
		</div>
	</div>

</div>

<table class="table">
		<tr>
			<th>Name</th>
			<th>amount</th>
			<th>Price</th>
			<th>Description</th>
			<th>payment method</th>
			<th>Date ordered</th>
		</tr>

<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>