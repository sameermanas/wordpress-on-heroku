<?php get_header(); ?>

<?php	
	
	$options = get_option('sf_sam5_options');
	$default_show_page_heading = $options['default_show_page_heading'];
	$default_page_heading_bg_alt = $options['default_page_heading_bg_alt'];
	$default_sidebar_config = $options['default_sidebar_config'];
	$default_left_sidebar = $options['default_left_sidebar'];
	$default_right_sidebar = $options['default_right_sidebar'];
	
	$show_page_title = get_post_meta($post->ID, 'sf_page_title', true);
	$page_title_one = get_post_meta($post->ID, 'sf_page_title_one', true);
	$page_title_two = get_post_meta($post->ID, 'sf_page_title_two', true);
	$page_title_bg = get_post_meta($post->ID, 'sf_page_title_bg', true);
	
	if ($show_page_title == "") {
		$show_page_title = $default_show_page_heading;
	}
	if ($page_title_bg == "") {
		$page_title_bg = $default_page_heading_bg_alt;
	}
	
	$full_width_display = get_post_meta($post->ID, 'sf_full_width_display', true);
	$show_author_info = get_post_meta($post->ID, 'sf_author_info', true);
	$show_social = get_post_meta($post->ID, 'sf_social_sharing', true);
	$show_related =  get_post_meta($post->ID, 'sf_related_articles', true);
	
	$sidebar_config = get_post_meta($post->ID, 'sf_sidebar_config', true);
	$left_sidebar = get_post_meta($post->ID, 'sf_left_sidebar', true);
	$right_sidebar = get_post_meta($post->ID, 'sf_right_sidebar', true);
	
	if ($sidebar_config == "") {
		$sidebar_config = $default_sidebar_config;
	}
	if ($left_sidebar == "") {
		$left_sidebar = $default_left_sidebar;
	}
	if ($right_sidebar == "") {
		$right_sidebar = $default_right_sidebar;
	}
	
	$page_wrap_class = '';
	if ($sidebar_config == "left-sidebar") {
	$page_wrap_class = 'has-left-sidebar has-one-sidebar row';
	} else if ($sidebar_config == "right-sidebar") {
	$page_wrap_class = 'has-right-sidebar has-one-sidebar row';
	} else if ($sidebar_config == "both-sidebars") {
	$page_wrap_class = 'has-both-sidebars';
	} else {
	$page_wrap_class = 'has-no-sidebar';
	}
?>

<?php if ($show_page_title) { ?>	
	<div class="row">
		<div class="page-heading span12 clearfix alt-bg <?php echo $page_title_bg; ?>">
			<?php if ($page_title_one || $page_title_two) { ?>
			<h1><?php echo $page_title_one; ?></h1>
			<h3><?php echo $page_title_two; ?></h3>
			<?php } else { ?>
			<h1><?php the_title(); ?></h1>
			<?php } ?>
		</div>
	</div>
<?php } ?>

<?php if(function_exists('bcn_display')) { ?>	
<div class="breadcrumbs-wrap row">
	<div id="breadcrumbs" class="span12 alt-bg">
		<?php bcn_display(); ?>
	</div>
</div>
<?php } ?>


<?php if (have_posts()) : the_post(); ?>
	
	<?php		
		$post_author = get_the_author_link();
		$post_date = get_the_date();
		$post_categories = get_the_category_list(', ');
		
		$media_type = $media_image = $media_video = $media_gallery = '';
				 
		$use_thumb_content = get_post_meta($post->ID, 'sf_thumbnail_content_main_detail', true);
		
		if ($use_thumb_content) {
		$media_type = get_post_meta($post->ID, 'sf_thumbnail_type', true);
		$media_image = rwmb_meta('sf_thumbnail_image', 'type=image&size=full');
		$media_video = get_post_meta($post->ID, 'sf_thumbnail_video_url', true);
		$media_gallery = rwmb_meta('sf_thumbnail_gallery', 'type=image&size=full-width-image-gallery');
		} else {
		$media_type = get_post_meta($post->ID, 'sf_detail_type', true);
		$media_image = rwmb_meta('sf_detail_image', 'type=image&size=full');
		$media_video = get_post_meta($post->ID, 'sf_detail_video_url', true);
		$media_gallery = rwmb_meta( 'sf_detail_gallery', 'type=image&size=full-width-image-gallery' );
		$media_slider = get_post_meta($post->ID, 'sf_detail_rev_slider_alias', true);
		$custom_media = get_post_meta($post->ID, 'sf_custom_media', true);
		}
		
		foreach ($media_image as $detail_image) {
			$media_image_url = $detail_image['url'];
			break;
		}
										
		if (!$media_image) {
			$media_image = get_post_thumbnail_id();
			$media_image_url = wp_get_attachment_url( $media_image, 'full' );
		}
		
		if (($sidebar_config == "left-sidebar") || ($sidebar_config == "right-sidebar") || ($sidebar_config == "both-sidebars") && !$full_width_display) {
		$media_width = 770;
		$media_height = NULL;
		$video_height = 433;
		} else {
		$media_width = 1170;
		$media_height = NULL;
		$video_height = 658;
		}
		$figure_output = '';
		
		if ($media_type != "none") {
		
		if ($full_width_display) {
			$figure_output .= '<figure class="media-wrap full-width-detail">'."\n";
		} else {
			$figure_output .= '<figure class="media-wrap">'."\n";
		}
							
			if ($media_type == "video") {
						
				$figure_output .= video_embed($media_video, $media_width, $video_height)."\n";
						
			} else if ($media_type == "slider") {
						
				$figure_output .= '<div class="flexslider item-slider">'."\n";
				$figure_output .= '<ul class="slides">'."\n";
									
				foreach ( $media_gallery as $image ) {
					$figure_output .= "<li><img src='{$image['url']}' width='{$image['width']}' height='{$image['height']}' alt='{$image['alt']}' /></li>";					
				}
																		
				$figure_output .= '</ul>'."\n";
				$figure_output .= '</div>'."\n";
					
			} else if ($media_type == "layer-slider") {
						
				$figure_output .= '<div class="layerslider">'."\n";
							
				$figure_output .= do_shortcode('[rev_slider '.$media_slider.']')."\n";
						
				$figure_output .= '</div>'."\n";
						
			} else if ($media_type == "custom") {
												
				$figure_output .= $custom_media."\n";				
						
			} else {
							
				$detail_image = aq_resize( $media_image_url, $media_width, $media_height, true, false);
						
				if ($detail_image) {
					$figure_output .= '<img src="'.$detail_image[0].'" width="'.$detail_image[1].'" height="'.$detail_image[2].'" />'."\n";
				}
						
			}
					
			$figure_output .= '</figure>'."\n";
					
			}
	?>
	
	<?php if ($full_width_display) {
		echo $figure_output;
	} ?>
	
	<div class="inner-page-wrap <?php echo $page_wrap_class; ?> clearfix">
	
		<!-- OPEN article -->
		<?php if ($sidebar_config == "left-sidebar") { ?>
		<article <?php post_class('clearfix span8'); ?> id="<?php the_ID(); ?>">
		<?php } elseif ($sidebar_config == "right-sidebar") { ?>
		<article <?php post_class('clearfix span8'); ?> id="<?php the_ID(); ?>">
		<?php } else { ?>
		<article <?php post_class('clearfix row'); ?> id="<?php the_ID(); ?>">
		<?php } ?>
		
		<?php if ($sidebar_config == "both-sidebars") { ?>
			<div class="page-content span6 clearfix">
		<?php } else if ($sidebar_config == "no-sidebars") { ?>
			<div class="page-content span12 clearfix">
		<?php } else { ?>
			<div class="page-content clearfix">
		<?php } ?>
				
				<?php if (!$full_width_display) {
					echo $figure_output;
				} ?>
															
				<section class="article-body-wrap">
					<div class="body-text clearfix">
						<?php the_content(); ?>
					</div>
					
					<?php if ($show_author_info) { ?>
					
					<div class="author-info-wrap clearfix">
						<div class="author-avatar"><?php if(function_exists('get_avatar')) { echo get_avatar(get_the_author_meta('ID'), '164'); } ?></div>
						<div class="post-info">
							<div class="author-name"><span><?php _e("Posted by", "swiftframework"); ?></span><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author_meta('display_name'); ?></a></div>
							<div class="post-date"><?php echo $post_date; ?></div>
						</div>
					</div>
					
					<?php } ?>
						
					<?php if (has_tag()) { ?>				
					<div class="tags-link-wrap clearfix">
						<div class="tags-wrap"><?php _e("Tags:", "swiftframework"); ?><span class="tags"><?php the_tags(''); ?></span></div>
						<div class="comments-likes">
						<?php if (function_exists( 'lip_love_it_link' )) {
							echo lip_love_it_link(get_the_ID(), '<i class="icon-heart"></i>', '<i class="icon-heart"></i>', false);
						} ?>				
						<?php if ( comments_open() ) { ?>
							<div class="comments-wrapper"><i class="icon-comments"></i><span><?php comments_number('0', '1', '%'); ?></span></div>
						<?php } ?>
						</div>
					</div>
					<?php } ?>
					
					<?php if ($show_social) { ?>
					
					<div class="share-links clearfix">
						<div class="share-text"><?php _e("Share:", "swiftframework"); ?></div>
						<div class="share-buttons">
							<span class='st_facebook_hcount' displayText='Facebook'></span>
							<span class='st_twitter_hcount' displayText='Tweet'></span>
							<span class='st_googleplus_hcount' displayText='Google +'></span>
							<span class='st_linkedin_hcount' displayText='LinkedIn'></span>
							<span class='st_pinterest_hcount' displayText='Pinterest'></span>
						</div>
						<a class="permalink item-link" href="<?php the_permalink(); ?>"><i class="icon-link"></i></a>
						<a class="email-link item-link" href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>" title="Share by Email"><i class="icon-envelope-alt"></i></a>						
					</div>
					
					<?php } ?>
					
				</section>
				
				<?php if ($show_related) { ?>
				
				<div class="related-wrap">
				<?php
					$categories = get_the_category($post->ID);
					if ($categories) {
						$category_ids = array();
						foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
	
						$args=array(
							'category__in' => $category_ids,
							'post__not_in' => array($post->ID),
							'showposts'=> 4, // Number of related posts that will be shown.
							'orderby' => 'rand'
						);
					}
					$related_posts_query = new wp_query($args);
					if( $related_posts_query->have_posts() ) {
						_e("<h4>Related Articles</h4>", "swiftframework");
						echo '<ul class="related-items row clearfix">';
						while ($related_posts_query->have_posts()) {
							$related_posts_query->the_post();
							$thumb_image = "";
							$thumb_image = get_post_meta($post->ID, 'sf_thumbnail_image', true);
							if (!$thumb_image) {
								$thumb_image = get_post_thumbnail_id();
							}
							$thumb_img_url = wp_get_attachment_url( $thumb_image, 'full' );
							$image = aq_resize( $thumb_img_url, 220, 152, true, false);
							?>
							<?php if ($sidebar_config == "both-sidebars" || $sidebar_config == "no-sidebars") { ?>
							<li class="related-item span3 clearfix">
							<?php } else { ?>
							<li class="related-item span2 clearfix">
							<?php } ?>
								<figure>
									<a href="<?php the_permalink(); ?>">
										<div class="overlay"><div class="thumb-info">
											<i class="icon-file-alt"></i>
										</div></div>
										<img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" />
									</a>
								</figure>
								<h5><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h5>
							</li>
						<?php }
						echo '</ul>';
					}
												
					wp_reset_query();
				?>
				</div>
				
				<?php } ?>
				
				<div class="pagination-wrap blog-pagination clearfix">
					<div class="nav-previous"><?php next_post_link('%link', __('<i class="icon-angle-left"></i> <span class="nav-text">%title</span>', 'swiftframework'), FALSE); ?></div>
					<div class="nav-next"><?php previous_post_link('%link', __('<span class="nav-text">%title</span><i class="icon-angle-right"></i>', 'swiftframework'), FALSE); ?></div>
				</div>
				
				<?php if ( comments_open() ) { ?>
				<div id="comment-area">
					<?php comments_template('', true); ?>
				</div>
				<?php } ?>
			
			</div>
			
			<?php if ($sidebar_config == "both-sidebars") { ?>
			<aside class="sidebar left-sidebar span3">
				<?php dynamic_sidebar($left_sidebar); ?>
			</aside>
			<?php } ?>
		
		<!-- CLOSE article -->
		</article>
	
		<?php if ($sidebar_config == "left-sidebar") { ?>
				
			<aside class="sidebar left-sidebar span4">
				<?php dynamic_sidebar($left_sidebar); ?>
			</aside>
	
		<?php } else if ($sidebar_config == "right-sidebar") { ?>
			
			<aside class="sidebar right-sidebar span4">
				<?php dynamic_sidebar($right_sidebar); ?>
			</aside>
			
		<?php } else if ($sidebar_config == "both-sidebars") { ?>
	
			<aside class="sidebar right-sidebar span3">
				<?php dynamic_sidebar($right_sidebar); ?>
			</aside>
		
		<?php } ?>
				
	</div>

<?php endif; ?>

<!--// WordPress Hook //-->
<?php get_footer(); ?>