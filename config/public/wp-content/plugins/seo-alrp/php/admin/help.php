<?php
/**
 * create help settings page
 **/
function pkalrp_admin_help_page() {
?>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br>
  </div>
  <h2 style="font-family: verdana;">Documentation</h2>  
  <?php pkalrp_admin_print_copyright(); pkalrp_admin_print_header_amazonaws(); ?>
  <table class="form-table  alrp-table">
    <tr valign="top">
      <th scope="row" colspan="2" class="rowtitle"><strong>DOCUMENTATION</strong></th>
    </tr>
    <tr valign="top">
      <td style="padding-left:20px;" colspan="2">
        <ol>
      	  <li>Use this code in your template to display related posts manually: <code>&lt;?php if( function_exists( 'seo_alrp' ) ) seo_alrp(); ?&gt;</code>.</li>
          <li>You can show related posts in your <strong>error 404 template page</strong> using these two functions:<blockquote> <code>&lt;?php seo_alrp_404( 25 ); ?&gt;</code> to display 25 related posts, and if needed, <code>&lt;?php seo_alrp_404_title(); ?&gt;</code> to echo the keyword used ( base on requested URL ).</blockquote>
          <li>Cache will be expired after X days (can be set manually in General settings page), or when the post got updated, or you clean up them manually using Delete All Caches button.</li>
          <li>You can change how the related posts and tooltips looks like by editing the CSS stylesheet in the /css folder.</li>
          <li>Slidebox is using related posts from the same category and tag, but if none found, recent posts will be used instead.</li>
        </ol>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row" colspan="2" class="rowtitle"><strong>FAQ</strong></th>
    </tr>
    <tr valign="top">
      <td style="padding-left:20px;" colspan="2"><ol>
          <li>I got ionCube PHP Loader error, what should I do? <br />Contact your hosting, and ask them to activate ionCube PHP Loader extension.</li>
          <li>Slide out related posts is not displayed and can't be found in the view source code! <br />Make sure that your theme calling this code <code>&lt;?php wp_footer(); ?&gt;</code> inside the footer.php file, right before <code>&lt;/body></code> tag.</li>
          <li>How to check whether ALRP served my page from cache or not? <br />Refresh your page, right click any where, select view page source, and find a text like these: 
          <br /><code>&lt;!-- ALRP: Autolinks served/NOT served from the cache --&gt;</code> <code>&lt;!-- ALRP: Related Posts served/NOT served from the cache --&gt;</code> <code>&lt;!-- ALRP: Slidebox served/NOT served from the cache --&gt;</code></li>
        </ol>
      </td>
    </tr>

   <?php
	$return = get_option( 'alrp_activation_error' );
	if ( !empty( $return ) ) {
	?>
	<tr valign="top">
      <th scope="row" colspan="2" class="rowtitle"><strong>ACTIVATION ERROR MESSAGE</strong></th>
    </tr>
    <tr valign="top">
      <td style="padding-left:20px;" colspan="2"><?php echo $return; ?></td>
      </tr>
      <?php } ?>
  </table>
</div>
<?php
}

?>