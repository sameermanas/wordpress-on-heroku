<?php
/*
Plugin Name: SEO Auto Links & Related Posts
Plugin URI: http://exclusivewp.com/seo-auto-links-related-posts/
Author: Purwedi Kurniawan
Author URI: http://exclusivewp.com
Version: 1.2.12.12
Description:  SEO Auto Links & Related Posts main features are auto create internal link, add related posts below content, and show slide out related posts in the bottom right corner when visitor scroll down and reach the end of article. <a href="admin.php?page=alrp">Configure...</a>
*/

error_reporting(0);
@ini_set( 'display_errors', 0 );

/**
* 23.09 bug fix slidebox not working with theme using loop file, slidebox with transparent background in firefox, add enable tooltip option
**/

define ('ALRP_URL', plugins_url( '',__FILE__ ));

include_once dirname( __FILE__ ) .'/php/admin.php';
include_once dirname( __FILE__ ) .'/php/db.php';

/**
 * activate seo-alrp plugin, prepare full index, cache folder, init settings
 **/
register_activation_hook( __file__,'pkalrp_admin_activation' );
function pkalrp_admin_activation() {
	pkalrp_db_add_fulltext_index();
	$cache_path = dirname( __FILE__ ) ."/php/cache";
	if (!is_dir($cache_path)) mkdir( $cache_path, 0775 );
	pkalrp_admin_init_settings( true );
}


/**
 * deactivate seo-alrp plugin
 **/
register_deactivation_hook( __file__,'pkalrp_admin_deactivation' );
function pkalrp_admin_deactivation(){
	pkalrp_db_del_transients();	
	pkalrp_db_drop_fulltext_index();
}

/**
 * get related posts
 **/
function pkalrp_get_related_posts( $err_search_term = '', $limit404 = 8 ) {
	global $wpdb, $post;
	$options = get_option( 'alrp_rp_options' );
	$gen_options = get_option( 'alrp_gs_options' );
	
	$cache_key = 'alrp_rp_final_'.$post->ID;
	$rp_final = pkalrp_get_cached_results( $cache_key );

	if ( !empty( $rp_final ) ) {
		echo '<!-- ALRP: Related posts served from the cache -->';
		$output = $rp_final;
	} else {
		echo '<!-- ALRP: Related posts NOT served from the cache -->';
		if ( 'cattag' == $options['match'] ) {
			$searches = pkalrp_get_related_posts_cattag( $post, $options['limit'] );
		} elseif ( !empty( $err_search_term ) ) {
			$searches = pkalrp_get_related_posts_404 ( $options, $err_search_term, $limit404 );
		} else {
			$searches = pkalrp_get_related_posts_match( $post, $options );
		}

		$output = '';
		if ( $searches ) {
			$output .= '<div id="alrp-related-posts">';
			$title = trim($options['title']);
			$title = ('<' == substr($title,0,1) || empty($title)) ? $title : '<h3>'.$title.'</h3>';
			$output .= str_replace( '#post_title', $post->post_title, $title );
			$output .= '<div id="alrp-container">';

			switch ( $options['layout'] ) {
			case 'thumbnail-title':
			case 'thumbnail-title-clean':
				foreach ( $searches as $search ) {
					$thumb_url = pkalrp_get_thumbnail_url( $search, $options );
					$output .= '<div class="alrp-content-caption">';
					$output .= '<a class="alrptip" href="'.get_permalink( $search->ID ).'" >';
					$output .= '<img width="'.$options['imgw'].'" height="'.$options['imgh'].'" alt="'.$search->post_title.'" src="'.$thumb_url.'">';
					$output .= ( $gen_options['enabletip'] ) ? '<span>'.pkalrp_post_excerpt( $search->post_content,$options['excerptlen'] ).'</span>' : '';
					$output .= '</a>';
					$output .= '<p><a class="alrptip" href="'.get_permalink( $search->ID ).'" >'.$search->post_title.'</a></p>';
					$output .= '</div>';
				}
				break;
			case 'title-content-thumbnail':
			case 'title-content-thumbnail-clean':
				foreach ( $searches as $search ) {
					$thumb_url = pkalrp_get_thumbnail_url( $search, $options );
					$output .= '<div class="alrp-thumbnail">';
					$output .= '<a title="'.$search->post_title.'" href="'.get_permalink( $search->ID ).'" class="seo-alrp-hover-link" >';
					$output .= '<img width="'.$options['imgw'].'" height="'.$options['imgh'].'" alt="'.$search->post_title.'" src="'.$thumb_url.'">';
					$output .= '</a> ';
					$output .= '</div>';
					$output .= '<div class="alrp-content">';
					$output .= '<a rel="bookmark" href="'.get_permalink( $search->ID ).'">'.$search->post_title.'</a>';
					$output .= '<p>'.pkalrp_post_excerpt( $search->post_content,$options['excerptlen'] ).'</p>';
					$output .= '</div>';
				}
				break;				
			case 'thumbnail-only':
			case 'thumbnail-only-clean':
				foreach ( $searches as $search ) {
					$thumb_url = pkalrp_get_thumbnail_url( $search, $options );
					$output .= '<div class="alrp-content-caption">';
					$output .= '<a class="alrptip" href="'.get_permalink( $search->ID ).'" >';
					$output .= '<img width="'.$options['imgw'].'" height="'.$options['imgh'].'" alt="'.$search->post_title.'" src="'.$thumb_url.'">';
					$output .= ( $gen_options['enabletip'] ) ? '<span>'.pkalrp_post_excerpt( $search->post_content,$options['excerptlen'] ).'</span>' : '';
					$output .= '</a>';
					$output .= '</div>';
				}
				break;		
			case 'title-only':
				$output .= '<ul>';
				foreach ( $searches as $search ) {
					$output .= '<li><a href="'.get_permalink( $search->ID ).'" class="alrptip">'.$search->post_title;
					$output .= ( $gen_options['enabletip'] ) ? '<span>'.pkalrp_post_excerpt( $search->post_content,$options['excerptlen'] ).'</span>' : '';
					$output .= '</a>';
					$output .= '</li>';
				}
				$output .= '</ul>';
				break;
			case 'title-content':
				$output .= '<ul>';
				foreach ( $searches as $search ) {
					$output .= '<li>';
					$output .= '<h4><a href="'.get_permalink( $search->ID ).'" class="alrptip">'.$search->post_title.'</a></h4>';
					$output .= '<p>'.pkalrp_post_excerpt( $search->post_content,$options['excerptlen'] ).'</p>';
					$output .= '</li>';
				}
				$output .= '</ul>';
				break;						
			}

			$output .= '</div></div><div style="clear:both"></div>';
		} else {
			$output = str_replace( '#post_title', $post->post_title, $options['blankmsg'] );
		}
		pkalrp_set_cached_results( $cache_key, $output );
	}

	return $output;
}


/**
 * echoing related posts
 **/
function seo_alrp() {
	$output = pkalrp_get_related_posts();
	echo $output;
}


/**
 * return post excerpt
 **/
function pkalrp_post_excerpt( $text, $excerpt_length ) {

	if ( 3 < strlen( $text ) && 1 < intval($excerpt_length) ) {
		$text = strip_shortcodes( $text );
		$text = str_replace( ']]>', ']]&gt;', $text );
		$text = strip_tags( $text );
		$words = preg_split( "/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY );
		if ( count( $words ) > $excerpt_length ) {
			array_pop( $words );
			$text = implode( ' ', $words );
			$text = $text . ' ...';
		} else {
			$text = implode( ' ', $words );
		}
	}
	return $text;
}


/**
 * import stopwords from file, and remove all occurences from post content
 **/
function pkalrp_tokenize_stopwords( $content ) {
	include_once dirname( __FILE__ ) .'/php/stopwords.php';
	if (isset($stopwords)){
		$content = preg_replace( "/[^a-zA-Z\s]/", "", strtolower( $content ) );
		$acontent = explode( ' ', $content );
		$tokenized = '';
		foreach ( $acontent as $str ) {
			$tokenized .= ( !in_array( $str, $stopwords ) && strlen( $str ) > 3 ) ? $str . ' ' : '';
		}	
		$content = (!empty($tokenized)) ? $tokenized : $content;
	}
	return $content;
}


/**
 * filter post content
 **/
add_filter( 'the_content', 'pkalrp_filter_content' );
function pkalrp_filter_content( $content ) {
	if( is_single() || is_page() ){
		$content = pkalrp_filter_content_manuallink( $content );
		$content = pkalrp_filter_content_autolink( $content );
		$content = pkalrp_filter_content_relatedposts( $content );
		$content .= '<div id="alrp-slidebox-anchor"></div>';
		$content = str_replace(array('#pstarttag#','#pendtag#'),array('<p>','</p>'), $content);
	}	
	return $content;
}


/**
 * auto add related posts to content or feed
 **/
function pkalrp_filter_content_relatedposts( $content ) {
	$options = get_option( 'alrp_rp_options' );
	if ( $options['enable'] ) {
		if (
			( is_page() && $options['onpage'] && $options['autoadd'] ) ||
			( is_single() && $options['onpost'] && $options['autoadd'] ) ||
			( is_feed() && $options['onfeed'] )
		) {
			$output = pkalrp_get_related_posts();
			$content .= '<div style="clear:both"></div>'.$output;
		}
	}
	return $content;
}


/**
 * automatically create internal link to the related posts
 **/
function pkalrp_filter_content_autolink( $content ) {
	$options = get_option( 'alrp_al_options' );
	$gen_options = get_option( 'alrp_gs_options' );

	if ( ( $options['enable'] )&&( $options['tag']||$options['keyword'] ) ) {

		global $post;
		$cache_key = 'alrp_al_keys_'.$post->ID;
		$all_keys = pkalrp_get_cached_results( $cache_key );
		if ( !empty( $all_keys ) ) {
			echo '<!-- ALRP: Autolinks served from the cache -->';
			$content_tmp = str_replace(array('<p>','</p>'),array('#pstarttag#','#pendtag#'), $content);
			$content_tmp = preg_replace_callback( '/\<pre(.+?)\/pre\>/is', 'pkalrp_base64encode_pre', $content_tmp );
			$content_tmp = preg_replace_callback( '/\<code(.+?)\/code\>/is', 'pkalrp_base64encode_code', $content_tmp );
			foreach ( $all_keys as $key ) {
				$regexp = '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/[a-z][1-9]>|[^>\]]+<\/[a-z]>))\b('.preg_quote($key['keyword'],'/').')\b/imsU';
				if ( $gen_options['enabletip'] ){
					$replacement = "<a class=\"alrptip\" href=\"".$key['permalink']."\">\$0#alrp#".base64_encode( $key['title'] )."#/alrp#</a>";
				} else {
					$replacement = "<a class=\"alrptip\" href=\"".$key['permalink']."\">\$0</a>";
				}
				$content_tmp = preg_replace( $regexp, $replacement, $content_tmp, $options['rglimit'] );
			}
			if ( !empty( $content_tmp ) ) {
				$content_tmp = str_replace(array('#pstarttag#','#pendtag#'),array('<p>','</p>'), $content_tmp);
				if ( $gen_options['enabletip'] ) $content_tmp = preg_replace_callback( '/\#alrp\#(.+?)\#\/alrp\#/is', 'pkalrp_base64decode', $content_tmp );
				$content_tmp = preg_replace_callback( '/\#pre\#(.+?)\#\/pre\#/is', 'pkalrp_base64decode_pre', $content_tmp );
				$content_tmp = preg_replace_callback( '/\#code\#(.+?)\#\/code\#/is', 'pkalrp_base64decode_code', $content_tmp );
				$content = &$content_tmp;
			}
		} else {
			echo '<!-- ALRP: Autolinks NOT served from the cache -->';
			$searches = pkalrp_get_related_posts_autolinks( $post, $options, $content );

			if ( !empty( $searches ) ) {
				/**
				 * collecting all the keywords used by all the related posts ( unique keyword only )
				 **/
				$a_all_keywords_with_args = array(); $a_keywords_with_args = array();
				foreach ( $searches as $search ) {

					$permalink = get_permalink( $search->ID );
					$link_title = str_replace( '#title', $search->post_title, $options['title'] );

					$meta_keywords = ''; $tags = '';

					if ( $options['keyword'] ) {
						$meta_keywords = get_post_meta( $search->ID, 'keywords', true );
					}

					if ( $options['tag'] ) {
						$terms = wp_get_post_tags( $search->ID, array( 'orderby' => 'name', 'order' => 'ASC', 'fields' => 'names' ) );
						if ( !empty( $terms ) ) $tags = trim( implode( ',', $terms ),', ' );
					}

					$keywords = trim(str_replace(array(', ',' ,',',,'),',',$meta_keywords.','.$tags),', ');
					if ( !empty( $keywords ) )
						$a_keywords_with_args = array_fill_keys(explode( ',', $keywords ), $link_title.'#alrp#'.$permalink);

					if ( is_array($a_keywords_with_args) )	
						$a_all_keywords_with_args = array_merge($a_all_keywords_with_args, $a_keywords_with_args);

				}

				if ( !empty( $a_all_keywords_with_args ) ) {

					/**
					 * foreach keyword, if found in post content, turn it into internal link
					 **/
					$a_final_data= array();
					$content_tmp = str_replace(array('<p>','</p>'),array('#pstarttag#','#pendtag#'), $content);
					$content_tmp = preg_replace_callback( '/\<pre(.+?)\/pre\>/is', 'pkalrp_base64encode_pre', $content_tmp );
					$content_tmp = preg_replace_callback( '/\<code(.+?)\/code\>/is', 'pkalrp_base64encode_code', $content_tmp );

					foreach ( $a_all_keywords_with_args as $key => $val ) {
						$args = explode('#alrp#', $val);

						$regexp = '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/[a-z][1-9]>|[^>\]]+<\/[a-z]>))\b('.preg_quote($key,'/').')\b/imsU';
						if ($gen_options['enabletip']){
							$replacement = '<a class="alrptip" href="'.$args[1].'">$0#alrp#'.base64_encode( $args[0] ).'#/alrp#</a>';
						} else {
							$replacement = '<a class="alrptip" href="'.$args[1].'">$0</a>';
						}
						$content_tmp = preg_replace( $regexp, $replacement, $content_tmp, $options['rglimit'], $count );

						if ( 0 < $count ) $a_final_data[] = array( 'keyword' => $key, 'permalink' => $args[1], 'title' => $args[0] );
					}					
					
					pkalrp_set_cached_results( $cache_key, $a_final_data );

					if ( !empty( $content_tmp ) ) {
						$content_tmp = str_replace(array('#pstarttag#','#pendtag#'),array('<p>','</p>'), $content_tmp);
						if ( $gen_options['enabletip'] ) $content_tmp = preg_replace_callback( '/\#alrp\#(.+?)\#\/alrp\#/is', 'pkalrp_base64decode', $content_tmp );
						$content_tmp = preg_replace_callback( '/\#pre\#(.+?)\#\/pre\#/is', 'pkalrp_base64decode_pre', $content_tmp );
						$content_tmp = preg_replace_callback( '/\#code\#(.+?)\#\/code\#/is', 'pkalrp_base64decode_code', $content_tmp );
						$content = &$content_tmp;
					}
				}
			}
		}
	}
	return $content;
}


/**
 * manual auto link
 **/
function pkalrp_filter_content_manuallink( $content ) {
	$options = get_option( 'alrp_al_options' );
	if ( !empty( $options['manuallinks'] ) ) {
		$autolinks = explode( "\n",$options['manuallinks'] );

		$content_tmp = str_replace(array('<p>','</p>'),array('#pstarttag#','#pendtag#'), $content);
		$content_tmp = preg_replace_callback( '/\<pre(.+?)\/pre\>/is', 'pkalrp_base64encode_pre', $content_tmp );
		$content_tmp = preg_replace_callback( '/\<code(.+?)\/code\>/is', 'pkalrp_base64encode_code', $content_tmp );

		foreach ( $autolinks as $autolink ) {
			$links = explode( ',',trim( $autolink ) );
			$nofollow = ( !empty($links[2]) && 'nofollow'==$links[2] ) ? ' rel="nofollow"' : '';

			$regexp = '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/[a-z][1-9]>|[^>\]]+<\/[a-z]>))\b('.preg_quote($links[0],'/').')\b/imsU';
			$replacement = "<a target=\"_blank\" href=\"".$links[1]."\" $nofollow>\$0</a>";
			$content_tmp = preg_replace( $regexp, $replacement, $content_tmp, $options['rglimit'] );
		}

		if ( !empty( $content_tmp ) ) {
			$content_tmp = str_replace(array('#pstarttag#','#pendtag#'),array('<p>','</p>'), $content_tmp);			
			$content_tmp = preg_replace_callback( '/\#pre\#(.+?)\#\/pre\#/is', 'pkalrp_base64decode_pre', $content_tmp );
			$content_tmp = preg_replace_callback( '/\#code\#(.+?)\#\/code\#/is', 'pkalrp_base64decode_code', $content_tmp );
			$content = &$content_tmp;
		}

	}
	return $content;
}

/**
* slidebox related posts
**/
function pkalrp_slidebox(){
	$options = get_option( 'alrp_sb_options' );
    global $post;
	wp_reset_query();

	if ( ( $options['enable'] )&&( (bool)$options['onpost']==is_single() || (bool)$options['onpage']==is_page() ) ) {
		global $post;
		$cache_key = 'alrp_sb_final_'.$post->ID;

		$sb_final = pkalrp_get_cached_results( $cache_key );
		
		if( !empty( $sb_final ) ){
			echo '<!-- ALRP: Slidebox served from the cache -->';
			$output = $sb_final;
		} else {
			echo '<!-- ALRP: Slidebox NOT served from the cache -->';
			$output ='<div id="alrp-slidebox" style="text-align:left;">';
			$output .='<strong class="title">'.$options['title'].'</strong>'; 
			$output .='<a class="close">close</a>';
			$output .='<hr>';
			$output .='<ul class="list">';
			$output .= pkalrp_premium_slidebox_ads();
			$searches = pkalrp_get_related_posts_cattag( $post, $options['limit'] );
			if ( empty( $searches ) ) $searches = pkalrp_get_recent_posts( $options['limit'] );   
			if( $searches ){
				foreach ( $searches as $search ){
					$output .='<li>'; 
					$output .='<span class="date">'.date_format( date_create( $search->post_date ),'l, d/m/Y g:i A' ).'</span>';
					$output .='<h5 class="blue"><a href="'.get_permalink( $search->ID ).'">'.$search->post_title.'</a></h5>'; 
					$output .='<div class="clearfix"></div>';
					$output .='</li>';
				}
			}	else {
				$output .='<li>none found - should be never happened.</li>';
			}
			$output .='</ul>';
			$output .='<div class="clearfix"></div>';
			$output .='</div>';
			pkalrp_set_cached_results( $cache_key, $output );

		}
		echo $output;
	}
}
add_action( 'wp_footer', 'pkalrp_slidebox' );

/**
 * callback, base64decode. use for CSS3 tooltip, <pre> and <code> tags
 **/
function pkalrp_base64decode( $matches, $tag = 'span' ) {
	switch ($tag) {
	case 'pre':
		return '<pre'.base64_decode( $matches[1] ).'/pre>';
		break;
	case 'code':
		return '<code'.base64_decode( $matches[1] ).'/code>';
		break;
	default:
		return '<span>'.base64_decode( $matches[1] ).'</span>';
		break;
	}
}


/**
 * callback, base64encode. use for CSS3 tooltip, <pre> and <code> tags
 **/
function pkalrp_base64encode( $matches, $tag = 'span' ) {
	switch ($tag) {
	case 'pre':
		return '#pre#'.base64_encode( $matches[1] ).'#/pre#';
		break;
	case 'code':
		return '#code#'.base64_encode( $matches[1] ).'#/code#';
		break;
	}
}


function pkalrp_base64decode_span( $matches ) {
	return pkalrp_base64decode ( $matches );
}


function pkalrp_base64decode_pre( $matches ) {
	return pkalrp_base64decode ( $matches, 'pre' );
}


function pkalrp_base64decode_code( $matches ) {
	return pkalrp_base64decode ( $matches, 'code' );
}


function pkalrp_base64encode_pre( $matches ) {
	return pkalrp_base64encode ( $matches, 'pre' );
}


function pkalrp_base64encode_code( $matches ) {
	return pkalrp_base64encode ( $matches, 'code' );
}


/**
 * related posts on error page 404
 **/
function seo_alrp_404( $limit=8 ) {
	$search_term = pkalrp_get_404_title();
	echo pkalrp_get_related_posts ( $search_term, $limit );
}


/**
 * get error page 404 title
 **/
function pkalrp_get_404_title() {
	$basename = str_replace( array( '.php','.html','.htm' ),'',basename( $_SERVER['REQUEST_URI'] ) );
	$search = array ( '@[\/]+@', '@( \..* )@', '@[\-]+@', '@[\_]+@', '@[\s]+@', '@archives@','@( \?.* )@','/\d/' );
	$replace = array ( ' ', '', ' ', ' ', ' ', '', '','' );
	$search_term = preg_replace( $search, $replace, $basename );
	$search_term = trim( $search_term );
	return $search_term;
}


/**
 * capitalize 404 title
 **/
function seo_alrp_404_title() {
	echo ucwords( pkalrp_get_404_title() );
}


/**
 * get thumbnail url
 **/
function pkalrp_get_thumbnail_url( $search, $options ) {

	if ( ( function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail( $search->ID ) ) ) {
		$image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $search->ID ), 'thumbnail' );
		$thumbnail_url = $image_url[0];
	} else {
		$thumbnail_url = get_post_meta( $search->ID, $options['customfield'], true );
		if ( empty($thumbnail_url) ) {
			preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $search->post_content, $matches );
			if( isset( $matches ) && isset($matches[1][0]) ) {
				$thumbnail_url = $matches[1][0];
			}
		}
	}
	if ( empty($thumbnail_url) ) $thumbnail_url = $options['defthumb'];
	
	// use timthumb if and only if the file hosted in our server
	// if ( ( 1 === $options['timthumb'] ) && ( parse_url( $thumbnail_url, PHP_URL_HOST ) == parse_url( site_url(),  PHP_URL_HOST ) ) ) {
	if ( 1 === $options['timthumb'] ){
		$timthumb_url = ALRP_URL.'/php/thumb.php';
	
		if ( pkalrp_is_img_in_trusted_server( $thumbnail_url ) ){
			if ( parse_url( $thumbnail_url, PHP_URL_HOST ) == parse_url( site_url(),  PHP_URL_HOST ) ){
				$thumbnail_url = pkalrp_get_image_path( $thumbnail_url );
			}
			$thumbnail_url = $timthumb_url.'?src='.$thumbnail_url.'&h='.$options['imgh'].'&w='.$options['imgw'].'&q=90&zc=1';
		}
	}

	return $thumbnail_url;
}

/**
 * check if img hosted in trusted website, match with allowed external sites setting in file thumb.php
 **/
function pkalrp_is_img_in_trusted_server( $url ) {
	$localhost = parse_url( site_url(),  PHP_URL_HOST );
	$trusted_server = explode(',', $localhost .',flickr.com,staticflickr.com,picasa.com,img.youtube.com,upload.wikimedia.org,photobucket.com,imgur.com,imageshack.us,tinypic.com,facebook.com,amazon.com,amazon.co.uk,amazon.ca,amazon.it,amazon.fr,amazon.ge,amazon.es,tinypic.com');
	
	foreach( $trusted_server as $server ){
    	if(($pos = strpos($url, $server))!==false) return $pos;
    }
    return false;
    
}

/**
 * Get image page, WPMU safe
 **/
function pkalrp_get_image_path( $src ) {

	global $blog_id;
	if ( isset( $blog_id ) && $blog_id > 0 ) {
		$imageParts = explode( '/files/', $src );
		if ( isset( $imageParts[1] ) ) {
			$src = '/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
		}
	}
	$ret = strstr( $src, '/wp-content' );
	$src = ( $ret ) ? $ret : $src;
	return $src;
}


/**
 * enqueue our js and css
 **/
add_action( 'wp_enqueue_scripts', 'pkalrp_enqueue_scripts' );
function pkalrp_enqueue_scripts() {
	if ( !is_admin() ) {

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'alrp_slidebox.js', ALRP_URL.'/js/slidebox.js', array( 'jquery' ) );

		$options = get_option( 'alrp_rp_options' );
		if ( 'title-only' == $options['layout'] || 
			'title-content' == $options['layout'] || 
			'thumbnail-only' == $options['layout'] || 
			'thumbnail-only-clean' == $options['layout'] 
			){
			wp_enqueue_style( 'alrp-title-only', ALRP_URL.'/css/'. $options['layout'] .'.css' );
		}

		$options = get_option( 'alrp_sb_options' );
		$theme = (!isset($options['theme'])) ? 'light' : $options['theme'];
		wp_enqueue_style( 'alrp_slidebox.css', ALRP_URL. '/css/slidebox-'.$theme.'.css' );

		$options = get_option( 'alrp_gs_options' );
		if ($options['enabletip']){
			$tiptheme = (!isset($options['tiptheme'])) ? 'light' : $options['tiptheme'];
			wp_enqueue_style( 'alrp_tooltip.css', ALRP_URL. '/css/tooltip-'.$tiptheme.'.css' );
		}
	}
}

add_action('wp_head','pkalrp_include_dynamic_css');
function pkalrp_include_dynamic_css(){
	$options = get_option( 'alrp_rp_options' );
	if ('thumbnail-title-clean' == $options['layout'] || 
		'thumbnail-title' == $options['layout'] ||
		'title-content-thumbnail' == $options['layout'] ||
		'title-content-thumbnail-clean' == $options['layout']
		){
		include_once dirname( __FILE__ ).'/css/'.$options['layout'].'.css.php';
	}
}

/**
 * for debugging purpose only
 **/
add_action( 'activated_plugin','pkalrp_save_error' );
function pkalrp_save_error() {
	$ret = ob_get_contents();
	if (!empty($ret))
		update_option( 'alrp_activation_error',  $ret );
}


/**
 * when post got updated, clear cache for the corresponding post
 **/
add_action( 'save_post', 'pkalrp_action_save_post' );
function pkalrp_action_save_post( $post_id ) {
	if ( !wp_is_post_revision( $post_id ) ) {
		pkalrp_db_del_transients_key( $post_id );
	}
}


/**
 * get current options, use default options if empty
 **/
function pkalrp_update_options( $current, $default ) {
	if ( !empty( $current ) ) {
		foreach ( $current as $key => $value ) {
			$default[$key] = $value;
		}
	}
	return $default;
}


?>