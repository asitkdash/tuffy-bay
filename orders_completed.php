<?php
include 'functions.php';

//if user is not logged in, kick them out
if(!$tuffy_user->is_loggedin())
{
	header("Location: http://" .$_SERVER['SERVER_NAME']);
	exit;
}

//Rating system
if (isset($_POST['rating']))
{
	$tuffy_inventory->rate_item($_POST['order_id2'], $_POST['item_id2'], $_POST['rating']);
}

$finished_orders = array();
$orders = $tuffy_inventory->display_orders($_SESSION['user']['id']);
foreach ($orders as $item_order)
{
	//completed orders that aren't being returned
	if ($item_order['has_arrived'] == 1 && $item_order['return_request'] == 0)
	{
		array_push($finished_orders, $item_order);
	}
}


$title = 'Tuffy Bay';
$css_files = array('bootstrap.css');
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<style type="text/css">
				@import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);

			.rating fieldset, .rating label { margin: 0; padding: 0; }

			/****** Style Star Rating Widget *****/

			.rating { 
			  border: none;
			  float: left;
			}

			.rating > input { display: none; } 
			.rating > label:before { 
			  margin: 5px;
			  font-size: 1.25em;
			  font-family: FontAwesome;
			  display: inline-block;
			  content: "\f005";
			}

			.rating > .half:before { 
			  content: "\f089";
			  position: absolute;
			}

			.rating > label { 
			  color: #ddd; 
			 float: right; 
			}


			/***** NON-INTERACTIVE 5 STAR *****/
			.non-interactive .rating > label:before { 
			  margin: 5px;
			}

			/***** CSS Magic to Highlight Stars on Hover *****/
			.gold {color: #FFED85 !important;}

			/***** INTERACTIVE/INPUT 5 STAR *****/
			.interactive .rating > label:before { 
			  margin: 0px 5px;

			}

			/***** CSS Magic to Highlight Stars on Hover *****/

			.interactive .rating > input:checked ~ label, /* show gold star when clicked */
			.interactive .rating:not(:checked) > label:hover, /* hover current star */
			.interactive .rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */

			.interactive .rating > input:checked + label:hover, /* hover current star when changing rating */
			.interactive .rating > input:checked ~ label:hover,
			.interactive .rating > label:hover ~ input:checked ~ label, /* lighten current selection */
			.interactive .rating > input:checked ~ label:hover ~ label { color: #FFED85;  } 
</style>

<div class = "container-fluid">
	<div>
		<ul class = "nav nav-tabs">
			<li><a href="/orders.php">Open Orders (in delivery)</a></li>
			<li class="active"><a href="#">Completed Orders</a></li>
			<li><a href="/orders_return_requests.php">Return Requests</a></li>
			<li><a href="/orders_completed_returns.php">Completed Returns</a></li>
		</ul>
	</div>



	<h2>Completed Orders: </h2>

	<table class="table">
		<tr>
			<th>Name</th>
			<th>amount</th>
			<th>Price</th>
			<th>Description</th>
			<th>payment method</th>
			<th>Date ordered</th>
			<th>Date Arrived</th>
			<th>actions</th>
			<th>rate this product</th>
		</tr>
	<?php foreach($finished_orders as $item): ?>
	<div class="row">
		<div class = "col-xs-12">
		<tr>
			<td><a href="/item_page.php?itemid=<?php echo $item['inventory_id'];?>"><?php echo $item['name']?></a></td>
			<td><?php echo $item['amount']?></td>
			<td>$<?php echo $item['price']?></td>
			<td><?php echo $item['description']?></td>
			<td><?php echo $item['payment_used']?></td>
			<td><?php echo get_time_ago(strtotime($item['date_ordered'])); ?></td>
			<td><?php echo get_time_ago(strtotime($item['date_arrived'])); ?></td>
			<td>
			<form method="post" action="return_request.php">
				<input hidden name="order_id" = value = "<?php echo $item['id']; ?>">
				<button type = "submit" name = "asked_return" class = "btn btn-info">return</button>
			</form>
			</td>
			<!--CASE: ITEM NOT RATED YET-->
			<?php if ($item['rated'] == 0): ?>

			<td style = "padding-left: 2px" class = "interactive">
			<form method = "post">
				<input hidden name="order_id2" = value = "<?php echo $item['id']; ?>">
				<input hidden name="item_id2" = value = "<?php echo $item['inventory_id']; ?>">

				<fieldset class="rating">
				    <input type="radio" id="star5<?php echo $item['id']; ?>" name="rating" value="5" onclick="this.form.submit()"/>
				    <label class = "full" for="star5<?php echo $item['id']; ?>" title="Awesome - 5 stars"></label>

				    <input type="radio" id="star4half<?php echo $item['id']; ?>" name="rating" value="4.5" onclick="this.form.submit()"/>
				    <label class="half" for="star4half<?php echo $item['id']; ?>" title="Pretty good - 4.5 stars"></label>

				    <input type="radio" id="star4<?php echo $item['id']; ?>" name="rating" value="4" onclick="this.form.submit()"/>
				    <label class = "full" for="star4<?php echo $item['id']; ?>" title="Pretty good - 4 stars"></label>

				    <input type="radio" id="star3half<?php echo $item['id']; ?>" name="rating" value="3.5" onclick="this.form.submit()"/>
				    <label class="half" for="star3half<?php echo $item['id']; ?>" title="Meh - 3.5 stars"></label>

				    <input type="radio" id="star3<?php echo $item['id']; ?>" name="rating" value="3" onclick="this.form.submit()"/>
				    <label class = "full" for="star3<?php echo $item['id']; ?>" title="Meh - 3 stars"></label>

				    <input type="radio" id="star2half<?php echo $item['id']; ?>" name="rating" value="2.5" onclick="this.form.submit()"/>
				    <label class="half" for="star2half<?php echo $item['id']; ?>" title="Kinda bad - 2.5 stars"></label>

				    <input type="radio" id="star2<?php echo $item['id']; ?>" name="rating" value="2" onclick="this.form.submit()"/>
				    <label class = "full" for="star2<?php echo $item['id']; ?>" title="Kinda bad - 2 stars"></label>

				    <input type="radio" id="star1half<?php echo $item['id']; ?>" name="rating" value="1.5" onclick="this.form.submit()"/>
				    <label class="half" for="star1half<?php echo $item['id']; ?>" title="Meh - 1.5 stars"></label>

				    <input type="radio" id="star1<?php echo $item['id']; ?>" name="rating" value="1" onclick="this.form.submit()"/>
				    <label class = "full" for="star1<?php echo $item['id']; ?>" title="Sucks big time - 1 star"></label>
				    
				    <input type="radio" id="starhalf<?php echo $item['id']; ?>" name="rating" value="0.5" onclick="this.form.submit()"/>
				    <label class="half" for="starhalf<?php echo $item['id']; ?>" title="Sucks big time - 0.5 stars"></label>
				</fieldset>
			</form>
			</td>
			<?php else: ?>
			<?php $rating = $tuffy_inventory->get_personal_rating($item['id']); ?>
			 <td class = "non-interactive">
          <fieldset class="rating" style = "padding-right: 5px">
            <input type="radio" id="star5" name="rating" value="5" <?php if($rating > 4.5){echo "checked";} ?> disabled/>
            <label for="star5" title="Awesome - 5 stars" class = "<?php if($rating > 4.5 ){echo 'gold';}else{echo 'full';} ?>">
            </label>

            <input type="radio" id="star4half" name="rating" value="4.5" <?php if($rating > 4 && $rating <= 4.5){echo "checked";} ?> disabled/>
            <label for="star4half" title="Pretty good - 4.5 stars" class = "<?php if($rating > 4){echo 'half gold';}else{echo 'half';} ?>">
            </label>

            <input type="radio" id="star4" name="rating" value="4" <?php if($rating > 3.5 && $rating <= 4){echo "checked";} ?> disabled/>
            <label for="star4" title="Pretty good - 4 stars" class = "<?php if($rating > 3.5 ){echo 'gold';}else{echo 'full';} ?>">
            </label>

            <input type="radio" id="star3half" name="rating" value="3.5" <?php if($rating > 3 && $rating <= 3.5){echo "checked";} ?> disabled/>
            <label for="star3half" title="Meh - 3.5 stars" class = "<?php if($rating > 3){echo 'half gold';}else{echo 'half';} ?>">
            </label>

            <input type="radio" id="star3" name="rating" value="3" <?php if($rating > 2.5 && $rating <= 3){echo "checked";} ?> disabled/>
            <label for="star3" title="Meh - 3 stars" class = "<?php if($rating > 2.5 ){echo 'gold';}else{echo 'full';} ?>">
            </label>

            <input type="radio" id="star2half" name="rating" value="2.5" <?php if($rating > 2 && $rating <= 2.5){echo "checked";} ?> disabled/>
            <label for="star2half" title="Kinda bad - 2.5 stars" class = "<?php if($rating > 2){echo 'half gold';}else{echo 'half';} ?>">
            </label>

            <input type="radio" id="star2" name="rating" value="2" <?php if($rating > 1.5 & $rating <= 2){echo "checked";} ?> disabled/>
            <label for="star2" title="Kinda bad - 2 stars" class = "<?php if($rating > 1.5 ){echo 'gold';}else{echo 'full';} ?>">
            </label>

            <input type="radio" id="star1half" name="rating" value="1.5" <?php if($rating > 1 && $rating <= 1.5){echo "checked";} ?> disabled/>
            <label for="star1half" title="Meh - 1.5 stars" class = "<?php if($rating > 1){echo 'half gold';}else{echo 'half';} ?>">
            </label>

            <input type="radio" id="star1" name="rating" value="1" <?php if($rating > 0.5 && $rating <= 1){echo "checked";} ?> disabled/>
            <label for="star1" title="Sucks big time - 1 star" class = "<?php if($rating > 0.5){echo 'gold';}else{echo 'full';} ?>">	
            </label>

            <input type="radio" id="starhalf" name="rating" value="0.5" <?php if($rating > 0 && $rating <= 0.5){echo "checked";} ?> disabled/>
            <label for="starhalf" title="Sucks big time - 0.5 stars" class = "<?php if($rating > 0){echo 'half gold';}else{echo 'half';} ?>">
            </label>

          </fieldset>
          </td>
			<?php endif; ?>
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