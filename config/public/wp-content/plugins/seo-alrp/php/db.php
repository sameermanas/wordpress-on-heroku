<?php
/**
 * rebuild the fulltext index
**/
function pkalrp_db_rebuild_fulltext_index(){
	$ret1 = pkalrp_db_drop_fulltext_index();
	$ret2 = pkalrp_db_add_fulltext_index();
	$return = ( false !== $ret1 ) && ( false !== $ret2 );
}
/**
 * add full text index
**/
function pkalrp_db_add_fulltext_index(){
	global $wpdb;
    $wpdb->hide_errors();
    $ret1 = $wpdb->query( 'ALTER TABLE '.$wpdb->posts.' ENGINE = MYISAM;' );
    $ret2 = $wpdb->query( 'ALTER TABLE '.$wpdb->posts.' ADD FULLTEXT seo_alrp ( post_title, post_content );' );
    $ret3 = $wpdb->query( 'ALTER TABLE '.$wpdb->posts.' ADD FULLTEXT seo_alrp_title ( post_title );' );
    $ret4 = $wpdb->query( 'ALTER TABLE '.$wpdb->posts.' ADD FULLTEXT seo_alrp_content ( post_content );' );
    $wpdb->show_errors();
    $return = ( false !== $ret1 ) && ( false !== $ret2 ) && ( false !== $ret3 ) && ( false !== $ret4 );
    return $return;
}
/**
 * drop full text index
**/
function pkalrp_db_drop_fulltext_index(){
	global $wpdb;	
	$wpdb->hide_errors();
	$ret1 = $wpdb->query( 'ALTER TABLE '.$wpdb->posts.' DROP INDEX seo_alrp' );
	$ret2 = $wpdb->query( 'ALTER TABLE '.$wpdb->posts.' DROP INDEX seo_alrp_title' );
	$ret3 = $wpdb->query( 'ALTER TABLE '.$wpdb->posts.' DROP INDEX seo_alrp_content' );
	$wpdb->show_errors();	
	$return = ( false !== $ret1 ) && ( false !== $ret2 ) && ( false !== $ret3 );
    return $return;	
}
/**
* clean up all cache data
**/
function pkalrp_db_del_transients( $cachekey='' ){
	global $wpdb;
	$wpdb->hide_errors();
	$ret1 = $wpdb->query( "DELETE FROM $wpdb->options WHERE option_name like '_transient_alrp_$cachekey%'" );
	$ret2 = $wpdb->query( "DELETE FROM $wpdb->options WHERE option_name like '_transient_timeout_alrp_$cachekey%'" );	
	$wpdb->show_errors();
	$return = $ret1 + $ret2;
    return $return;	
}
/**
* clean up cache data for specific post
**/
function pkalrp_db_del_transients_key( $post_id ){
	$ret1 = pkalrp_db_del_transients( 'rp_final_'.$post_id );
	$ret2 = pkalrp_db_del_transients( 'al_keys_'.$post_id );
	$ret3 = pkalrp_db_del_transients( 'sb_final_'.$post_id );
	$return = $ret1 + $ret2 + $ret3;
    return $return;	
}
/**
* related posts base on categories and tags only, return dataset 
**/
function pkalrp_get_related_posts_cattag( $post, $limit  )
{
	$cats = wp_get_post_categories( $post->ID, array( 'fields' => 'ids'  ) ) ;
	$tags = wp_get_post_tags( $post->ID, array( 'fields' => 'ids'  ) );
	$tagscats = array_merge( $tags, $cats );
	arsort( $tagscats );
	$catstags = implode( ',', $tagscats );
	//echo 'KATEGORI DAN TAGS: '.$catstags;
	
	if ( ( is_int( $post->ID )  )&&( !empty( $catstags )  ) ) {
		
		global $wpdb;
		$time_difference = get_option( 'gmt_offset' );
		$now = gmdate( "Y-m-d H:i:s",( time()+( $time_difference*3600 ) ) );	
			
		$sql_query = $wpdb->prepare ( 
			"SELECT a.ID, a.post_title, a.post_content, a.post_date
			FROM $wpdb->posts a 
				INNER JOIN $wpdb->term_relationships b ON ( a.ID = b.object_id ) 
			WHERE a.ID != %d 
				AND b.term_taxonomy_id IN ( %s ) 
				AND a.post_type = 'post' 
				AND ( a.post_status = 'publish' 
					AND a.post_date <= '%s'  ) 
			GROUP BY a.ID 
			ORDER BY a.post_date DESC 
			LIMIT %d",
		$post->ID, $catstags, $now, $limit );
		
		$searches = $wpdb->get_results( $sql_query );

	}
	
	return $searches;
} 	
/**
* related posts using match
**/
function pkalrp_get_related_posts_match( $post, $options ){

	$content_ok = strip_tags( $post->post_content );
	$content_ok = addslashes( $post->post_title. ' ' . $content_ok  );			
	
	$meta_keywords = get_post_meta( $post->ID, 'keywords', true );
	if ( !empty( $meta_keywords )  ) $content_ok = addslashes( $meta_keywords .' '. $content_ok );
	$content_ok = pkalrp_tokenize_stopwords( $content_ok  );
	
	$sql_title = addslashes( $post->post_title );
			
	if ( ( is_int( $post->ID )  )&&( !empty( $content_ok )  ) ) {
			
		global $wpdb;	
		$time_difference = get_option( 'gmt_offset' );
		$now = gmdate( "Y-m-d H:i:s",( time()+( $time_difference*3600 ) ) );				

		$sql_query = $wpdb->prepare( 
			"SELECT a.ID, a.post_title, a.post_content, 
				CASE WHEN a.post_title LIKE '%%%s%%' THEN 1 ELSE 0 END AS title_score,
				MATCH ( a.post_title, a.post_content ) AGAINST ( '%s' ) AS score
			FROM $wpdb->posts a
				INNER JOIN $wpdb->term_relationships b ON ( a.ID = b.object_id ) 
			WHERE  a.ID NOT IN ( %d ) 
				AND ( MATCH ( a.post_title, a.post_content ) AGAINST ( '%s' IN BOOLEAN MODE )  ) 
				AND ( a.post_status = 'publish' AND a.post_date <= '%s'	AND a.post_type = 'post'  )                
				AND b.term_taxonomy_id NOT IN ( %s ) 
			GROUP BY a.ID
			ORDER BY ( title_score * 5 + score  ) DESC
			LIMIT %d",
			$sql_title, $content_ok,  $post->ID, $content_ok, $now, $options['excat'], $options['limit']  );

		$searches = $wpdb->get_results( $sql_query  );		
		
	} 	
	return $searches;
		
}
/**
* related posts in 404 page 
**/
function pkalrp_get_related_posts_404( $options, $err_search_term, $limit404  ){

	$content_ok	= addslashes( pkalrp_tokenize_stopwords( strip_tags( $err_search_term ) ) );
	$sql_title = addslashes( $err_search_term );
		
	global $wpdb;	
	$time_difference = get_option( 'gmt_offset' );
	$now = gmdate( "Y-m-d H:i:s",( time()+( $time_difference*3600 ) ) );			

	$sql_query = $wpdb->prepare( 
		"SELECT a.ID, a.post_title, a.post_content, 
			CASE WHEN a.post_title LIKE '%%%s%%' THEN 1 ELSE 0 END AS title_score,
			MATCH ( a.post_title, a.post_content ) AGAINST ( '%s' ) AS score
		FROM $wpdb->posts a 
			INNER JOIN $wpdb->term_relationships b ON ( a.ID = b.object_id ) 
		WHERE ( MATCH ( a.post_title, a.post_content ) AGAINST ( '%s' IN BOOLEAN MODE )  ) 
			AND ( a.post_status = 'publish' AND a.post_date <= '%s'	AND a.post_type = 'post'  )                
			AND b.term_taxonomy_id NOT IN ( %s ) 
		GROUP BY a.ID
		ORDER BY ( title_score * 5 + score  ) DESC
		LIMIT %d",
		$sql_title, $content_ok, $content_ok, $now, $options['excat'], $limit404  );
		
	$searches = $wpdb->get_results( $sql_query  );
	
	return $searches;

}
/**
* recent posts for slidebox when no related posts base on cats/tags found
**/
function pkalrp_get_recent_posts( $limit ){
		
	global $wpdb;	
	$time_difference = get_option( 'gmt_offset' );
	$now = gmdate( "Y-m-d H:i:s",( time()+( $time_difference*3600 ) ) );			

	$sql_query = $wpdb->prepare( 
		"SELECT a.ID, a.post_title, a.post_date
		FROM $wpdb->posts a
		WHERE a.post_status = 'publish' AND a.post_date <= '%s'	AND a.post_type = 'post'
		ORDER BY a.post_date DESC
		LIMIT %d",
		$now, $limit );
		
	$searches = $wpdb->get_results( $sql_query  );

	return $searches;
}
/**
* get related posts for autolinks
**/
function pkalrp_get_related_posts_autolinks( $post, $options, $content ){						
		
	global $wpdb;
	$time_difference = get_option( 'gmt_offset' );
	$now = gmdate( "Y-m-d H:i:s",( time()+( $time_difference*3600 ) ) );
	$content_ok = pkalrp_tokenize_stopwords( strip_tags( $content )  );

	$sql_query = $wpdb->prepare( 
		"SELECT a.ID, a.post_title, 
			MATCH ( a.post_title, a.post_content ) AGAINST ( '%s' ) AS score
		FROM $wpdb->posts a 
		WHERE  a.ID NOT IN ( %d ) 
			AND ( MATCH ( a.post_title, a.post_content ) AGAINST ( '%s' IN BOOLEAN MODE )  ) 
			AND ( a.post_status = 'publish' AND a.post_date <= '%s'	AND a.post_type = 'post'  ) 
		GROUP BY a.ID
		ORDER BY score DESC
		LIMIT %d",
		$content_ok, $post->ID, $content_ok, $now, $options['matchlimit']  );	

	//echo '<!-- ALRP: pkalrp_get_related_posts_autolinks:query: '.$sql_query.' -->';
	
	$searches = $wpdb->get_results( $sql_query );	

	return $searches;
}		
?>