<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php // PUT YOUR settings_fields name and your input // ?>
<?php settings_fields('ns_product_brand_options_group'); ?>
<table>
	<tr valign="top">
	    <th scope="row">
	   		 <label>Active</label>
	    </th>
	    <td>
			<input type="checkbox" disabled="disabled" name="ns_product_brand_active" id="ns_product_brand_active" checked="checked" value="1" <? checked( get_option('ns_product_brand_active'), 1); ?>> 
            <span class="description">Check to active</span><br/>
		</td>
	</tr>

	<tr valign="top">
	    <td colspan="2">
	    	<hr>
            <span class="description">Go to Product -> Brands and start to add your Brands! See the image below</span><br/><br/>
            <img src="<? echo plugin_dir_url( __FILE__ ); ?>img/productbrand-01-guide.png">
		</td>
	</tr>
</table>


