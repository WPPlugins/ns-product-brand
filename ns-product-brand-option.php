<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ns_product_brand_activate_set_options()
{
   add_option('ns_product_brand_active', '');
}

register_activation_hook( __FILE__, 'ns_addcart_activate_set_options');



function ns_product_brand_register_options_group()
{
    register_setting('ns_product_brand_options_group', 'ns_product_brand_active');   
}
 
add_action ('admin_init', 'ns_product_brand_register_options_group');

?>