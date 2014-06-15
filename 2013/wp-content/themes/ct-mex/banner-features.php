<?php global $banners; 

$obs = array();
foreach((array) $banners as $key=>$val) {
	$ob = array('title'=>$val->post_title,
				'caption'=>nl2br(get_post_meta($val->ID, 'rm-banner-caption', true)),
				'url'=>get_post_meta($val->ID, 'rm-banner-link', true),
				'image'=>wp_get_attachment_url(get_post_thumbnail_id($val->ID)));
	
	$obs[$key] = $ob;
}
$args = array('items'=>$obs,
			  'anim_rate'=>4000);
?>
<div class="feature_wrapper"></div>
<script type="text/javascript">
	jQuery('.feature_wrapper').featureSlider(<?php echo json_encode($args);?>);
</script>

