<?php
/**
 * create auto links settings page
 **/
function pkalrp_autolinks_page() {
	if ( isset( $_GET['settings-updated'] ) && true == $_GET['settings-updated'] ) {
		$ret = pkalrp_db_del_transients();
		echo '<div id="message" class="updated" style="margin:5px 0 25px;"><p>The <strong>Auto Links</strong> settings has been updated. '.$ret.' caches automatically deleted from database.</p></div>';
	};
?>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br>
  </div>
  <h2 style="font-family: verdana;">Auto Links Settings</h2>
  <?php pkalrp_admin_print_copyright(); pkalrp_admin_print_header_amazonaws(); ?>
  <form method="post" action="options.php" id="alrp-al_options">
    <?php settings_fields( 'alrp_al' ); ?>
    <?php $options = pkalrp_get_admin_al_options(); ?>
    <table class="form-table alrp-table">
      <tr>
        <th scope="row" colspan="2" class="rowtitle"><strong>GENERAL SETTINGS</strong></th>
      </tr>
      <tr valign="top">
        <th scope="row">Enable</th>
        <td><label>
            <input type="checkbox" name="alrp_al_options[enable]" id="alrp_al_options[enable]" value="1" <?php checked( $options['enable'], 1 ); ?> />
            &nbsp;&nbsp;Yes</label></td>
      </tr>
      <tr valign="top">
        <th scope="row">Auto links base on</th>
        <td><label>
            <input name="alrp_al_options[tag]" type="checkbox" value="1"  <?php checked( $options['tag'], 1 ); ?>  />
            &nbsp;&nbsp;Tags</label>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <label>
            <input name="alrp_al_options[keyword]" type="checkbox" value="1"  <?php checked( $options['keyword'], 1 ); ?>  />
            &nbsp;&nbsp;Meta keyword</label></td>
      </tr>
      <tr>
        <th scope="row">Auto links per keyword/tag</th>
        <td><label>
            <input type="text" size="3" name="alrp_al_options[rglimit]" value="<?php echo $options['rglimit']; ?>" /> internal link per keyword/tag</label></td>
      </tr>
      <tr>
        <th scope="row">Related posts to be compared</th>
        <td><label>
            <input type="text" size="3" name="alrp_al_options[matchlimit]" value="<?php echo $options['matchlimit']; ?>" />
            posts<br/>
            <span style="color:#666666;margin-left:2px;">We gathered all keywords and tags used by x numbers of posts related to the current post. If the current post containing the keyword/tag, it will be transformed into internal link to the related post.</span></label></td>
      </tr>
      <tr>
        <th scope="row">Auto links title/tooltip</th>
        <td><label>
            <input type="text" size="45" name="alrp_al_options[title]" value="<?php echo $options['title']; ?>" />
            Use <i>#title</i> as a place holder for the related post title.
            <br /><span style="color:#666666;margin-left:2px;">Sample result: <code>&lt;a href="http://yourdomain.com/article/" title="#title. Read more ... &raquo;"&gt;keyword&lt;/a&gt;</code>.</span></label></td>
      </tr>
      <tr>
        <th scope="row" colspan="2" class="rowtitle"><strong>PREMIUM FEATURES</strong></th>
      </tr>
      <?php pkalrp_admin_print_premium_auto_links( $options ); ?>
      <tr>
        <th scope="row" colspan="2" class="whitetitle"> <input class="button-primary"  name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
        </th>
      </tr>
    </table>
  </form>
</div>
<?php
}


/**
 * print table of manual links for better representation
 **/
function pkalrp_print_manual_links( $manuallinks ) {
?>
		<table class="widefat alrp-widefat">
			<thead><tr><th style="width:150px">Keyword</th><th style="width:450px">URL</th><th style="width:50px">Rel</th></tr></thead>
			<tbody>
			<?php
      	if (!empty($manuallinks)) {
      		$a_manuallinks = explode("\n", $manuallinks);
      		foreach ($a_manuallinks as $links) {
      			$a_onelink = explode(',', $links);
      			$nofollow = ( 3 == count($a_onelink) ) ? $a_onelink[2] : '';
      			echo '<tr><td>'.$a_onelink[0].'</td><td>'.$a_onelink[1].'</td><td>'.$nofollow.'</td></tr>';
      		}
      	}
      ?>
			</tbody>
		</table>
		<?php
}


/**
 * admin auto links options, set default values if empty
 **/
function pkalrp_get_admin_al_options() {
	$options = array(
		'enable' => 0,
		'tag' => 1,
		'keyword' => 1,
		'rglimit' => 1,
		'matchlimit' => 200,
		'title' => '#title. Read more ... &raquo;',
		'manuallinks' => "seo plugin,http://exclusivewp.com/seo-auto-links-related-posts/\nwordpress,http://wordpress.org,nofollow"
	);
	$current = get_option( 'alrp_al_options' );
	$options = pkalrp_update_options( $current, $options );
	update_option( 'alrp_al_options', $options );
	return $options;
}


/**
 * Sanitize and validate input for auto links settings page
 **/
function pkalrp_val_al_options( $input ) {
	$input['enable'] = ( isset($input['enable']) && intval( $input['enable'] ) == 1  ) ? 1 : 0 ;
	$input['tag'] = ( isset($input['tag']) && intval( $input['tag'] ) == 1  ) ? 1 : 0 ;
	$input['keyword'] = ( isset($input['keyword']) && intval( $input['keyword'] ) == 1  ) ? 1 : 0 ;	
	$input['rglimit'] =   ( isset($input['rglimit']) ) ? intval( wp_filter_nohtml_kses( $input['rglimit'] ) ) : 1;
	$input['matchlimit'] =  ( isset($input['matchlimit']) ) ? intval( wp_filter_nohtml_kses( $input['matchlimit'] ) ) : 200;
	$input['title'] =  ( isset($input['title']) ) ? wp_filter_nohtml_kses( $input['title'] ) : '#title. Read more ... &raquo;';
	$input['manuallinks'] =  ( isset($input['manuallinks']) ) ? wp_filter_nohtml_kses( trim(str_replace(', ',',', $input['manuallinks']),' ') ) : "seo plugin,http://exclusivewp.com/seo-auto-links-related-posts/\nwordpress,http://wordpress.org,nofollow";

	return $input;
}


?>