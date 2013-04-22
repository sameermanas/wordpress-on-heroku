<?php
function pkalrp_slideout_page(){
	if ( isset( $_GET['settings-updated'] ) && true == $_GET['settings-updated'] ) {
		$ret = pkalrp_db_del_transients();
		echo '<div id="message" class="updated" style="margin:5px 0 25px;"><p>The <strong>Slide Out Related Posts</strong> settings has been updated. '.$ret.' caches automatically deleted from database.</p></div>';
	};  
?>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br>
  </div>
  <h2 style="font-family: verdana;">Slide Out Related Posts Settings</h2>
  <?php pkalrp_admin_print_copyright(); pkalrp_admin_print_header_amazonaws(); ?>
  <form method="post" action="options.php" id="alrp-sb-options">
    <?php settings_fields( 'alrp_sb' ); ?>
    <?php $options = pkalrp_get_admin_sb_options(); ?>
    <table class="form-table alrp-table">
      <tr>
        <th scope="row" colspan="2" class="rowtitle"><strong>GENERAL SETTINGS</strong></th>
      </tr>
      <tr valign="top">
        <th scope="row">Enable slide-out related posts</th>
        <td><label>
            <input name="alrp_sb_options[enable]" type="checkbox" value="1" <?php checked( $options['enable'], 1 ); ?> />
            &nbsp;&nbsp;Yes<br/>
            <span style="color:#666666;margin-left:2px;">When user scroll down and reach the end of article, a slide-out box, with related posts inside it, will appear at the bottom right corner.</span></label></td>
      </tr>
      <tr>
        <th scope="row">Slide-out box title</th>
        <td><label>
            <input type="text" size="50" name="alrp_sb_options[title]" value="<?php echo $options['title']; ?>" />
            <br/>
            <span style="color:#666666;margin-left:2px;">Box title for slide-out related posts.</span></label></td>
      </tr>
      <tr  valign="top">
        <th scope="row">Slide out related posts show at most</th>
        <td><label>
            <input type="text" size="3" name="alrp_sb_options[limit]" value="<?php echo $options['limit']; ?>" /><span>Max 5 related posts</span> posts</label> </td>
      </tr>
      
      <tr valign="top">
        <th scope="row">Show slide out related posts on</th>
        <td><label>
            <input name="alrp_sb_options[onpost]" type="checkbox" value="1"  <?php checked( $options['onpost'], 1 ); ?>  />
            &nbsp;&nbsp;Post</label>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <label>
            <input name="alrp_sb_options[onpage]" type="checkbox" value="1"  <?php checked( $options['onpage'], 1 ); ?>  />
            &nbsp;&nbsp;Page</label></td>
      </tr>      
      <tr valign="top">
        <th scope="row">Slide out theme</th>
        <td>
          <select name="alrp_sb_options[theme]" id="selecttheme" style="padding: 5px 10px;font-family: verdana;font-size: 11px;width: 150px;height: 28px;">
          <option value="light" <?php if('light'==$options['theme']) echo 'selected="selected"'; ?>>Light</option>          
          <option value="blue" <?php if('blue'==$options['theme']) echo 'selected="selected"'; ?>>Blue</option>
          <option value="green" <?php if('green'==$options['theme']) echo 'selected="selected"'; ?>>Green</option>
          <option value="darkgreen" <?php if('darkgreen'==$options['theme']) echo 'selected="selected"'; ?>>Dark Green</option>
          <option value="orange" <?php if('orange'==$options['theme']) echo 'selected="selected"'; ?>>Orange</option>
          <option value="dark" <?php if('dark'==$options['theme']) echo 'selected="selected"'; ?>>Dark</option>
          </select> <?php if('free'==get_option ('alrp_status')) echo '  <span style="color:#666666;margin-left:2px;">FREE version come with Light theme only. Please upgrade to unlock the other themes.</span>'; ?>
          <p style="margin-bottom: 0"><img id="theme" src="<?php echo ALRP_URL.'/img/slidebox-'.$options['theme'].'.jpg'; ?>"></p>
        </td>
      </tr>       
      <?php pkalrp_admin_print_premium_slideout( $options ); ?>
      <tr>
        <th scope="row" colspan="2" class="whitetitle"> <input class="button-primary"  name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
        </th>
      </tr>      
      </table>
     </form>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        $('#selecttheme').change(function () {
          $('#theme').attr('src', <?php echo '"'.ALRP_URL.'/img/slidebox-'.'"'; ?> + $('#selecttheme :selected').val() + '.jpg');
        });
      });
    </script>     
<?php	
}


/**
 * admin slidebox options, set default values if empty
 **/
function pkalrp_get_admin_sb_options() {
	$options = array(
		'enable' => 1,
		'title' => 'You might also like',
		'onpost' => 1,
		'onpage' => 0,
	    'theme' => 'light',
	    'limit' => 2,
		'enableads' => 0,
		'azonaffid' => '',
		'customadstitle' => '',
		'customadslink' => '',
	    'customadstitle2' => '',
	    'customadslink2' => ''    
	);

	$current = get_option( 'alrp_sb_options' );

	$options = pkalrp_update_options( $current, $options );
	update_option( 'alrp_sb_options', $options );

	return $options;
}


/**
 * Sanitize and validate input for slidebox settings page
 **/
function pkalrp_val_sb_options( $input ) {
	$input['enable'] = ( isset($input['enable']) && intval( $input['enable'] ) == 1  ) ? 1 : 0 ;
	$input['title'] =  ( isset($input['title']) ) ? wp_filter_nohtml_kses( $input['title'] ) : 'You might also like';
	$input['limit'] =  ( isset($input['limit']) ) ? intval( wp_filter_nohtml_kses( $input['limit'] ) ) : 2;
	$input['limit'] = ( 5 < $input['limit'] ) ? 5 : $input['limit']; 
	$input['enableads'] = ( isset($input['enableads']) && intval( $input['enableads'] ) == 1  ) ? 1 : 0 ;
	$input['onpost'] = ( isset($input['onpost']) && intval( $input['onpost'] ) == 1  ) ? 1 : 0 ;
	$input['onpage'] = ( isset($input['onpage']) && intval( $input['onpage'] ) == 1  ) ? 1 : 0 ;
	$input['azonaffid'] =  ( isset($input['azonaffid']) ) ? wp_filter_nohtml_kses( $input['azonaffid'] ) : '';
	$input['theme'] =  ( isset($input['theme']) ) ? wp_filter_nohtml_kses( $input['theme'] ) : 'light';
	if('free'==get_option ('alrp_status')) $input['theme'] = 'light';    
	$input['customadstitle'] =  ( isset($input['customadstitle']) ) ? wp_filter_nohtml_kses( $input['customadstitle'] ) : '';
	$input['customadslink'] =  ( isset($input['customadslink']) ) ? wp_filter_nohtml_kses( $input['customadslink'] ) : '';
	$input['customadstitle2'] =  ( isset($input['customadstitle2']) ) ? wp_filter_nohtml_kses( $input['customadstitle2'] ) : '';
	$input['customadslink2'] = ( isset($input['customadslink2']) ) ? wp_filter_nohtml_kses( $input['customadslink2'] ) : '';
	return $input;
}

?>