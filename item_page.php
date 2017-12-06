<?php
include 'functions.php';

  //PHP LOGIC
  $item = $tuffy_inventory->inventory_get_item($_GET['itemid']);
  if (empty($item))
  {
    header("HTTP/1.0 404 Not Found");
    exit;
  }
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

    $tuffy_inventory->insert_wishlist($_SESSION['user']['id'], $item['id']);
  }

  //image setup
  $img_file = "/images/item_".$_GET['itemid'].".jpg";
  if (!file_exists($_SERVER['DOCUMENT_ROOT'].$img_file))
  {
    $img_file = "/images/item_default.png";
  }

  $rate_count = $tuffy_inventory->get_num_of_rates($_GET['itemid']);

  //HEADER
  $title = $item['name'];
  $css_files = array('rating_item_page.css');
  include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<div class="single-sec">
	 <div class="container">
		 <ol class="breadcrumb">
			 <li><a href="index.php">Home</a></li>
			 <li class="active">Products</li>
		 </ol>
		  <!-- start content -->
		  <div class="col-md-9 det">
				 <div class="single_left">
					 <div class="flexslider">
             <ul class="slides">
               <li data-thumb="<?php echo $img_file; ?>">
                 <img src="<?php echo $img_file; ?>" />
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
         <h4>Product rating</h4>
         <?php $rating = $tuffy_inventory->get_item_rating($item['id']); ?>
          <fieldset class="rating" style = "padding-right: 5px">
            <input type="radio" id="star5" name="rating" value="5" <?php if($rating > 4.5){echo "checked";} ?> disabled/>
            <label class = "full" for="star5" title="Awesome - 5 stars"></label>

            <input type="radio" id="star4half" name="rating" value="4.5" <?php if($rating > 4 && $rating <= 4.5){echo "checked";} ?> disabled/>
            <label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>

            <input type="radio" id="star4" name="rating" value="4" <?php if($rating > 3.5 && $rating <= 4){echo "checked";} ?> disabled/>
            <label class = "full" for="star4" title="Pretty good - 4 stars"></label>

            <input type="radio" id="star3half" name="rating" value="3.5" <?php if($rating > 3 && $rating <= 3.5){echo "checked";} ?> disabled/>
            <label class="half" for="star3half" title="Meh - 3.5 stars"></label>

            <input type="radio" id="star3" name="rating" value="3" <?php if($rating > 2.5 && $rating <= 3){echo "checked";} ?> disabled/>
            <label class = "full" for="star3" title="Meh - 3 stars"></label>

            <input type="radio" id="star2half" name="rating" value="2.5" <?php if($rating > 2 && $rating <= 2.5){echo "checked";} ?> disabled/>
            <label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>

            <input type="radio" id="star2" name="rating" value="2" <?php if($rating > 1.5 & $rating <= 2){echo "checked";} ?> disabled/>
            <label class = "full" for="star2" title="Kinda bad - 2 stars"></label>

            <input type="radio" id="star1half" name="rating" value="1.5" <?php if($rating > 1 && $rating <= 1.5){echo "checked";} ?> disabled/>
            <label class="half" for="star1half" title="Meh - 1.5 stars"></label>

            <input type="radio" id="star1" name="rating" value="1" <?php if($rating > 0.5 && $rating <= 1){echo "checked";} ?> disabled/>
            <label class = "full" for="star1" title="Sucks big time - 1 star"></label>

            <input type="radio" id="starhalf" name="rating" value="0.5" <?php if($rating > 0 && $rating <= 0.5){echo "checked";} ?> disabled/>
            <label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
          </fieldset>
          <div style = "padding-top: 10px;font-size: 20px;">
            ( <span style = "color:#7f7854""><?php echo $rating; ?> / 5.0</span> ) <?php echo "by " .$rate_count. " users "; ?>
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
              <small style = "font-size: 18px">stock: <?php echo $item['count']?></small>

             <?php if ($tuffy_user->is_loggedin()): ?>
              <form method="post">
                <label> number of items: </label>
                <input type="number" name="num_to_buy" value="1" style = "width: 70px">
                <button type = "submit" class = "btn btn-info" name="add_to_cart" style = "padding: 1px 10px">Add to cart<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></button>

              </form>

              <?php if ($tuffy_inventory->already_wishlisted($_SESSION['user']['id'], $item['id'])): ?>
                <small>already on wishlist</small>
              <?php else: ?>
              	<br>
                <form method = "post">
                  <button type = "submit" name = "add_to_wishlist" class = "btn btn-info">Add to wishlist</button>
                </form>
              <?php endif; ?>
            <?php else: ?>
            	<br>
              <button type = "submit" class = "btn btn-info" disabled>Add to cart</button>
              <button type = "submit" class = "btn btn-info" disabled>Add to wishlist</button>
              <br>
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