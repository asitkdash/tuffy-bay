<?php
//use javascript to make sure user puts atleast one checkbox
include 'functions.php';

//if not admin or higher kick them out
if ($_SESSION['user']['type'] < 1 || !$tuffy_user->is_loggedin())
{
	header("Location: http://" .$_SERVER['SERVER_NAME']);
	/* Make sure that code below does not get executed when we redirect. */
	exit;
}

$index = 0;
$edit_index = 0;
$checked_items = array();

if (isset($_POST['remove_items']) && $_POST['selected_array'] != null)
{
	foreach ($_POST['selected_array'] as $item_id)
	{
		$tuffy_inventory->inventory_delete_item($item_id);
	}
}
else if (isset($_POST['picked_edit']) && $_POST['selected_array'] != null)
{
	foreach ($_POST['selected_array'] as $item_id)
	{
		$item = $tuffy_inventory->inventory_get_item($item_id);
		array_push($checked_items, $item);
	}
}
if (isset($_POST['edit_items']) && $_POST['items_to_edit'] != null)
{
	foreach ($_POST['items_to_edit'] as $item)
	{
		$tuffy_inventory->inventory_update_item($item['id'], $item['name'], $item['count'], $item['price'], $item['description']);
	}
}

$inventory = $tuffy_inventory->inventory_display();
$title = 'Tuffy Bay';
$css_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>
<a href="/admin_page.php" class = "btn btn-info" style = "min-width: 50px; margin-left: 10px">Admin Page</a>
<!--item display/editing/deleting-->
<div style="border: 1px solid #eee;padding: 10px 20px;margin:10px;">
	<h4 style = "padding: 0px 5px 10px 0px"><strong>current inventory:</strong></h4>

	<?php if (!isset($_POST['picked_edit'])): ?>
		<form method = "post">
		<table class = "table">
			<tr>
				<th></th>
				<th>Name</th>
				<th>Price</th>
				<th>Stock</th>
				<th>description</th>
			</tr>
			<?php foreach($inventory as $item): ?>
			<tr>
				<td><input class="form-check-input" type="checkbox" value="<?php echo $item['id']; ?>" name = "<?php echo 'selected_array['.$index.']'; ?>"></td>
				<td><a href="/item_page.php?itemid=<?php echo $item['id'];?>"><?php echo $item['name']?></a></td>
				<td>$<?php echo $item['price']?></td>
				<td><?php echo $item['count']?></td>
				<td><?php echo $item['description']?></td>
				<?php $index++; ?>
			</tr>
		<?php endforeach; ?>
		</table>
		<?php if (!$inventory == null): ?>
			<button type = "submit" name = "remove_items" class = "btn btn-info" style = "margin-top:15px">Remove selected items</button>
			<button type = "submit" name = "picked_edit" class = "btn btn-info" style = "margin-top:15px;margin-left:7px">Edit selected items</button>
		<?php else: ?>
			<button type = "submit" name = "remove_items" class = "btn btn-info" disabled style = "margin-top:15px">Inventory is empty</button>
		<?php endif; ?>
		</form>
	<?php else: ?>
		<form method = "post">
		<table class = "table">
			<tr>
				<th>item #</th>
				<th>Name</th>
				<th>Price</th>
				<th>Stock</th>
				<th>description</th>
			</tr>
			<?php foreach($checked_items as $item): ?>
			<tr>
				<td><?php echo $edit_index + 1; ?></td>
				<input type="text" name="items_to_edit[<?php echo $edit_index.']'; ?>[id]" value = "<?php echo $item['id']; ?>" hidden>
				<td><input type="text" name="items_to_edit[<?php echo $edit_index.']'; ?>[name]" value = "<?php echo $item['name'] ?>" required></td>
				<td><input type="text" name="items_to_edit[<?php echo $edit_index.']'; ?>[count]" value = "<?php echo $item['count'] ?>" required></td>
				<td><input type="text" name="items_to_edit[<?php echo $edit_index.']'; ?>[price]" value = "<?php echo $item['price'] ?>" required></td>
				
				<td><textarea name = "items_to_edit[<?php echo $edit_index.']'; ?>[description]" required><?php echo $item['description'] ?></textarea></td>
				<?php $edit_index++; ?>
			</tr>
		<?php endforeach; ?>
		</table>
		<button type = "submit" name = "edit_items">Edit items</button>
		<?php endif; ?>
		</form>

</div>

<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>