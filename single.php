<?php
include 'functions.php';

  //PHP LOGIC
  $item = $tuffy_inventory->inventory_get_item($_GET['itemid']);
  $cart_url = "Location: http://" .$_SERVER['SERVER_NAME'] . "/checkout.php";

  if (isset($_POST['add_to_cart']))
  {
    if (!$tuffy_user->is_loggedin())
    {
      $msg = "must log in first";
    }
    else
    {
      $tuffy_inventory->add_to_cart($_GET['itemid'], $_SESSION['user']['id'], $_POST['num_to_buy']);
      header($cart_url);
          exit;
    }
  }
  else if (isset($_POST['add_to_wishlist']))
  {

    $hi = $tuffy_inventory->insert_wishlist($_SESSION['user']['id'], $item['id']);
    var_dump($hi);
  }

  //HEADER
  $title = $item['name'];
  $css_files = array();
  include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<div class="single-sec">
	 <div class="container">
		 <ol class="breadcrumb">
			 <li><a href="index.html">Home</a></li>
			 <li class="active">Products</li>
		 </ol>
		 <!-- start content -->
		 <div class="col-md-9 det">
				 <div class="single_left">
					 <div class="flexslider">
             <ul class="slides">
               <li data-thumb="https://upload.wikimedia.org/wikipedia/commons/6/6a/A_blank_flag.png">
                 <img src="https://upload.wikimedia.org/wikipedia/commons/6/6a/A_blank_flag.png" />
               </li>
             </ul>
           </div>
           <!-- FlexSlider -->
             <script defer src="js/jquery.flexslider.js"></script>
           <link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
             <script>
           // Can also be used with $(document).ready()
           $(window).load(function() {
             $('.flexslider').flexslider({
             animation: "slide",
             controlNav: "thumbnails"
             });
           });
           </script>
				 </div>
				  <div class="single-right">
					 <h3><?php echo $item['name']?></h3>
					 <div class="id"><h4>ID: <?php echo $item['id']?></h4></div>
					  <div class="cost">
						  <div class="prdt-cost">
							 <ul>
								 <li>Price:</li>
								 <li class="active">$<?php echo $item['price']?></li>
							 </ul>
             </div>
						 <div class="clearfix"></div>
					  </div>
              <small>stock: <?php echo $item['count']?></small>

             <?php if ($tuffy_user->is_loggedin()): ?>
              <form method="post">
                <label >number of items: </label>
                <input type="number" name="num_to_buy" value="1">
                <input type="submit" class="button" value="Add to cart" name="add_to_cart">
              </form>

              <?php if ($tuffy_inventory->already_wishlisted($_SESSION['user']['id'], $item['id'])): ?>
                <small>already on wishlist</small>
              <?php else: ?>
                <form method = "post">
                  <button type = "submit" name = "add_to_wishlist">Add to wishlist</button>
                </form>
              <?php endif; ?>
            <?php else: ?>
              <input type="submit" class="button" value="Add to cart" name="add_to_cart" disabled>
              <button type = "submit" name = "add_to_wishlist" disabled>Add to wishlist</button>
              <small style = "color:red">must be logged in to add to cart/add to wishlist</small>
            <?php endif; ?>

            <h2 style="color:red"><?php echo $msg; ?></h2>

					  <div class="single-bottom1">
						<h6>Details</h6>
						<p class="prod-desc"><?php echo $item['description']?></p>
					 </div>
				  </div>
				  <div class="clearfix"></div>
      <!---->
  </div>
</div>

<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>