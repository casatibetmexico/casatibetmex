<?php
global $sbar_args, $post;

$upcoming_dates = array();
if ($sbar_args['show_upcoming']) {
	$events = ct_event_get_upcoming(-1, array('fields'=>'ids'));
	foreach($events as $e) {
		list($d, $h) = explode(' ', get_post_meta($e, 'ct_event_start', true));
		$upcoming_dates[] = $d;
	}
}


if ($_GET['date']) {
	list($year, $month, $day) = explode('-', $_GET['date']);
	if (!$day) $day = 1;
	$focus = $year.'-'.$month.'-'.$day;
	$curDate = array('year'=>$year, 'month'=>$month);
} else {
	list($year, $month) = explode('-', date('Y-m', strtotime('now')));
	$curDate = array('year'=>$year, 'month'=>$month);
}

$upcoming_dates = json_encode($upcoming_dates);
$curDate = json_encode($curDate);

?>
<div class="calendar"></div>
<input type="hidden" id="search_by_date" value="" />
<script type="text/javascript">
	var datesArray = <?php echo $upcoming_dates; ?>;
	var curDate = <?php echo $curDate; ?>;
	function ctLoadDate(data) {
		if (data.year && data.month) {
			var target = 'date='+data.year+'-'+data.month;
			if(data.day) target += '-'+data.day;
			ct.goTo('<?php echo ct_get_permalink();?>?'+target, true);
		}
	}
	jQuery(document).ready(function() {
		jQuery('.calendar').datepicker({
			<?php if ($focus) : ?>defaultDate:'<?php echo $focus; ?>',<?php endif; ?>
	        dateFormat : 'yy-mm-dd',
	        beforeShowDay: function(date) {
	        	var theday = [];
	        	theday.push(date.getFullYear());
	        	var month = date.getMonth()+1;
	        	theday.push((month < 10) ? '0'+month : month);
	        	var date = date.getDate();
	        	theday.push((date < 10) ? '0'+date : date);
	        	var theday = theday.join('-');
				return [true,jQuery.inArray(theday, datesArray) >=0?"day_highlight":''];
			},
			onChangeMonthYear: function(year, month) {
				curDate = {year:year, month:month};
			},
			onSelect: function(dateText) {
				var parts = dateText.split('-');
				var data = {year:parts[0], month:parts[1], day:parts[2]};
				ctLoadDate(data);
			}
	    });
	    
	    jQuery('.calendar').click(function(e) {
	    	var el = jQuery(e.srcElement);
	    	if (el.hasClass('ui-datepicker-title') ||
	    		el.hasClass('ui-datepicker-month') || 
	    		el.hasClass('ui-datepicker-year')) {
	    		ctLoadDate(curDate);
	    	}
	    });
    });
</script>