<?php
global $sbar_args, $post;
$cmd = ($sbar_args['cmd']) ? $sbar_args['cmd'] : '_donations';
?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<INPUT TYPE="hidden" name="charset" value="utf-8">
<input type="hidden" name="cmd" value="<?php echo $cmd; ?>">
<input type="hidden" name="business" value="<?php echo $sbar_args['email']; ?>">
<input type="hidden" name="lc" value="MX">
<input type="hidden" name="amount" value="<?php echo $sbar_args['price']; ?>">
<?php if ($sbar_args['shipping']) : ?>
<input type="hidden" name="shipping" value="<?php echo $sbar_args['shipping']; ?>">
<?php endif; ?>
<input type="hidden" name="item_name" value="<?php echo $sbar_args['name']; ?>">
<input type="hidden" name="currency_code" value="MXN">
<input type="hidden" name="button_subtype" value="services">
<?php if ($sbar_args['return']) : ?>
<input type="hidden" name="return" value="<?php echo $sbar_args['return']; ?>">
<input type="hidden" name="cancel_return" value="<?php echo $sbar_args['return']; ?>">
<?php endif; ?>
<input type="hidden" name="quantity" value="<?php echo ($sbar_args['qty']) ? $sbar_args['qty'] : 1; ?>" />
<input type="hidden" name="bn" value="PP-BuyNowBF:btn-donate.png:NonHosted">
<?php if ($cmd == '_xclick') : ?>
<input type="image" alt="PayPal, la forma más segura y rápida de pagar en línea." name="submit" src="https://www.paypalobjects.com/es_XC/i/btn/btn_buynow_LG.gif">
<?php else : ?>
<input type="image" alt="PayPal, la forma más segura y rápida de pagar en línea." name="submit" src="https://www.paypalobjects.com/es_XC/i/btn/btn_donate_LG.gif">
<?php endif; ?>
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

