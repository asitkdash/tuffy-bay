<?php
include 'functions.php';

$inv_items = $tuffy_inventory->inventory_display();

$title = 'Tuffy Bay';
$css_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<div class="container">
		 <h3>All items:</h3>
		 <small id="emailHelp" class="form-text text-muted">displays 10 for now</small>
		 <div class="feature-grids">
		 	<?php foreach($inv_items as $item): ?>
		 	<?php
		 		//link setup
		 		$item_link = "/item_page.php?itemid=".$item['id']; 

		 		//image setup
				$img_file = "/images/item_".$item['id'].".jpg";
				if (!file_exists($_SERVER['DOCUMENT_ROOT'].$img_file))
				{
					$img_file = "/images/item_default.png";
				}
		 	?>
			 <div class="col-md-3 feature-grid">
				<a href="<?php echo $item_link; ?>">
					<img src="<?php echo $img_file; ?>" alt=""/>
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

<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>