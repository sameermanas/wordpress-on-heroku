<?php
/**
 * include_once menu files
 **/
include_once dirname( __FILE__ ) .'/admin/related-posts.php';
include_once dirname( __FILE__ ) .'/admin/auto-links.php';
include_once dirname( __FILE__ ) .'/admin/dashboard.php';
include_once dirname( __FILE__ ) .'/admin/help.php';
include_once dirname( __FILE__ ) .'/admin/registration.php';
include_once dirname( __FILE__ ) .'/admin/slideout.php';
include_once dirname( __FILE__ ) .'/admin/general.php';

/**
 * create plugin admin menu
 **/
add_action( 'admin_menu', 'pkalrp_admin_create_menu' );
function pkalrp_admin_create_menu() {
	$basename = 'alrp';
	add_menu_page( 'SEO ALRP Plugin Settings', 'SEO ALRP', 'manage_options', $basename );
	add_submenu_page( $basename, 'SEO ALRP - Dashboard', 'Dashboard', 'manage_options', $basename, 'pkalrp_admin_init_dashboard_page'  );
	add_submenu_page( $basename, 'SEO ALRP - Auto Links Settings', 'Auto Links', 'manage_options', $basename.'-autolinks', 'pkalrp_admin_init_autolinks_page' );
	add_submenu_page( $basename, 'SEO ALRP - Related Posts Settings', 'Related Posts', 'manage_options', $basename.'-relatedposts', 'pkalrp_admin_init_relatedposts_page' );
	add_submenu_page( $basename, 'SEO ALRP - Slide Out Related Posts Settings', 'Slide Out', 'manage_options', $basename.'-slideout', 'pkalrp_admin_init_slideout_page' );	
	add_submenu_page( $basename, 'SEO ALRP - General Settings', 'General Settings', 'manage_options', $basename.'-general', 'pkalrp_admin_general_page' );		
	add_submenu_page( $basename, 'SEO ALRP - Documentation', 'Documentation', 'manage_options', $basename.'-doc', 'pkalrp_admin_help_page' );
}


/**
 * register plugin options to white list our options, enqueue css for admin page
 **/
add_action( 'admin_init', 'pkalrp_admin_init' );
function pkalrp_admin_init() {
	register_setting( 'alrp_rp', 'alrp_rp_options', 'pkalrp_val_rp_options' );
	register_setting( 'alrp_al', 'alrp_al_options', 'pkalrp_val_al_options' );
	register_setting( 'alrp_sb', 'alrp_sb_options', 'pkalrp_val_sb_options' );
	register_setting( 'alrp_gs', 'alrp_gs_options', 'pkalrp_val_gs_options' );
	wp_enqueue_style( 'alrp_admin.css', ALRP_URL.'/css/admin.css' );
	wp_enqueue_style( 'alrp_tooltip.css', ALRP_URL.'/css/tooltip-dark.css' );	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery.validate.js', ALRP_URL.'/js/jquery.validate.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery.md5.js', ALRP_URL.'/js/jquery.md5.js', array( 'jquery' ) );
}


/**
 * print header copyright
 **/
function pkalrp_admin_print_copyright( $fontsize = 10 ) {
	$status = get_option('alrp_status');
	$helpdesk = ( 'premium' == $status ) ? ' | <a href="http://support.exclusivewp.com" target="_blank">Help Desk</a>' : '';
	$affiliate = ( 'premium' == $status ) ? ' | <a target="_blank" href="https://www.jvzoo.com/affiliates/info/16203">Affiliate Earn 100% Commission</a>' : '';
?>
	<p style="font-family:verdana;font-size:<?php echo $fontsize; ?>px;padding-left: 12px;margin: -20px 2px 5px;text-align: right;">Copyright &copy; 2012, by Purwedi Kurniawan<?php echo $helpdesk.$affiliate;?></p>
	<?php
}


/**
 * print plugin is not activated header
 **/
function pkalrp_admin_print_not_activated_header($page_title) {
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br>
		</div>
		<h2 style="font-family: verdana;"><?php echo $page_title; ?> Settings</h2>
	</div>
	<?php
}

/**
* init plugin settings
**/
function pkalrp_admin_init_settings($once = false){
	pkalrp_get_admin_rp_options();
	pkalrp_get_admin_al_options();
	pkalrp_get_admin_sb_options();
	pkalrp_get_admin_gs_options();
	pkalrp_reg_premium_features();
	if ( $once ) pkalrp_chk_prev_settings();
}

/**
* add dashboard widgets
**/
add_action( 'wp_dashboard_setup', 'pkalrp_dashboard_widgets' );
function pkalrp_dashboard_widgets() {
	wp_add_dashboard_widget( 'dashboard_special_offers', 'Special Offers News', 'pkalrp_dashboard_display' );
}
function pkalrp_dashboard_display()
{
	$rss_feed = 'http://www.getresponse.com/rss/seo_alrp_buyer';
	echo '<div class="rss-widget">';
	wp_widget_rss_output( array(
		'url' => $rss_feed,
		'title' => 'Special Offers News',
		'items' => 5,
		'show_summary' => 0,
		'show_author' => 0,
		'show_date' => 1
		) );
	echo '</div>';
}

?>