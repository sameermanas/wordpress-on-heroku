<?php get_header(); ?>

<?php
	$options = get_option('sf_sam5_options');
	$default_page_heading_bg_alt = $options['default_page_heading_bg_alt'];
?>	

<div class="row">
	<div class="page-heading span12 clearfix alt-bg <?php echo $default_page_heading_bg_alt; ?>">
		<h1><?php _e("Shop", "swiftframework"); ?></h1>
	</div>
</div>

<?php if(function_exists('bcn_display')) { ?>	
<div class="breadcrumbs-wrap row">
	<div id="breadcrumbs" class="span12 alt-bg">
		<?php bcn_display(); ?>
	</div>
</div>
<?php } ?>

<div class="inner-page-wrap row has-right-sidebar top-spacing clearfix">

	<!-- OPEN article -->
	<div class="type-page type-woocommerce span8 clearfix">
		
		<div class="page-content clearfix">

			<?php woocommerce_content(); ?>
			
		</div>
			
	<!-- CLOSE article -->
	</div>
	
	<aside class="sidebar right-sidebar span4">
		<?php dynamic_sidebar('woocommerce-sidebar'); ?>
	</aside>

</div>

<!--// WordPress Hook //-->
<?php get_footer(); ?>