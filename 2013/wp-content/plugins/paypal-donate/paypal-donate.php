<?php
/*
Plugin Name: RM PayPal Donate
Description: Provides shortcode for inserting a custom paypal form.
Author: Joe Flumerfelt
Version: 0.1
*/


add_action('wp_enqueue_scripts', 'pp_styles', 0);
function pp_styles() {
	wp_enqueue_style('pp_donate', plugins_url('paypal-donate.css', __FILE__));
}


function pp_donate_shortcode($atts, $content) {

return '
<form class="pp_payment_form" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<INPUT TYPE="hidden" name="charset" value="utf-8">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="'.$atts['email'].'">
<input type="hidden" name="lc" value="MX">
<input type="hidden" name="item_name" value="'.htmlspecialchars_decode(htmlentities($atts['name'], ENT_NOQUOTES, 'UTF-8'), ENT_NOQUOTES).'">
<!--<input type="hidden" name="item_number" value="00001">-->
<input type="hidden" name="currency_code" value="MXN">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="return" value="'.$atts['return'].'">
<input type="hidden" name="cancel_return" value="'.$atts['return'].'">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn-donate.png:NonHosted">
<div class="pp_payment_wrapper">
<div class="pp_payment_btn">
<input type="image" alt="PayPal, la forma más segura y rápida de pagar en línea." name="submit" src="https://www.paypalobjects.com/es_XC/i/btn/btn_donate_LG.gif">
</div>
<div class="pp_payment_info">
<input type="hidden" name="quantity" value="1" style="width:25px"/> <input type="text" name="amount" value="'.$atts['price'].'"> <span class="label">'.$atts['name'].'</span>
</div>
<p>'.$content.'</p>
</div>

<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>';

}
add_shortcode( 'pp_donate', 'pp_donate_shortcode' );


?>