<?php
/**
 * create help settings page
 **/
function pkalrp_admin_general_page() {
	if ( isset( $_GET['emptycache'] ) ) {
	$return = pkalrp_db_del_transients();
	echo '<div id="message" class="updated fade" style="margin:5px 0 25px;"><p>'.$return.' caches has been deleted from database.</p></div>';
	}
	if ( isset( $_GET['rebuildindex'] ) ) {
	$return = pkalrp_db_rebuild_fulltext_index();
	if ( false !== $return ) {
	  echo '<div id="message" class="updated fade" style="margin:5px 0 25px;"><p><strong>MySQL Database Full-text index</strong> was successfully rebuild.</p></div>';
	} else {
	  echo '<div id="message" class="updated fade" style="margin:5px 0 25px;"><p><strong>MySQL Database Full-text index</strong> was NOT successfully rebuild!</p></div>';
	}
	}
	if ( isset( $_GET['reset'] ) ) {
	pkalrp_admin_reset_settings();
	echo '<div id="message" class="updated fade" style="margin:5px 0 25px;"><p>All settings has been restored to its default values.</p></div>';
	}  
	if ( isset( $_GET['settings-updated'] ) && true == $_GET['settings-updated'] ) {
		$ret = pkalrp_db_del_transients();
		echo '<div id="message" class="updated" style="margin:5px 0 25px;"><p>The <strong>General</strong> settings has been updated.'.$ret.' caches automatically deleted from database.</p></div>';
	};  
?>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br>
  </div>
  <h2 style="font-family: verdana;">General Settings</h2>
  <?php pkalrp_admin_print_copyright(); pkalrp_admin_print_header_amazonaws(); ?>  
  <form method="post" action="options.php" id="alrp-gs-options">
    <?php settings_fields( 'alrp_gs' ); ?>
    <?php $options = pkalrp_get_admin_gs_options(); ?>
    <table class="form-table alrp-table">
    <tr valign="top">
      <th scope="row" colspan="2" class="rowtitle"><strong>GENERAL SETTINGS</strong></th>
    </tr>
      <tr valign="top">
        <th scope="row">Enable tooltip</th>
        <td><label>
            <input name="alrp_gs_options[enabletip]" type="checkbox" value="1" <?php checked( $options['enabletip'], 1 ); ?> />
            &nbsp;&nbsp;Yes</label></td>
      </tr>    
    <tr valign="top">
    <th scope="row">Tooltip theme</th>
    <td>
		<select name="alrp_gs_options[tiptheme]" id="selecttiptheme" style="padding: 5px 10px;font-family: verdana;font-size: 11px;width: 150px;height: 28px;">
		<option value="light" <?php if('light'==$options['tiptheme']) echo 'selected="selected"'; ?>>Light</option>
		<option value="dark" <?php if('dark'==$options['tiptheme']) echo 'selected="selected"'; ?>>Dark</option>
		</select>
        <p style="margin-bottom: 0"><img id="tiptheme" src="<?php echo ALRP_URL.'/img/tooltip-'.$options['tiptheme'].'.jpg'; ?>"></p>
    </td>
    </tr>    
    
    <th scope="row">Cache will be expired after</th>
    <td><label>
      <input type="text" size="5" name="alrp_gs_options[expire]" value="<?php echo $options['expire']; ?>" />
      days</label></td>
    </tr>     
      <tr>
        <th scope="row" colspan="2" class="whitetitle"> <input class="button-primary"  name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
        </th>
      </tr>    
    <tr valign="top">
      <th scope="row" colspan="2" class="rowtitle"><strong>MAINTENANCE</strong></th>
    </tr>  
    <tr valign="top">
      <th scope="row">Clean up all caches</th>
      <td valign="top"><a href="<?php echo admin_url('admin.php?page=alrp-general&emptycache=1'); ?>" class="button-secondary">Delete All Caches</a></td>
    </tr>
    <tr valign="top">
      <th scope="row">Rebuild full-text index</th>
      <td valign="top"><a href="<?php echo admin_url('admin.php?page=alrp-general&rebuildindex=1'); ?>" class="button-secondary">Rebuild Full-Text Index</a>&nbsp;&nbsp;
            <p style="margin: 5px 0px 0px;"><span style="color:#666666;margin-left:2px;"><small>Use this when the plugin suddenly stop working. It the problem persist, try to optimize/fix your database from CPanel &raquo; phpMyAdmin.</small></span></p></td>
    </tr>
    <?php $status = get_option('alrp_status');
    if (!empty($status)){ ?>
    <tr valign="top">
      <th scope="row">Reset all plugin settings</th>
      <td valign="top"><a href="<?php echo admin_url('admin.php?page=alrp-general&reset=1'); ?>" class="button-secondary">Reset to Default Settings</a></td>
    </tr>
    <?php } ?>      
    </table> 
  </form>
      <script type="text/javascript">
      jQuery(document).ready(function($) {
        $('#selecttiptheme').change(function () {
          $('#tiptheme').attr('src', <?php echo '"'.ALRP_URL.'/img/tooltip-'.'"'; ?> + $('#selecttiptheme :selected').val() + '.jpg');
        });
      });
    </script>
<?php  
}

/**
 * admin general options, set default values if empty
 **/
function pkalrp_get_admin_gs_options() {
  $options = array(
  	'enabletip' => 0,
    'tiptheme' => 'dark',
    'expire' => 7
  );

  $current = get_option( 'alrp_gs_options' );

  $options = pkalrp_update_options( $current, $options );
  update_option( 'alrp_gs_options', $options );

  return $options;
}


/**
 * Sanitize and validate input for general settings page
 **/
function pkalrp_val_gs_options( $input ) {
	$input['enabletip'] = ( isset($input['enabletip']) && intval( $input['enabletip'] ) == 1  ) ? 1 : 0 ;
	$input['tiptheme'] =  ( isset($input['tiptheme']) ) ? wp_filter_nohtml_kses( $input['tiptheme'] ) : 'dark';
	$input['expire'] =  ( isset($input['expire']) ) ? intval( wp_filter_nohtml_kses( $input['expire'] ) ) : 7;  
	return $input;
}

/**
* reset all settings
**/
function pkalrp_admin_reset_settings(){
	delete_option( 'alrp_al_options' );
	delete_option( 'alrp_rp_options' );
	delete_option( 'alrp_sb_options' );
  delete_option( 'alrp_gs_options' );  
  pkalrp_admin_init_settings();
	pkalrp_reg_enable_features();
}

?>