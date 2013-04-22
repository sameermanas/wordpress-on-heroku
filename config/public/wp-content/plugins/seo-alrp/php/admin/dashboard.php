<?php
/**
 * create dashboard settings page
 **/
function pkalrp_admin_init_dashboard_page() {
?>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br>
  </div>
  <h2 style="font-family: verdana;">Dashboard</h2>
  <div class="dashboard">
  	<?php pkalrp_admin_dashboard_page(); ?>
  </div>
</div>
<?php
}


/**
 * print default dashboard
 **/
function pkalrp_admin_print_dashboard() {
?>

  <p>SEO Auto Links and Related Posts plugin comes in three types of licenses:
 	<ol>
  		<li><strong>FREE:</strong> Free, no registration needed. Results and thumbnail are NOT saved in CACHE <i>(slower page loading time, need more server resources, not recommended for shared hosting users - see performance comparison table below)</i>.</li>
  		<li><strong>REGISTERED:</strong> Free, registration needed. Register the plugin using a valid email address, and a multi-site license key will be sent to your email. </li>
  		<li><strong>PREMIUM:</strong> Paid version (only $4.97, multi-site license), with custom auto link and custom ads on slide out features (for affiliate marketer or anyone that want to support this plugin development).</li>
  	</ol>
  </p>
  	<div style="width: 100%">
	<table class="form-table alrp-table ta-center" style="border:none; width:750px;">
		<thead>
			<tr>
				<th class="rowtitle" style="width:250px;">PERFORMANCE COMPARISON</th>
				<th class="rowtitle" style="width:250px;text-align:center;background-color:#CFD5C4;">PREMIUM/REGISTERED</th>
				<th class="rowtitle" style="width:250px;text-align:center;">FREE*</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th class="rowtitle">Case Study #1</th>
				<td style="font-size:11px;"><strong style="color:#267296">32</strong> queries in <strong>0.470</strong> seconds.</td>
				<td style="font-size:11px;"><strong style="color:#267296">258</strong> queries in <strong>1.025</strong> seconds.</td>
			</tr>
			<tr>
				<th class="rowtitle">Case Study #2</th>
				<td style="font-size:11px;"><strong style="color:#267296">33</strong> queries in <strong>0.415</strong> seconds.</td>
				<td style="font-size:11px;"><strong style="color:#267296">260</strong> queries in <strong>0.821</strong> seconds.</td>
			</tr>
			<tr>
				<th class="rowtitle">Case Study #3</th>
				<td style="font-size:11px;"><strong style="color:#267296">33</strong> queries in <strong>0.342</strong> seconds.</td>
				<td style="font-size:11px;"><strong style="color:#267296">261</strong> queries in <strong>0.879</strong> seconds.</td>
			</tr>		
			<tr>
			<td colspan="3" style="text-align:left;font-size:10px;background-color:white;padding:2px;">*Results &amp; thumbnails are not cached in the Free version</td>	
		</tbody>
	</table>  	
	<table class="form-table alrp-table ta-center" style="border:none">
		<thead>
				
			<tr>
				<th class="rowtitle" style="width:500px;">MAIN FEATURES COMPARISON</th>
				<th class="rowtitle" style="width:150px;text-align:center;background-color:#CFD5C4;">PREMIUM</th>
				<th class="rowtitle" style="width:100px;text-align:center;">REGISTERED</th>
				<th class="rowtitle" style="width:100px;text-align:center;">FREE</th>
			</tr>
			<tr>
				<?php
					$status = get_option('alrp_status');
					switch ($status) {
					case 'premium':
						echo '<td colspan="4" style="background-color:white;"></td>';
						break;
					case 'registered':
				?>
						<td style="background-color:white;"></td>
						<td style="text-align:center;background-color:white;"><a class="button-primary" href="https://www.jvzoo.com/b/0/16203/14">Buy Now (Only <strong>$4.97</strong>)</a></td>
						<td style="background-color:white;"></td>
						<td style="background-color:white;"></td>
						<?php
						break;
					case 'free':
				?>
						<td style="background-color:white;"></td>
						<td style="text-align:center;background-color:white;"><a class="button-primary" href="https://www.jvzoo.com/b/0/16203/14">Buy Now (Only <strong>$4.97</strong>)</a></td>
						<td style="text-align:center;background-color:white;vertical-align: top;"><a href="<?php echo admin_url('admin.php?page=alrp&prev=first&next=two'); ?>" class="button-secondary">Activate</a></td>
						<td style="background-color:white;"></td>
						<?php
						break;						
					default:
				?>
						<td style="background-color:white;"></td>
						<td style="text-align:center;background-color:white;"><a class="button-primary" href="https://www.jvzoo.com/b/0/16203/14">Buy Now (Only <strong>$4.97</strong>)</a></td>
						<td style="text-align:center;background-color:white;vertical-align: top;"><a href="<?php echo admin_url('admin.php?page=alrp&prev=first&next=two'); ?>" class="button-secondary">Activate</a></td>
						<td style="text-align:center;background-color:white;vertical-align: top;"><a href="<?php echo admin_url('admin.php?page=alrp&prev=free&next=finish'); ?>" class="button-secondary">Activate</a></td>
						<?php
						break;
					}
				?>
			</tr>			
		</thead>

		<tbody>

			<tr>
				<th>Auto Links</br><span style="font-size:11px;font-weight:normal">Auto create internal link to posts related to the current post base on meta keywords and post tags. Authority sites like Wikipedia always have a lot of internal link, and this feature will automatically help you getting the same results.</span></th>
				<td style="background-color:#CFD5C4;">Yes</td>
				<td>Yes</td>
				<td>Yes</td>
			</tr>
			<tr>
				<th>Related Posts</br><span style="font-size:11px;font-weight:normal">Displaying related posts based on the content of articles, post tags and meta keywords. This feature is useful to improve internal links and to keep visitors stay on your site.</span></th>
				<td style="background-color:#CFD5C4;">Yes</td>
				<td>Yes</td>
				<td>Yes</td>
			</tr>
			<tr>
				<th>Slide Out Related Posts</br><span style="font-size:11px;font-weight:normal">It is a sliding box, with related/recent posts in it, that will automatically appeared at the lower right corner when user scroll down and reach the end of article. It will grab visitors attention right after they finish reading your article, and giving them more options about what to do next. The impact is a longer visitors time spent on your site.</span></th>
				<td style="background-color:#CFD5C4;">Yes</td>
				<td>Yes</td>
				<td>Yes</td>
			</tr>
			<tr>
				<th>Cache All Results and Thumbnail</br><span style="font-size:11px;font-weight:normal">Visitors and Google likes fast loading websites. That's why we use a persistent cache to store all results from auto links, related posts, slide out and thumbnail to achieve a faster loading time and saving server resources at the same time. All requests will be served from the cache, except on the first request.</span></th>
				<td style="background-color:#CFD5C4;">Yes</td>
				<td>Yes</td>
				<td class="hilite">No</td>
			</tr>
			<tr>
				<th>Subscribe to Our Newsletter</br><span style="font-size:11px;font-weight:normal">You will be automatically subscribed to our newsletter. In this newsletter, we will review the latest SEO techniques and WordPress plugin / theme which we believe will be beneficial to you.</span></th>
				<td style="background-color:#CFD5C4;">Yes</td>
				<td>Yes</td>
				<td class="hilite">No</td>
			</tr>
			<tr>
				<th>Custom Auto Link to Affiliate Products</br><span style="font-size:11px;font-weight:normal">Manually adding a couple of keywords and links for the auto link. You can use this feature to automatically create a link to a trusted site for better SEO results or to affiliate products to earn additional profit.</span></th>
				<td style="background-color:#CFD5C4;" >Yes</td>
				<td class="hilite">No</td>
				<td class="hilite">No</td>
			</tr>
			<tr>
				<th>Custom Ads in Slide Out Related Posts</br><span style="font-size:11px;font-weight:normal">The slide out feature would definitely attract the attention of visitors, so it is one of the best places to display your ads. This feature will add your ad on the first row of the slide out related posts. You have the option to show Amazon.com Today's Best Deals with your affiliate ID embedded and your own custom ad.</span></th>
				<td style="background-color:#CFD5C4;" >Yes</td>
				<td class="hilite">No</td>
				<td class="hilite">No</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<?php
					$status = get_option('alrp_status');
					switch ($status) {
					case 'premium':
						echo '<td colspan="4" style="background-color:white;"></td>';
						break;
					case 'registered':
				?>
						<td style="background-color:white;"></td>
						<td style="text-align:center;background-color:white;"><a class="button-primary" href="https://www.jvzoo.com/b/0/16203/14">Buy Now (Only <strong>$4.97</strong>)</a></td>
						<td style="background-color:white;"></td>
						<td style="background-color:white;"></td>
						<?php
						break;
					case 'free':
				?>
						<td style="background-color:white;"></td>
						<td style="text-align:center;background-color:white;"><a class="button-primary" href="https://www.jvzoo.com/b/0/16203/14">Buy Now (Only <strong>$4.97</strong>)</a></td>
						<td style="text-align:center;background-color:white;vertical-align: top;"><a href="<?php echo admin_url('admin.php?page=alrp&prev=first&next=two'); ?>" class="button-secondary">Activate</a></td>
						<td style="background-color:white;"></td>
						<?php
						break;						
					default:
				?>
						<td style="background-color:white;"></td>
						<td style="text-align:center;background-color:white;"><a class="button-primary" href="https://www.jvzoo.com/b/0/16203/14">Buy Now (Only <strong>$4.97</strong>)</a></td>
						<td style="text-align:center;background-color:white;vertical-align: top;"><a href="<?php echo admin_url('admin.php?page=alrp&prev=first&next=two'); ?>" class="button-secondary">Activate</a></td>
						<td style="text-align:center;background-color:white;vertical-align: top;"><a href="<?php echo admin_url('admin.php?page=alrp&prev=free&next=finish'); ?>" class="button-secondary">Activate</a></td>
						<?php
						break;
					}
				?>
			</tr>
		</tfoot>
	</table>
	<p>&nbsp;</p>
	<?php pkalrp_admin_print_copyright( 12 ); ?>
	</div>	
	<?php	
}


?>