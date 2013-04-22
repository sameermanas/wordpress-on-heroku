<?php

class WPBakeryShortCode_blog extends WPBakeryShortCode {

    protected function content($atts, $content = null) {
			
			$options = get_option('sf_sam5_options');
			$rss_feed_url = $options['rss_feed_url'];
			
		    $title = $width = $el_class = $output = $show_blog_aux = $exclude_categories = $blog_aux = $show_read_more = $items = $item_figure = $el_position = '';
			
	        extract(shortcode_atts(array(
	        	'title' => '',
	        	'show_blog_aux' => 'yes',
	        	"pagination" 	=> "no",
	        	"blog_type"		=> "standard",
	        	'show_title'	=> 'yes',
	        	'show_excerpt'	=> 'yes',
	        	"show_details"	    => 'yes',
	        	"excerpt_length" => '20',
	        	'show_read_more' => 'no',
	        	"item_count"	=> '5',
	        	"category"		=> '',
	        	"exclude_categories" => '',
	        	'el_position' => '',
	        	'width' => '1/1',
	        	'el_class' => ''
	        ), $atts));
	        
	        $width = wpb_translateColumnWidthToSpan($width);
	        
	        $sidebar_config = get_post_meta(get_the_ID(), 'sf_sidebar_config', true);
	        
	        $sidebars = '';
	        if (($sidebar_config == "left-sidebar") || ($sidebar_config == "right-sidebar")) {
	        $sidebars = 'one-sidebar';
	        } else if ($sidebar_config == "both-sidebars") {
	        $sidebars = 'both-sidebars';
	        } else {
	        $sidebars = 'no-sidebars';
	        }
	        
	        $options = get_option('sf_sam5_options');
	        $filter_wrap_bg = $options['filter_wrap_bg'];
	        
	        
	        /* TOP AUX BUTTONS OUTPUT
	        ================================================== */ 
	        
	        if ($show_blog_aux == "yes" && $sidebars == "no-sidebars") {
	        
	        $category_list = wp_list_categories('sort_column=name&title_li=&depth=1&number=10&echo=0&show_count=1');
			$archive_list =  wp_get_archives('type=monthly&limit=12&echo=0');
	        $tags_list = wp_tag_cloud('smallest=12&largest=12&unit=px&format=list&number=50&orderby=name&echo=0');
	        
	        $blog_aux .= '<div class="blog-aux-wrap row">'; // open .blog-aux-wrap
	        $blog_aux .= '<ul class="blog-aux-options '.$width.'">'; // open .blog-aux-options
	        
	        // CATEGORIES
	        $blog_aux .= '<li><a href="#" class="blog-slideout-trigger" data-aux="categories"><i class="icon-list"></i>'.__("Categories", "swiftframework").'</a>';
	        
	        // TAGS
	        $blog_aux .= '<li><a href="#" class="blog-slideout-trigger" data-aux="tags"><i class="icon-tags"></i>'.__("Tags", "swiftframework").'</a>';
	        
	        // SEARCH FORM
	        $blog_aux .= '<li><form method="get" class="search-form" action="'. home_url().'/">';
	        $blog_aux .= '<input type="text" placeholder="'. __("Search", "swiftframework") .'" name="s" />';
	        $blog_aux .= '</form></li>';
	        
	        // ARCHIVES
	        $blog_aux .= '<li><a href="#" class="blog-slideout-trigger" data-aux="archives"><i class="icon-list"></i>'.__("Archives", "swiftframework").'</a>';
	        
	        // RSS LINK
	        if ($rss_feed_url != "") {
	        $blog_aux .= '<li><a href="'.$rss_feed_url.'" class="rss-link" target="_blank"><i class="icon-rss"></i>'.__("RSS", "swiftframework").'</a>';
	        }
	        
	        $blog_aux .= '</ul>'; // close .blog-aux-options
	        $blog_aux .= '</div>'; // close .blog-aux-wrap
	        
			$blog_aux .= '<div class="filter-wrap blog-filter-wrap row clearfix">'; // open .blog-filter-wrap
			$blog_aux .= '<div class="filter-slide-wrap span12 alt-bg '.$filter_wrap_bg.'">';
			if ($category_list != '') {  
			    $blog_aux .= '<ul class="aux-list aux-categories row clearfix">'.$category_list.'</ul>';  
			}
			if ($tags_list != '') {  
			    $blog_aux .= '<ul class="aux-list aux-tags row clearfix">'.$tags_list.'</ul>';  
			}	
			if ($archive_list != '') {  
			    $blog_aux .= '<ul class="aux-list aux-archives row clearfix">'.$archive_list.'</ul>';  
			}
			$blog_aux .='</div></div>'; // close .blog-filter-wrap
	        }
	        
	        
	        /* BLOG QUERY SETUP
	        ================================================== */ 
	        
	        // CATEGORY SLUG MODIFICATION
	        if ($category == "All") {$category = "all";}
	        if ($category == "all") {$category = '';}
	        $category_slug = str_replace('_', '-', $category);
		    
    		// BLOG QUERY SETUP
    		global $post, $wp_query;
    		
    		if ( get_query_var('paged') ) {
    		$paged = get_query_var('paged');
    		} elseif ( get_query_var('page') ) {
    		$paged = get_query_var('page');
    		} else {
    		$paged = 1;
    		}
    		    		
    		$blog_args = array(
    			'post_type' => 'post',
    			'post_status' => 'publish',
    			'paged' => $paged,
    			'category_name' => $category_slug,
    			'posts_per_page' => $item_count,
    			'cat' => '"'.$exclude_categories.'"'
    			);
    			    		
    		$blog_items = new WP_Query( $blog_args );
    			
    		$list_class = '';
    		
    		if ($blog_type == "masonry") {
    		$list_class .= 'masonry-items';
    		} else if ($blog_type == "mini") {
    		$list_class .= 'mini-items';
    		} else {
    		$list_class .= 'standard-items';
    		}    		
    		
    		/* BLOG ITEMS OUTPUT
    		================================================== */ 
    		
    		$items .= '<ul class="blog-items row '. $list_class .' clearfix">';
    			
			while ( $blog_items->have_posts() ) : $blog_items->the_post();
				    				
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
				$post_excerpt = custom_excerpt($custom_excerpt, $excerpt_length);
				} else {
				$post_excerpt = excerpt($excerpt_length);
				}
				
				$standard_post_width = $thumb_image = $thumb_width = $thumb_height = $bordered_thumb_width = $bordered_thumb_height = $video = $video_height = $bordered_video_height = $item_class = $link_config = $item_icon = '';
				
				if ($blog_type == "mini") {
					$item_class = $width;
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
					$thumb_width = 270;
					$thumb_height = NULL;
					$video_height = 165;
					} else {
					$item_class = "span4";
					$thumb_width = 370;
					$thumb_height = NULL;
					$video_height = 220;
					}
				} else {
					if ($sidebars == "both-sidebars") {
					$standard_post_width = "span5";
					} else if ($sidebars == "one-sidebar") {
					$standard_post_width = "span6";
					} else {
					$standard_post_width = "span10";
					}
					$item_class = $width;
					$thumb_width = 970;
					$thumb_height = NULL;
					$video_height = 545;
				}
				
				/* MEDIA VARIABLES
				================================================== */ 
				
				$thumb_type = get_post_meta($post->ID, 'sf_thumbnail_type', true);
				$thumb_image = rwmb_meta('sf_thumbnail_image', 'type=image&size=full');
				$thumb_video = get_post_meta($post->ID, 'sf_thumbnail_video_url', true);
				$thumb_gallery = rwmb_meta( 'sf_thumbnail_gallery', 'type=image&size=thumb-image-onecol' );
				$thumb_link_type = get_post_meta($post->ID, 'sf_thumbnail_link_type', true);
				$thumb_link_url = get_post_meta($post->ID, 'sf_thumbnail_link_url', true);
				$thumb_lightbox_thumb = rwmb_meta( 'sf_thumbnail_image', 'type=image&size=large' );
				$thumb_lightbox_image = rwmb_meta( 'sf_thumbnail_link_image', 'type=image&size=large' );
				$thumb_lightbox_video_url = get_post_meta($post->ID, 'sf_thumbnail_link_video_url', true);
				
				foreach ($thumb_image as $detail_image) {
					$thumb_img_url = $detail_image['url'];
					break;
				}
												
				if (!$thumb_image) {
					$thumb_image = get_post_thumbnail_id();
					$thumb_img_url = wp_get_attachment_url( $thumb_image, 'full' );
				}
					
				$thumb_lightbox_img_url = wp_get_attachment_url( $thumb_lightbox_image, 'full' );
				
				
				/* LINK TYPE VARIABLES
				================================================== */ 
				
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
					$link_config = 'href="'.$thumb_lightbox_video_url.'" rel="prettyPhoto"';
					$item_icon = "facetime-video";
				} else {
					$link_config = 'href="'.$post_permalink.'" class="link-to-post"';
					$item_icon = "file-alt";
				}
				
				
				/* MEDIA TYPE CONFIG
				================================================== */ 
				
				if ($thumb_type != "none") {
				
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
																	
					$item_figure .= '</ul><div class="open-item"><a '.$link_config.'><i class="icon-plus"></i></a></div></div>';
					
				} else {
				
					$image = aq_resize( $thumb_img_url, $thumb_width, $thumb_height, true, false);
					
					if ($image) {
						
						$item_figure .= '<a '.$link_config.'>';
						
						if ($blog_type != "standard") {
						$item_figure .= '<div class="overlay"><div class="thumb-info">';
						$item_figure .= '<i class="icon-'.$item_icon.'"></i>';
						$item_figure .= '</div></div>';
						}
												
						$item_figure .= '<img src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'" alt="'.$post_title.'" />';
												
						$item_figure .= '</a>';
					}
				}
				
				$item_figure .= '</figure>';
				
				}
				
				
				/* BLOG ITEM OUTPUT
				================================================== */ 
				
				if ($blog_type == "masonry") {
				$items .= '<li class="blog-item recent-post '.$item_class.'">';
				} else {
				$items .= '<li class="blog-item '.$item_class.'">';				
				}
								
				// MASONRY STYLING				
				if ($blog_type == "masonry") {
					
					if ($post_format == "quote") {
						$items .= '<div class="quote-display"><i class="icon-quote-left"></i></div>';
					} else {
						$items .= $item_figure;
					}
				
					$items .= '<div class="details-wrap">';
					if ($show_title == "yes") {
						$items .= '<h4><a href="'.$post_permalink.'">'.$post_title.'</a></h4>'; 
					}			
					
					// POST EXCERPT
					if ($excerpt_length != "0" && $show_excerpt == "yes") {
						$items .= '<div class="excerpt">'. $post_excerpt .'</div>';
					}
					
					if ($show_read_more == "yes") {
						$items .= '<a href="'.$post_permalink.'" class="read-more">'.__("Read more", "swiftframework").'<i class="icon-angle-right"></i></a>';
					}
					
					$items .= '</div>';
					
					// POST DETAILS 
					if ($show_details == "yes") {
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
					}
				
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
					
					if ($show_read_more == "yes") {
					
					$items .= '<div class="read-more-bar"><a class="read-more" href="'.$post_permalink.'">'.__("Read more", "swiftframework").'<i class="icon-angle-right"></i></a>';
					
					}
					
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
					} else if ($sidebars == "one-sidebar") {
					$items .= '<div class="standard-post-author span1">';
					if(function_exists('get_avatar')) {
					$items .= '<div class="author-avatar">'. get_avatar(get_the_author_meta('ID'), '164') .'</div>';
					}
					$items .= '<span class="standard-post-author-name">'.__("Posted by", "swiftframework").' '.$post_author.'</span>';
					$items .= '</div>';
					}
					
					$items .= '<div class="standard-post-content '.$standard_post_width.'">'; // open standard-post-content
				
					if ($post_format != "quote") {
						$items .= $item_figure;
						$items .= '<h2><a href="'.$post_permalink.'">'. $post_title .'</a></h2>';
					}
										
					if ($post_format == "quote") {
						$items .= '<div class="quote-excerpt heading-font">'. get_the_content() .'</div>';
					} else {
						$items .= '<div class="excerpt">'. $post_excerpt .'</div>';
					}
					
					if ($show_read_more == "yes") {
						$items .= '<a href="'.$post_permalink.'" class="read-more">'.__("Read more", "swiftframework").'<i class="icon-angle-right"></i></a>';
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
				
				
				$items .= '</li>';
				
				$item_figure = '';
				
			endwhile;
			
			wp_reset_postdata();
			$items .= '</ul>';
			
			
			/* PAGINATION OUTPUT
			================================================== */ 
			
			if ($pagination == "yes") {
			
				if ($blog_type == "masonry") {
				$items .= '<div class="pagination-wrap masonry-pagination">';
				} else {
				$items .= '<div class="pagination-wrap">';
				}
							
				$items .= pagenavi($blog_items);
													
				$items .= '</div>';
				
			}
			
			
			/* FINAL OUTPUT
			================================================== */ 
 			
    		$el_class = $this->getExtraClass($el_class);
            
            $output .= "\n\t".'<div class="wpb_blog_widget wpb_content_element '.$width.$el_class.'">';
            $output .= "\n\t\t".'<div class="wpb_wrapper blog-wrap">';            
            $output .= ($title != '' ) ? "\n\t\t\t".'<div class="heading-wrap"><h3 class="wpb_heading"><span>'.$title.'</span></h3></div>' : '';
            if ($show_blog_aux == "yes") {
            $output .= "\n\t\t\t\t".$blog_aux;
            }
            $output .= "\n\t\t\t\t".$items;
            $output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
            $output .= "\n\t".'</div> '.$this->endBlockComment($width);
    
            $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
            
            if ($blog_type == "masonry") {
            global $include_isotope;
            $include_isotope = true;
            }
            
            global $has_blog;
            $has_blog = true;
            
            return $output;
		
    }
}

WPBMap::map( 'blog', array(
    "name"		=> __("Blog", "js_composer"),
    "base"		=> "blog",
    "class"		=> "wpb_blog",
    "icon"      => "icon-wpb-blog",
    "params"	=> array(
    	array(
    	    "type" => "textfield",
    	    "heading" => __("Widget title", "js_composer"),
    	    "param_name" => "title",
    	    "value" => "",
    	    "description" => __("Heading text. Leave it empty if not needed.", "js_composer")
    	),
    	array(
    	    "type" => "dropdown",
    	    "heading" => __("Show blog aux options", "js_composer"),
    	    "param_name" => "show_blog_aux",
    	    "value" => array(__("Yes", "js_composer") => "yes", __("No", "js_composer") => "no"),
    	    "description" => __("Show the blog aux options - categories/tags/search/archives/rss. NOTE: This is only available on a page with the no sidebar setup.", "js_composer")
    	),
    	array(
    	    "type" => "dropdown",
    	    "heading" => __("Blog type", "js_composer"),
    	    "param_name" => "blog_type",
    	    "value" => array(__('Standard', "js_composer") => "standard", __('Mini', "js_composer") => "mini", __('Masonry', "js_composer") => "masonry"),
    	    "description" => __("Select the display type for the blog.", "js_composer")
    	),
        array(
            "type" => "textfield",
            "class" => "",
            "heading" => __("Number of items", "js_composer"),
            "param_name" => "item_count",
            "value" => "5",
            "description" => __("The number of blog items to show per page.", "js_composer")
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Blog category", "js_composer"),
            "param_name" => "category",
            "value" => get_category_list('category'),
            "description" => __("Choose the category for the blog items.", "js_composer")
        ),
        array(
            "type" => "textfield",
            "heading" => __("Exclude categories", "js_composer"),
            "param_name" => "exclude_categories",
            "value" => "",
            "description" => __('If you would like to exclude categories from the blog list, then enter the category IDs here with a "-" infront of them, seperated by a comma (no spaces or quotes). E.g. "-1,-7,-23".', "js_composer")
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Show title text", "js_composer"),
            "param_name" => "show_title",
            "value" => array(__("Yes", "js_composer") => "yes", __("No", "js_composer") => "no"),
            "description" => __("Show the item title text.", "js_composer")
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Show item excerpt", "js_composer"),
            "param_name" => "show_excerpt",
            "value" => array(__("Yes", "js_composer") => "yes", __("No", "js_composer") => "no"),
            "description" => __("Show the item excerpt text.", "js_composer")
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Show item details", "js_composer"),
            "param_name" => "show_details",
            "value" => array(__("Yes", "js_composer") => "yes", __("No", "js_composer") => "no"),
            "description" => __("Show the item details.", "js_composer")
        ),
        array(
            "type" => "textfield",
            "heading" => __("Excerpt Length", "js_composer"),
            "param_name" => "excerpt_length",
            "value" => "20",
            "description" => __("The length of the excerpt for the posts.", "js_composer")
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Show read more link", "js_composer"),
            "param_name" => "show_read_more",
            "value" => array(__("No", "js_composer") => "no", __("Yes", "js_composer") => "yes"),
            "description" => __("Show a read more link below the excerpt.", "js_composer")
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Pagination", "js_composer"),
            "param_name" => "pagination",
            "value" => array(__("Yes", "js_composer") => "yes", __("No", "js_composer") => "no"),
            "description" => __("Show pagination.", "js_composer")
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    )
) );

?>