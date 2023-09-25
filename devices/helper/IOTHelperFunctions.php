<?php
	
/**
 * @param $endDate
 * @param $startDate
 * @return string|void
 */
function get_period_ago($endDate, $startDate) {
	$dateInterval = $endDate->diff($startDate);
	
	if ($dateInterval->invert==1) {
		if (($dateInterval->y > 0) && ($dateInterval->y == 1)) {
			return $dateInterval->y . " year ago";
		} else if ($dateInterval->y > 0) {
			return $dateInterval->y . " years ago";
		}
		if (($dateInterval->m > 0) && ($dateInterval->m == 1)) {
			return $dateInterval->m . " month ago";
		} else if ($dateInterval->m > 0) {
			return $dateInterval->m . " months ago";
		}
		if (($dateInterval->d > 7) && $dateInterval->d < 14) {
			return (int)($dateInterval->d / 7) . " week ago";
		} else if ($dateInterval->d > 7) {
			return (int)($dateInterval->d / 7) . " weeks ago";
		}
		if (($dateInterval->d > 0) && ($dateInterval->d == 1)) {
			return $dateInterval->d . " day ago";
		} else if ($dateInterval->d > 0) {
			return $dateInterval->d . " days ago";
		}
	}
}

function dateReadFormat($datetime) {
	return date("d-M-Y H:i:s" , strtotime($datetime));
}
	
	function dPMessage(){
		echo ' <div id="aSucc" class="alert alert-success" style="display: none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button>
                <p id="dp_suc_msg"></p>
            </div>
            <div id="aFail"  class="alert alert-danger" style="display: none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
			×</button>
                <p id="dp_fail_msg"></p>
            </div>';
	}