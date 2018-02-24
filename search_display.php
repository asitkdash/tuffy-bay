<?php
include 'functions.php';
$tuffy_inventory = new tuffy_inventory($DB_connection);


if (isset($_POST['search_item']))
{
    $search_result = $tuffy_inventory->search_item($_POST['search_input']);
}
//HEADER
$title = 'Tuffy Bay';
$css_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_header.php';
?>

<!--SEARCH BAR-->

<div class="container">
	<div style="margin-top: 50px;" class="row" align="center">
		<?php if (!isset($_POST['search_item'])): ?>
		<h3>no search input</h3>
		<?php elseif ($search_result !== null): ?>
			<?php foreach($search_result as $item): ?>
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
		<?php else: ?>
			<h3>found no matches</h3>
		<?php endif; ?>
	</div>
</div>


<?php
$js_files = array();
include $_SERVER['DOCUMENT_ROOT'] . '/page_modules/html_footer.php';
?>