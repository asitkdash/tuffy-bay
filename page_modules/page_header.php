<?php 
	$add_money_url = "http://".$_SERVER['SERVER_NAME']."/manage_user.php";
	$logout_get_url = "http://" .$_SERVER['SERVER_NAME']. "?action=logout";

	$cart_count_nav = $tuffy_inventory->get_cart_count($_SESSION['user']['id']);
	$cart_count_nav = $cart_count_nav['COUNT(*)'];
?>

<!--header-->
<div class="top_bg">
<div class="container">
	<div class="header_top-sec">
		<div class="top_right">
			<ul>

				<li><a href="/index.php">TuffyBay</a></li>
			</ul>
		</div>
		<div class="top_left">
			<ul>

				<?php if (!$tuffy_user->is_loggedin()): ?>
					<!--case: not logged in-->
					<li><a href="/login.php" target="_self" title="Login">Login/Register</a></li>
				<?php else:?>
					<!--case: logged in-->
					<li><a href="/checkout.php"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>Cart (<?php echo "$cart_count_nav"?>)</a></li> | 
					<li><a href="/wishlist.php">Wishlist</a></li> |
				  	<li><a href="/orders.php">Purchase History</a></li> | 
					<li><?php echo "Balance: <a href=".$add_money_url.">$".$_SESSION['user']['money']."</a> | ";?></li>
					<li><a href="/manage_user.php">
					<?php
						echo "My Account: ".$_SESSION['user']['username'];
						if ($_SESSION['user']['type'] == 1)
						{
							echo "(admin)";
						}
					?>
					</a>
					</li> |
					<li><a href="<?php echo $logout_get_url; ?>">Sign Out</a></li>

				<?php endif; ?>
			</ul>
		</div>
			<div class="clearfix"> </div>
	</div>
</div>
</div>
<div class="header-top">
 <div class="header-bottom">
	 <div class="container">
			<div class="logo">

			</div>
		 <!--taking out some redundancies-->
		 <div class = "row">
		 	<div class = "col-xs-3"></div>
		 	<div class = "col-xs-6">
				<ul class="memenu skyblue">
					<li class="grid"><a href="/browse_items.php">Browse</a>
						<!--<div class="mepanel">
							<div class="col1 me-one">
								<h4>Shop</h4>
								<ul>
									<li><a href="product.php">Category 1</a></li>
									<li><a href="product.php">Category 2</a></li>
									<li><a href="product.php">Category 3</a></li>
								</ul>
							</div>
						</div>-->
					</li>
					<li class="grid">
				    <form action="search_display.php" method="post">
				        <input type="text" name="search_input">
				        <button type="submit" name="search_item">SEARCH</button>
				    </form>
					</li>
				</ul>
			<div class="clearfix"> </div>
		 
		 	</div>
		 	<div class = "col-xs-3"></div>
		 </div>
		 
		 <!---->
		 <!--<div class="cart box_1">
			 <a href="checkout.php">
				<h3> <div class="total">
				<span class="simpleCart_total"></span> (<span id="simpleCart_quantity" class="simpleCart_quantity"></span>)</div>
				<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></h3>
			</a>
			<p><a href="javascript:;" class="simpleCart_empty">Empty Cart</a></p>
			 <div class="clearfix"> </div>
		 </div>
		 <div class="clearfix"> </div>

		 </div>-->
		<div class="clearfix"> </div>
	</div>
</div>
<!---->