<?php
include 'functions.php';

//HEADER
$title = 'TuffyBay';
$css_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>
<?php $inv_items = $tuffy_inventory->inventory_display(); ?>

<div>
  <img src="/images/TuffyBay_Banner.png" alt="Smiley face" width="100%"/>
</div>

<div class="welcome">
	 <div class="container">
		 <div class="col-md-3 welcome-left">
			 <h2>Welcome to our site</h2>
		 </div>
		 <div class="col-md-9 welcome-right">
			 <h3>The Official CSUF Online Webstore</h3>
			 <p>TuffyBay is an electronic commerce website dedicated to selling novelty college supplies to the students, faculty members, and supporters of California State University, Fullerton. By partnering with Student Store, TuffyBay provides a quick and easy way for students and faculty members to purchase college necessities at their own leisure. TuffyBay will provide an attractive design geared towards students. Through TuffyBay, supporters of California State University, Fullerton will now be able to show their support from anywhere within the states.</p>
		 </div>
	 </div>
</div>
<!---->
<div class="featured">
	 <div class="container">
		 <h3>Featured Products</h3>
		 <div class="feature-grids">
		 	<?php foreach($inv_items as $item): ?>
		 	<?php
		 		$item_link = "/item_page.php?itemid=".$item['id']; 

		 	?>
			 <div class="col-md-3 feature-grid">
				<a href="<?php echo $item_link; ?>">
					<img src="https://upload.wikimedia.org/wikipedia/commons/6/6a/A_blank_flag.png" alt=""/>
					<div class="arrival-info">
						<h4></h4>
						<p><?php echo $item['name']; ?></p>
						<span class="pric1">$<?php echo $item['price']?></span>
					</div>
					<div class="viw">
						<a href="<?php echo $item_link; ?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Quick View</a>
					</div>
				</a>
			 </div>
			 <?php endforeach; ?>
			 <div class="clearfix"></div>
		 </div>
	 </div>
</div>
<!---->

<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>