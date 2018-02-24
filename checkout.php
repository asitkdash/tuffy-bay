<?php
include 'functions.php';

//if user is not logged in, kick them out
if(!$tuffy_user->is_loggedin())
{
  header("Location: http://" .$_SERVER['SERVER_NAME']);
  exit;
}

//CART LOGIC
$cart = $tuffy_inventory->display_cart($_SESSION['user']['id']);
$total_price = 0.00;
$not_enough_stock = false;
$not_enough_money = true;

if (isset($_POST['order_cart']))
{
	//"use credit card" option is picked, modify the payment method value to include the last 4 digits of card
	if (isset($_POST['payment_method']) && $_POST['payment_method'] == "use_this_card")
	{
		$_POST['payment_method'] = "**** **** **** ".$_POST['creditCard4'];
	}

	//Checkbox "Save Credit Card" is checked
	if (isset($_POST['add_card_to_account']))
	{
		$security_code = $_POST['security_code'];
	    $card_num = $_POST['creditCard1'] . $_POST['creditCard2'] . $_POST['creditCard3'] . $_POST['creditCard4'];
		$tuffy_user->insert_card_info($_SESSION['user']['id'], $card_num, $security_code);
	}

	$tuffy_inventory->purchase_cart($_SESSION['user']['id'], $cart, $_POST['total_price'], $_POST['payment_method'], $_POST['rewards_amount']);
	header("Location: http://" .$_SERVER['SERVER_NAME'] . "/orders.php");
	exit;
}
else if(isset($_POST['update_quan']))
{
	$tuffy_inventory->update_cart_count($_SESSION['user']['id'], $_POST['item_id'], $_POST['quantity']);
	$cart = $tuffy_inventory->display_cart($_SESSION['user']['id']);
}
else if (isset($_POST['delete_shop_item']))
{
	$tuffy_inventory->delete_cart_count($_SESSION['user']['id'], $_POST['item_id']);
	$cart = $tuffy_inventory->display_cart($_SESSION['user']['id']);
}


//HEADER
$title = 'Tuffy Bay';
$css_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<div class="checkout">
	 <div class="container">
		 <ol class="breadcrumb">
		  <li><a href="index.php">Home</a></li>
		  <li class="active">Cart</li>
		 </ol>
		 <div class="col-md-9 product-price1">
			 <div class="check-out">
				 <div class=" cart-items">
					 <h3>My Cart (<span id="num-items"><?php echo count($cart); ?></span>)</h3>

					 <div class="in-check" >
						  <ul class="unit">
							<li><span>Item</span></li>
							<li><span>Product Name</span></li>
							<li><span>Unit Price</span></li>
							<li><span>Quantity</span></li>
							<div class="clearfix"> </div>
						  </ul>
						  <?php foreach($cart as $item): ?>

						  	<?php 
							$total_price += $item['price'] * $item['in_cart_count'];

						    if ($item['in_cart_count'] > $item['stock_count'])
						    {
						      $not_enough_stock = true;
						    }

						    ?>

						  <ul class="cart-header" style="border-bottom: 2px solid #eee;">
						   
							<li class="ring-in"><a href="single.html" ><img src="https://upload.wikimedia.org/wikipedia/commons/6/6a/A_blank_flag.png" class="img-responsive" alt=""></a>
							</li>
							<li><span><?php echo $item['name']; ?></span></li>
							<li><span>$<?php echo $item['price']; ?></span></li>
							<li>
								<span>
								<form method = "post">
									<input hidden type="number" name="item_id" value = "<?php echo $item['id']; ?>">
									<input type="number" name="quantity" min="1" value = "<?php echo $item['in_cart_count']; ?>">
									<button style="margin-top: 5px;" type = "submit" name = "update_quan">update</button>
									<button style="margin-top: 5px;" type = "submit" name = "delete_shop_item">delete</button>
								</form>
								</span>
							</li>
							<div class="clearfix"> </div>

							<?php 
							if ($item['in_cart_count'] > $item['stock_count'])
							{
							echo "<div style='font-size: 12px;padding-top:15px;color:red'>(CANNOT PURCHASE: we currently only have ".$item['stock_count']." of these in stock)</div>";
							}
							?>

							</ul>
							<?php endforeach; ?>
					 </div>
				  </div>
			 </div>
		 </div>

		 <!--TOTAL CART DETAILS-->
		 <?php 
		 	$total_price = number_format((float)$total_price, 2, '.', '');
		 	if ($_SESSION['user']['money'] >= $total_price)
		 	{
		 		$not_enough_money = false;
		 	}

		 	$last4display = substr($_SESSION['user']['credit_card_num'], -4);
		 ?>

		<?php if (empty($cart)): ?>
		<div style="height:300px">
			<h3>Cart is empty</h3>
		</div>
		<?php else: ?>
		 <div class="col-md-3 cart-total">
			 <div class="price-details">
				 <h3>Price Details</h3>
				 <span>Total</span>
				 <span class="total" id="total-price">$<?php echo $total_price; ?></span>
				 <span>Tax</span>
				 <span class="total" id="tax-price">$0.00</span>
				 <div class="clearfix"></div>
			 </div>
			 <h4 class="last-price">TOTAL</h4>
			 <span class="total final" id="final-price">$<?php echo $total_price; ?></span>
			 <div class="clearfix"></div>

			 <!--REWARDS-->
			 <br>
			 <div>
			 	<?php 
			 		$total_rewards = $total_price * 0.10;
			 		$total_rewards = number_format((float)$total_rewards, 2, '.', '');
			 	?>
			 </div>

			<!--Place order + Payment method-->
			<?php if ($not_enough_stock): ?>
			<h3>Not enough in stock</h3>
			<?php else: ?>
			<form method = "post">
				<button class="order" type = "submit" name = "order_cart" style = "border:none">Place Order</button>

				<!--rewards-->
				<input hidden name="rewards_amount" value = "<?php echo $total_rewards; ?>">
				<div style = "color:#47aa63;padding-top: 5px; text-align: center"> 
					<span style = "color: #000; font-size: 20px">Tuffy Rewards</span>
					<br>
					<?php echo "Get $".$total_rewards. " Back!"; ?>
				</div>

				<br>
				<h4>Choose payment method: </h4>
				<br>

				<!--buying with tuffy money-->
				<?php if ($not_enough_money): ?>
		 		<input type="radio" disabled> Tuffy Money <span style = "color:#b72626">(not enough money)</span>
		 		<div style = "padding-left:17px;"><a style = "color: #8ba1c4" href="/manage_user.php">Add more</a></div>
		 		<?php else:?>
		 		<input hidden name="total_price" value = "<?php echo $total_price; ?>">
		 		<input id="tuffy_money_id" type="radio" name="payment_method" value = "tuffy money" required> Tuffy Money
		 		<?php endif; ?>

		 		<br>
		 		<!--buying with credit card-->
		 		<?php if (!isset($_SESSION['user']['credit_card_num'])): ?>
		 		<input id="credit_card_id" type="radio" name="payment_method" value = "use_this_card" required></input> Use credit card: <br>
				Credit Card Number:<br>
					<input disabled class = "card_req" type="number" min="1000" max="9999" name="creditCard1" />
					-
					<input disabled class = "card_req" type="number" min="1000" max="9999" name="creditCard2" />
					-
					<input disabled class = "card_req" type="number" min="1000" max="9999" name="creditCard3" />
					-
					<input disabled class = "card_req" type="number" min="1000" max="9999"  name="creditCard4" />
					<br />
					<br>

				    Security Code: <br>
				    <input disabled class = "card_req" type="number" name="security_code"><br><br>
				    Card Expiry: <br>
				    <input disabled class="card_req" name="expiry" id="expiry" type="month"><br><br>
				   
				   	<input disabled class = "card_optional" type="checkbox" name="add_card_to_account"> Save Credit Card
				  	
		 		<?php else: ?>
		 		<input type="radio" name="payment_method" value = "credit card: **** **** **** <?php echo $last4display; ?>" required> Use credit card: <?php echo "**** **** **** ".$last4display; ?>
		 		<?php endif; ?>
		 	</form>
			<?php endif; ?>
	 	</div>
	 	<?php endif; ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
        $("#credit_card_id").click(function() {
            $(".card_req").prop("required", true);
            $(".card_req").prop("disabled", false);
            $(".card_optional").prop("disabled", false);
        });
        $("#tuffy_money_id").click(function() {
            $(".card_req").prop("required", false);
            $(".card_req").prop("disabled", true);
            $(".card_optional").prop("disabled", true);
            $(".card_optional").prop("checked", false);
        });
</script>

<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>