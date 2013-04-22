<?php
/**
 * print registration form for previous user
 **/
function pkalrp_reg_print_alreg_form( $email='' ) {
?>
	<form method="get" id="reguser">
	<input type="hidden" name="page" value="alrp"/>
	<input type="hidden" name="next" value="finish"/>
	<input type="hidden" name="prev" value="alreg"/>
    <table class="alrp-regform">
	    <tr>
		    <th><label for="email">Registered Email:</label></th>
		    <td><input class="required email" id="alregemail" type="text" name="email" value="<?php echo $email; ?>" /></td>
		    </tr>
		<tr>
			    <tr>
	    	<th><label for="key">License Key:</label></th>
	    	<td><input style="width:350px" id="key" type="text" name="key" class="required key" value="" /></td>
	    </tr>

			<th></th>
			<td>
			<input class="button-primary" name="submit" class="submit" type="submit" value="Submit" />
		</td>
		</tr>
	</table>
	</form>
	<?php
}


/**
 * print registration form for new user
 **/
function pkalrp_reg_print_aweber_form() {
?>
	<h4>B. REGISTER AS NEW USER</h4>
	<form method="post" id="aweberform" action="http://www.aweber.com/scripts/addlead.pl"  >    
	
    <input type="hidden" name="custom token" value="" id="token"/>
	<input type="hidden" name="meta_web_form_id" value="1627425926" />
	<input type="hidden" name="meta_split_id" value="" />
	<input type="hidden" name="listname" value="seoalrp" />
	<input type="hidden" name="redirect" value="<?php echo admin_url('admin.php?page=alrp&next=two&prev=new'); ?>" id="redirect_37315ae94d40c865b5c31ed945729fe1" />
	<input type="hidden" name="meta_redirect_onlist" value="<?php echo admin_url('admin.php?page=alrp&next=finish&prev=new'); ?>" />
	<input type="hidden" name="meta_adtracking" value="SEO_ALRP" />
	<input type="hidden" name="meta_message" value="1" />
	<input type="hidden" name="meta_required" value="name,email,custom token" />   

    <table class="alrp-regform">
	    <tr>
	    	<th><label for="name">First Name:</label></th>
	    	<td><input id="name" type="text" name="name" class="required name" value="" style="text-transform: capitalize;"/></td>
	    </tr>
	    <tr>
		    <th><label for="email">Your Best Email:</label></th>
		    <td><input class="required email" id="email" type="text" name="email" value="" /></td>
		    </tr>
		<tr>
			<th></th>
			<td>
				<input class="button-primary" name="submit" class="submit" type="submit" value="Submit" />
			</td>
		</tr>
	</table>

    </form>
<?php
}


?>