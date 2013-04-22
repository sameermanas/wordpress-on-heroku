<?php
	
	$options = get_option('sf_flexform_options');
	$blog_type = $options['archive_display_type'];
	
	global $sidebars;
	
	$post_format = get_post_format();
	$post_title = get_the_title();
	$post_author = get_the_author_link();
	$post_date = get_the_date();
	$post_categories = get_the_category_list(', ');
	$post_comments = get_comments_number();
	$post_permalink = get_permalink();
	$custom_excerpt = get_post_meta($post->ID, 'sf_custom_excerpt', true);
	$post_excerpt = '';
	if ($custom_excerpt != '') {
	$post_excerpt = $custom_excerpt;
	} else {
	$post_excerpt = get_the_excerpt();
	}
	$post_content = get_the_content();
		
	$items = $thumb_image = $thumb_width = $thumb_height = $bordered_thumb_width = $bordered_thumb_height = $video = $video_height = $bordered_video_height = $item_class = $link_config = $item_icon = '';
		
	if ($blog_type == "mini") {
		if ($sidebars == "no-sidebars") {
		$thumb_width = 446;
		$thumb_height = NULL;
		$video_height = 335;
		} else {
		$thumb_width = 290;
		$thumb_height = NULL;
		$video_height = 218;
		}
	} else if ($blog_type == "masonry") {
		if ($sidebars == "both-sidebars") {
		$item_class = "span3";
		} else {
		$item_class = "span4";
		}
		$thumb_width = 480;
		$thumb_height = NULL;
		$video_height = 360;
	} else {
		if ($sidebars == "both-sidebars") {
		$standard_post_width = "span5";
		} else if ($sidebars == "right-sidebar" || $sidebars == "left-sidebar") {
		$standard_post_width = "span6";
		} else {
		$standard_post_width = "span10";
		}
		$thumb_width = 970;
		$thumb_height = NULL;
		$video_height = 728;
	}
	
	
	$thumb_type = get_post_meta($post->ID, 'sf_thumbnail_type', true);
	$thumb_image = get_post_meta($post->ID, 'sf_thumbnail_image', true);
	$thumb_video = get_post_meta($post->ID, 'sf_thumbnail_video_url', true);
	$thumb_gallery = rwmb_meta( 'sf_thumbnail_gallery', 'type=image&size=blog-image' );
	$thumb_link_type = get_post_meta($post->ID, 'sf_thumbnail_link_type', true);
	$thumb_link_url = get_post_meta($post->ID, 'sf_thumbnail_link_url', true);
	$thumb_lightbox_thumb = rwmb_meta( 'sf_thumbnail_image', 'type=image&size=large' );
	$thumb_lightbox_image = rwmb_meta( 'sf_thumbnail_link_image', 'type=image&size=large' );
	$thumb_lightbox_video_url = get_post_meta($post->ID, 'sf_thumbnail_link_video_url', true);
	
	if (!$thumb_image) {
		$thumb_image = get_post_thumbnail_id();
	}
	
	$thumb_img_url = wp_get_attachment_url( $thumb_image, 'full' );
	$thumb_lightbox_img_url = wp_get_attachment_url( $thumb_lightbox_image, 'full' );
	
	$item_figure = $link_config = "";
	
	// LINK TYPE VARIABLES			
	if ($thumb_link_type == "link_to_url") {
		$link_config = 'href="'.$thumb_link_url.'" class="link-to-url"';
		$item_icon = "link";
	} else if ($thumb_link_type == "link_to_url_nw") {
		$link_config = 'href="'.$thumb_link_url.'" class="link-to-url" target="_blank"';
		$item_icon = "link";
	} else if ($thumb_link_type == "lightbox_thumb") {
		$link_config = 'href="'.$thumb_img_url.'" class="view"';
		$item_icon = "search";
	} else if ($thumb_link_type == "lightbox_image") {
		$lightbox_image_url = '';
		foreach ($thumb_lightbox_image as $image) {
			$lightbox_image_url = $image['full_url'];
		}
		$link_config = 'href="'.$lightbox_image_url.'" class="view"';
		$item_icon = "search";	
	} else if ($thumb_link_type == "lightbox_video") {
		$link_config = 'href="'.$thumb_lightbox_video_url.'" rel="prettyphoto"';
		$item_icon = "facetime-video";
	} else {
		$link_config = 'href="'.$post_permalink.'" class="link-to-post"';
		$item_icon = "file-alt";
	}	
	
	// THUMBNAIL MEDIA TYPE SETUP
	
	if ($thumb_type == "none") {
	
	$item_figure .= '<div class="spacer"></div>';
	
	} else {
	
	$item_figure .= '<figure>';
					
	if ($thumb_type == "video") {
		
		$video = video_embed($thumb_video, $thumb_width, $video_height);
		
		$item_figure .= $video;
		
	} else if ($thumb_type == "slider") {
		
		$item_figure .= '<div class="flexslider thumb-slider"><ul class="slides">';
					
		foreach ( $thumb_gallery as $image )
		{
		    $item_figure .= "<li><img src='{$image['url']}' width='{$image['width']}' height='{$image['height']}' alt='{$image['alt']}' /></li>";
		}
														
		$item_figure .= '</ul><div class="open-item"><a '.$link_config.'></a></div></div>';
		
	} else if ($thumb_type == "image") {
	
		$image = aq_resize( $thumb_img_url, $thumb_width, $thumb_height, true, false);
		
		if ($image) {
			
			$item_figure .= '<a '.$link_config.'>';
			
			if ($blog_type != "standard") {
			$item_figure .= '<div class="overlay"><div class="thumb-info">';
			$item_figure .= '<i class="icon-'.$item_icon.'"></i>';
			$item_figure .= '</div></div>';
			}
						
			$item_figure .= '<img src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'" />';
						
			$item_figure .= '</a>';
		}
	}
	
	$item_figure .= '</figure>';
	
	}
	
?>

<?php 
	
	// MASONRY STYLING				
	if ($blog_type == "masonry") {
		
		if ($post_format == "quote") {
			$items .= '<div class="quote-display"><i class="icon-quote-left"></i></div>';
		} else {
			$items .= $item_figure;
		}
		
		$items .= '<div class="details-wrap">';
		$items .= '<h4><a href="'.$post_permalink.'">'.$post_title.'</a></h4>'; 	
	
		// POST EXCERPT
		if ($post_excerpt != "0") {
			$items .= '<div class="excerpt">'. $post_excerpt .'</div>';
		}
	
		$items .= '</div>';
	
		// POST DETAILS 
		$items .= '<div class="post-item-details clearfix">';
		$items .= '<span class="post-date">'.$post_date.'</span>';
		$items .= '<div class="comments-likes">';
		if ( comments_open() ) {
		$items .= '<a href="'.$post_permalink.'#comment-area"><i class="icon-comments"></i><span>'. $post_comments .'</span></a> ';
		}
		if (function_exists( 'lip_love_it_link' )) {
		$items .= lip_love_it_link(get_the_ID(), '<i class="icon-heart"></i>', '<i class="icon-heart"></i>', false);
		}
		$items .= '</div>';				
		$items .= '</div>';

	// MINI STYLING
	} else if ($blog_type == "mini") {
	
		if ($post_format == "quote") {
			$items .= '<div class="quote-display"><i class="icon-quote-left"></i></div>';
		} else {
			$items .= $item_figure;
		}
	
		$items .= '<div class="blog-details-wrap">';					
		$items .= '<h3><a href="'.$post_permalink.'">'. $post_title .'</a></h3>';
	
		$items .= '<div class="blog-item-details">'. sprintf(__('By <a href="%2$s">%1$s</a> on %3$s', 'swiftframework'), $post_author, get_author_posts_url(get_the_author_meta( 'ID' )), $post_date) .'</div>';
		
		$items .= '<div class="comments-likes">';
		if ( comments_open() ) {
			$items .= '<a href="'.$post_permalink.'#comment-area"><i class="icon-comments"></i><span>'. $post_comments .'</span></a> ';
		}
		if (function_exists( 'lip_love_it_link' )) {
			$items .= lip_love_it_link(get_the_ID(), '<i class="icon-heart"></i>', '<i class="icon-heart"></i>', false);
		}
		$items .= '</div>';
		
		if ($post_format == "quote") {
			$items .= '<div class="quote-excerpt heading-font">'. get_the_content() .'</div>';
		} else {
			$items .= '<div class="excerpt">'. $post_excerpt .'</div>';
		}
			
		$items .= '<div class="read-more-bar"><a class="read-more" href="'.$post_permalink.'">'.__("Read more", "swiftframework").'<i class="icon-angle-right"></i></a>';
		
		$items .= '</div>';
	
	// STANDARD STYLING
	} else {
		
		$items .= '<div class="row">'; // open row
		
		if ($sidebars == "no-sidebars") {
		$items .= '<div class="standard-post-author span1">';
		if(function_exists('get_avatar')) {
		$items .= '<div class="author-avatar">'. get_avatar(get_the_author_meta('ID'), '164') .'</div>';
		}
		$items .= '<span class="standard-post-author-name">'.__("Posted by", "swiftframework").' '.$post_author.'</span>';
		$items .= '</div>';
		} else if ($sidebars == "right-sidebar" || $sidebars == "left-sidebar") {
		$items .= '<div class="standard-post-author span1">';
		if(function_exists('get_avatar')) {
		$items .= '<div class="author-avatar">'. get_avatar(get_the_author_meta('ID'), '164') .'</div>';
		}
		$items .= '<span class="standard-post-author-name">'.__("Posted by", "swiftframework").' '.$post_author.'</span>';
		$items .= '</div>';
		}
		
		$items .= '<div class="standard-post-content '.$standard_post_width.'">'; // open standard-post-content
	
		if ($post_format == "quote") {
			$items .= '<div class="quote-display"><i class="icon-quote-left"></i></div>';
		} else {
			$items .= $item_figure;
			$items .= '<h2><a href="'.$post_permalink.'">'. $post_title .'</a></h2>';
		}
							
		if ($post_format == "quote") {
			$items .= '<div class="quote-excerpt heading-font">'. get_the_content() .'</div>';
		} else {
			$items .= '<div class="excerpt">'. $post_excerpt .'</div>';
		}
	
		$items .= '</div>'; // close standard-post-content
		
		$items .= '<div class="standard-post-details span1">'; // open standard-post-details
		if ($sidebars == "both-sidebars") {
		$items .= '<div class="standard-post-author">';
		if(function_exists('get_avatar')) {
		$items .= '<div class="author-avatar">'. get_avatar(get_the_author_meta('ID'), '164') .'</div>';
		}
		$items .= '<span class="standard-post-author-name">'.__("Posted by", "swiftframework").' '.$post_author.'</span>';
		$items .= '</div>';
		}
		$items .= '<span class="standard-post-date">'.$post_date.'</span>';
		$items .= '<div class="comments-likes">';
		
		if ( comments_open() ) {
			$items .= '<div class="comments-wrapper"><a href="'.$post_permalink.'#comment-area"><i class="icon-comments"></i><span>'. $post_comments .'</span></a></div>';
		}
		
		if (function_exists( 'lip_love_it_link' )) {
			$items .= lip_love_it_link(get_the_ID(), '<i class="icon-heart"></i>', '<i class="icon-heart"></i>', false);
		}
		
		$items .= '</div>';
		$items .= '</div>'; // close standard-post-details
		
		$items .= '</div>'; // close row
		
	}
	
	echo $items;
	
?>