<?php
include 'functions.php';

//displaying a specified number of items to add can be done in javascript, doing in php for now
$number_to_add = array();

//PHP LOGIC
if (isset($_POST['create_slots']))
{
	for ($i = 0; $i < $_POST['number_of_slots']; $i++) 
	{
		array_push($number_to_add, $i);
	}	
}
else if(isset($_POST['add_items']))
{
	foreach ($_POST['items_to_delete'] as $item_info) {
		$tuffy_inventory->inventory_add_item($item_info['name'], $item_info['count'], $item_info['price'], $item_info['item_description']);
	}
}

$title = 'Tuffy Bay';
$css_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>


<div class = "container">
	<a href="/admin_page.php" class = "btn btn-info" style = "min-width: 50px; margin-left: 10px">Admin Page</a>
	<div style="border: 1px solid #eee;padding: 10px 20px;margin:10px;">
	<form method = "post">
		<table class = "table">
			<tr>
				<th>item #</th>
				<th>Name</th>
				<th>Stock</th>
				<th>Price</th>
				
				<th>description</th>
			</tr>
			<?php foreach($number_to_add as $item_index): ?>
			<tr>

				<td><?php echo $item_index + 1; ?></td>
				<td><input type="text" name="items_to_delete[<?php echo $item_index.']'; ?>[name]" required></td>
				<td><input type="text" name="items_to_delete[<?php echo $item_index.']'; ?>[count]" required></td>
				<td><input type="text" name="items_to_delete[<?php echo $item_index.']'; ?>[price]" required></td>
				
				<td><textarea name = "items_to_delete[<?php echo $item_index.']'; ?>[item_description]" required></textarea></td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php if (isset($_POST['number_of_slots'])): ?>
		<button type = "submit" name = "add_items" class = "btn btn-info">add items to inventory</button>
		<?php else: ?>
		<button type = "submit" name = "create_slots" class = "btn btn-info" disabled>add items to inventory</button>
		<?php endif; ?>
	</form>

	</div>

	<div style = "margin:10px;">
		<form method = "post" class="form-inline">
		<div>
			<?php if (!isset($_POST['number_of_slots'])): ?>
			<input type="number" name="number_of_slots" required min="1" style = "width: 40px">
			<button type = "submit" name = "create_slots">Create rows</button>
			<?php else: ?>
			
			<?php endif; ?>
		</div>
	</form>
	</div>
</div>

<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>