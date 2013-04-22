<?php
/**
 * create related posts settings page
 **/
function pkalrp_relatedposts_page() {
	if ( isset( $_GET['settings-updated'] ) && true == $_GET['settings-updated'] ) {
		$ret = pkalrp_db_del_transients();
		echo '<div id="message" class="updated" style="margin:5px 0 25px;"><p>The <strong>Related Posts</strong> settings has been updated. '.$ret.' caches automatically deleted from database.</p></div>';
	};
?>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br>
  </div>
  <h2 style="font-family: verdana;">Related Posts Settings</h2>
  <?php pkalrp_admin_print_copyright(); pkalrp_admin_print_header_amazonaws(); ?>
  <form method="post" action="options.php" id="alrp-rp_options">
    <?php settings_fields( 'alrp_rp' ); ?>
    <?php $options = pkalrp_get_admin_rp_options(); ?>
    <table class="form-table alrp-table">
      <tr>
        <th scope="row" colspan="2" class="rowtitle"><strong>GENERAL SETTINGS</strong></th>
      </tr>
      <tr valign="top">
        <th scope="row">Enable</th>
        <td><label>
            <input type="checkbox" name="alrp_rp_options[enable]" id="alrp_rp_options[enable]" value="1" <?php checked( $options['enable'], 1 ); ?> />
            &nbsp;&nbsp;Yes</label></td>
      </tr>
      <tr  valign="top">
        <th scope="row">Related posts show at most</th>
        <td><label>
            <input type="text" size="5" name="alrp_rp_options[limit]" value="<?php echo $options['limit']; ?>" />
            posts</label></td>
      </tr>
      <tr valign="top">
        <th scope="row">Add related posts automatically</th>
        <td><label>
            <input name="alrp_rp_options[autoadd]" id="alrp_rp_options[autoadd]" type="checkbox" value="1" <?php checked( $options['autoadd'], 1 ); ?> />
            &nbsp;&nbsp;Yes<br/>
            <span style="color:#666666;margin-left:2px;">Use this code in your template for custom position: <code>&lt;?php if( function_exists( 'seo_alrp' ) ) seo_alrp(); ?&gt;</code>.</span></label></td>
      </tr>
      <tr valign="top">
        <th scope="row">Show related posts on</th>
        <td><label>
            <input name="alrp_rp_options[onpost]" type="checkbox" value="1"  <?php checked( $options['onpost'], 1 ); ?>  />
            &nbsp;&nbsp;Post</label>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <label>
            <input name="alrp_rp_options[onpage]" type="checkbox" value="1"  <?php checked( $options['onpage'], 1 ); ?>  />
            &nbsp;&nbsp;Page</label></td>
      </tr>
      <tr>
        <th scope="row">Category to be excluded</th>
        <td><label>
            <input type="text" size="25" name="alrp_rp_options[excat]" value="<?php echo $options['excat']; ?>" />
            <br/>
            <span style="color:#666666;margin-left:2px;">Category ID, separated by commas. Posts from these categories will be excluded from related posts.</span></label></td>
      </tr>
      <tr valign="top">
        <th scope="row">Find related posts base on</th>
        <td><label>
            <input name="alrp_rp_options[match]" type="radio" value="content" <?php checked( 'content', $options['match'] ); ?> />
            &nbsp;&nbsp;Content</label>
          <br />
          <label>
            <input name="alrp_rp_options[match]" type="radio" value="cattag" <?php checked( 'cattag', $options['match'] ); ?> />
            &nbsp;&nbsp;Categories and tags <span style="color:#666666;margin-left:2px;">- faster, but less accurate.</span></label>
          </td>
      </tr>
      <tr>
        <th scope="row">Related posts title</th>
        <td><label>
            <input type="text" size="50" name="alrp_rp_options[title]" value="<?php echo $options['title']; ?>" />
            <br/>
            <span style="color:#666666;margin-left:2px;">Use <i>#post_title</i> to use the Post Title inside the related posts title, e.g: <code>&lt;h3&gt;Posts related to #post_title&lt;/h3&gt;</code>.</span></label></td>
      </tr>
      <tr>
        <th scope="row">No match message</th>
        <td><label>
            <input type="text" size="50" name="alrp_rp_options[blankmsg]" value="<?php echo $options['blankmsg']; ?>" />
          </label></td>
      </tr>
      <tr valign="top">
        <th scope="row">Thumbnail size</th>
        <td><label>Width
            <input type="text" size="5" name="alrp_rp_options[imgw]" value="<?php echo $options['imgw']; ?>" />
            px</label>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <label>Height
            <input type="text" size="5" name="alrp_rp_options[imgh]" value="<?php echo $options['imgh']; ?>" />
            px</label></td>
      </tr>      
      <tr valign="top">
        <th scope="row">Related posts layout</th>
        <td style="background-color: white; border: 1px solid #efefef; padding:0px;">
          <table style="width: 100%;border-spacing:0px;">      
            <tr>
              <td>
                <select name="alrp_rp_options[layout]" id="selectlayout" style="padding: 5px 10px;font-family: verdana;font-size: 11px;width: 275px;height: 28px;">
                <option value="thumbnail-title" <?php if('thumbnail-title'==$options['layout']) echo 'selected="selected"'; ?>>Thumbnail Title</option>
                <option value="thumbnail-title-clean" <?php if('thumbnail-title-clean'==$options['layout']) echo 'selected="selected"'; ?>>Thumbnail Title - Clean</option>
                <option value="thumbnail-only" <?php if('thumbnail-only'==$options['layout']) echo 'selected="selected"'; ?>>Thumbnail Only</option>
                <option value="thumbnail-only-clean" <?php if('thumbnail-only-clean'==$options['layout']) echo 'selected="selected"'; ?>>Thumbnail Only - Clean</option>
                <option value="title-content-thumbnail" <?php if('title-content-thumbnail'==$options['layout']) echo 'selected="selected"'; ?>>Thumbnail Title Content</option>
                <option value="title-content-thumbnail-clean" <?php if('title-content-thumbnail-clean'==$options['layout']) echo 'selected="selected"'; ?>>Thumbnail Title Content - Clean</option>
                <option value="title-content" <?php if('title-content'==$options['layout']) echo 'selected="selected"'; ?>>Title Content</option>
                <option value="title-only" <?php if('title-only'==$options['layout']) echo 'selected="selected"'; ?>>Title Only</option>
                </select>
              </td>
            </tr>
            <tr valign="top" style="background-color: white">
              <td><img id="layout" src="<?php echo ALRP_URL.'/img/'.$options['layout'].'.jpg'; ?>">
              </td>
            </tr>                 
            <tr>
              <td>
            <span style="color:#666666;margin-left:2px;font-size: 11px;font-family: verdana; "><strong>TIPS:</strong> You can change how the related posts and tooltips looks like by editing the CSS stylesheet in the plugin /css folder.</span>
          </td>
        </tr>
          </table>
        </td>
      </tr>
      <tr>
        <th scope="row" colspan="2" class="rowtitle"><strong>OPTIONAL SETTINGS</strong></th>
      </tr>
      <tr valign="top">
        <th scope="row">Add related posts to blog feed</th>
        <td><label>
            <input name="alrp_rp_options[onfeed]" type="checkbox" value="1"  <?php checked( $options['onfeed'], 1 ); ?>  />
            &nbsp;&nbsp;Yes</label></td>
      </tr>
      <tr valign="top">
        <th scope="row">Resize &amp; cache thumbnail</th>
        <td><label>
            <input <?php if('free' == get_option('alrp_status')) echo 'disabled="disabled"'; ?> name="alrp_rp_options[timthumb]" type="checkbox" value="1" <?php checked( $options['timthumb'], 1 ); ?> />
            &nbsp;&nbsp;Yes<br/>
            <span style="color:#666666;margin-left:2px;">Recommended for faster page loading time. If thumbnail not displayed properly, follow <a href="http://support.hostgator.com/articles/specialized-help/technical/timthumb-basics" target="_blank">this guide</a> or disable this option.</span></label></td>
      </tr>

      <tr>
        <th scope="row">Custom field for thumbnail</th>
        <td><label>
            <input type="text" size="25" name="alrp_rp_options[customfield]" value="<?php echo $options['customfield']; ?>" />
            <br />
            <span style="color:#666666;margin-left:2px;">Optional, <i>only</i> when your theme use custom field for thumbnail.</span></label></td>
      </tr>
      <tr>
        <th scope="row">Default image for thumbnail</th>
        <td><label>
            <input type="text" size="100" name="alrp_rp_options[defthumb]" value="<?php echo $options['defthumb']; ?>" />
          </label></td>
      </tr>
      <tr>
        <th scope="row">Post excerpt length</th>
        <td><label>
            <input type="text" size="5" name="alrp_rp_options[excerptlen]" value="<?php echo $options['excerptlen']; ?>" />
            words</label></td>
      </tr>
      <tr>
        <th scope="row" colspan="2" class="whitetitle"> <input class="button-primary"  name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
        </th>
      </tr>
    </table>
  </form>
</div>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        $('#selectlayout').change(function () {
          $('#layout').attr('src', <?php echo '"'.ALRP_URL.'/img/'.'"'; ?> + $('#selectlayout :selected').val() + '.jpg');
        });
      });
    </script>
<?php
}


/**
 * admin related posts options, set default values if empty
 **/
function pkalrp_get_admin_rp_options() {
	$defthumb = ALRP_URL.'/img/default_thumbnail.gif';
	$options = array(
		'enable' => 0,
		'limit' => 4,
		'autoadd' => 1,
		'onpost' => 1,
		'onpage' => 0,
		'onfeed' => 0,
		'excat' => '',
		'match' => 'content',
		'title' => '<h3>Posts related to #post_title</h3>',
		'blankmsg' => 'None found.',
		'layout' => 'thumbnail-title',
		'timthumb' => 1,
		'imgw' => 114,
		'imgh' => 90,
		'customfield' => 'thumbnail',
		'defthumb' => $defthumb,
		'excerptlen' => 25
	);

	$current = get_option( 'alrp_rp_options' );

	$options = pkalrp_update_options( $current, $options );
	update_option( 'alrp_rp_options', $options );

	return $options;
}


/**
 * Sanitize and validate input for related posts settings page
 **/
function pkalrp_val_rp_options( $input ) {
	$input['enable'] = ( isset($input['enable']) && intval( $input['enable'] ) == 1  ) ? 1 : 0 ;
	$input['limit'] =  ( isset($input['limit']) ) ? intval(wp_filter_nohtml_kses( $input['limit'] )) : 4;
	$input['autoadd'] = ( isset($input['autoadd']) && intval( $input['autoadd'] ) == 1  ) ? 1 : 0 ;
	$input['onpost'] = ( isset($input['onpost']) && intval( $input['onpost'] ) == 1  ) ? 1 : 0 ;
	$input['onpage'] = ( isset($input['onpage']) && intval( $input['onpage'] ) == 1  ) ? 1 : 0 ;	
	$input['onfeed'] = ( isset($input['onfeed']) && intval( $input['onfeed'] ) == 1  ) ? 1 : 0 ;	
	$input['excat'] =  ( isset($input['excat']) ) ? str_replace( ' ', '',wp_filter_nohtml_kses( $input['excat'] ) ) : '';
    $title = ( isset($input['title']) ) ? trim($input['title']) : '<h3>Posts related to #post_title</h3>';
	$input['title'] =  ('<' == substr($title,0,1) || empty($title)) ? $title : '<h3>'.$title.'</h3>';
	$input['blankmsg'] =  ( isset($input['blankmsg']) ) ? wp_filter_nohtml_kses( $input['blankmsg'] ) : 'None found.';
	$input['timthumb'] = ( isset($input['timthumb']) && intval( $input['timthumb'] ) == 1  ) ? 1 : 0 ;
	$input['imgw'] =  ( isset($input['imgw']) ) ? intval( wp_filter_nohtml_kses( $input['imgw'] ) ) : 114;
	$input['imgh'] =  ( isset($input['imgh']) ) ? intval( wp_filter_nohtml_kses( $input['imgh'] ) ) : 96;
	$input['customfield'] =  ( isset($input['customfield']) ) ? wp_filter_nohtml_kses( $input['customfield'] ) : 'thumbnail';
	$defthumb = ALRP_URL.'/img/default_thumbnail.gif';
	$input['defthumb'] =  ( isset($input['defthumb']) ) ? wp_filter_nohtml_kses( $input['defthumb'] ) : $defthumb;
	$input['excerptlen'] =  ( isset($input['excerptlen']) ) ? intval( wp_filter_nohtml_kses( $input['excerptlen'] ) ) : 25;
	return $input;
}


?>