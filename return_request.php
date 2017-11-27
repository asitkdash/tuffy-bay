<?php
include 'functions.php';


if (isset($_POST['submit_return']))
{
	$tuffy_inventory->return_request($_POST['order_id_thispage'], $_POST['return_reason']);
	header("Location: http://" .$_SERVER['SERVER_NAME'] . "/orders_return_requests.php");
	exit;
}
else if (isset($_POST['asked_return']))
{
	$order_info = $tuffy_inventory->get_order($_POST['order_id']);
}
else
{
	header("Location: http://" .$_SERVER['SERVER_NAME']);
  	exit;
}

$title = 'Tuffy Bay';
$css_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<div class = "container">
	<div class = "row">

		<div class = "col-xs-3" style = "border: 1px solid #eee;padding: 10px 20px;margin:10px;word-wrap: break-word;">
			<div style = "font-weight: bold">name:</div>
			<p><?php echo $order_info['name']; ?></p><br>

			<div style = "font-weight: bold">payment used:</div>
			<p><?php echo $order_info['payment_used']; ?></p><br>

			<div style = "font-weight: bold">amount:</div>
			<p><?php echo $order_info['amount']; ?></p><br>

			<div style = "font-weight: bold">date ordered:</div>
			<p><?php echo get_time_ago(strtotime($order_info['date_ordered'])); ?></p>

		</div>

		<div class = "col-xs-5" style = "margin:10px;">

			
			
			
			
			<form method="post">
				<p style = "font-size: 15px; margin-bottom: 5px">Why are you returning this order? (max 128 characters)</p>
				<textarea class="form-control" rows="5" name = "return_reason" required maxlength="128"></textarea>

				<input hidden name="order_id_thispage" = value = "<?php echo $_POST['order_id']; ?>">
				<br>
				<button type = "submit" name = "submit_return" class = "btn btn-info" style = "float:right">Submit return request</button>
			</form>
		</div>

		<div class = "col-xs-4"></div>
		
	</div>
	
</div>

<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>