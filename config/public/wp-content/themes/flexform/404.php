<?php get_header(); ?>

<?php
	$options = get_option('sf_flexform_options');
	$default_page_heading_bg_alt = $options['default_page_heading_bg_alt'];
?>

<div class="row">
<div class="page-heading span12 clearfix alt-bg <?php echo $default_page_heading_bg_alt; ?>">
	<h1><?php _e("404", "swiftframework"); ?></h1>
</div>
</div>

<div class="inner-page-wrap row has-right-sidebar has-one-sidebar clearfix">

	<article class="help-text span8">
		<?php _e("Sorry but we couldn't find the page you are looking for. Please check to make sure you've typed the URL correctly. You may also want to search for what you are looking for.Downloaded from http://www.96down.com", "swiftframework"); ?> 
		<?php get_template_part('searchform'); ?>
		<a class="sf-button small accent slightlyroundedarrow" href="javascript:history.go(-1)" target="_self"><span><?php _e("Return to the previous page", "swiftframework"); ?></span><span class="arrow"></span></a>
	</article>
	
	<aside class="sidebar right-sidebar span4">
		<?php dynamic_sidebar('Sidebar-1'); ?>
	</aside>
	
</div>

<!--// WordPress Hook //-->
<?php get_footer(); ?>