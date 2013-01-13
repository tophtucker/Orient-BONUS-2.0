<?php

function bonus()
{
	$CI =& get_instance();
	return $CI->session->userdata('logged_in');
}

function currentuser()
{
	$CI =& get_instance();
	return $CI->session->userdata('logged_in');
}

function username()
{
	$CI =& get_instance();
	$session_data = $CI->session->userdata('logged_in');
	return $session_data['username'];
}

function userid()
{
	$CI =& get_instance();
	$session_data = $CI->session->userdata('logged_in');
	return $session_data['id'];
}

function error()
{
	$CI =& get_instance();
	$CI->load->view('error');
}

/* Intelligently return the issue_id given either 
   the issue date, 
   volume and number, or 
   issue_id */
function issue($p1, $p2=false)
{
	if($p2) {
		// it's vol/no
	}
	
	// if it's ####-##-##
	
	// else, it's issue_id
	
	return true;
}

// from http://snipplr.com/view/35635/
function relativedate($secs) {
	$second = 1;
	$minute = 60;
	$hour = 60*60;
	$day = 60*60*24;
	$week = 60*60*24*7;
	$month = 60*60*24*7*30;
	$year = 60*60*24*7*30*365;
	
	if ($secs <= 0) { $output = "now";
	}elseif ($secs > $second && $secs < $minute) { $output = round($secs/$second)." second";
	}elseif ($secs >= $minute && $secs < $hour) { $output = round($secs/$minute)." minute";
	}elseif ($secs >= $hour && $secs < $day) { $output = round($secs/$hour)." hour";
	}elseif ($secs >= $day && $secs < $week) { $output = round($secs/$day)." day";
	}elseif ($secs >= $week && $secs < $month) { $output = round($secs/$week)." week";
	}elseif ($secs >= $month && $secs < $year) { $output = round($secs/$month)." month";
	}elseif ($secs >= $year && $secs < $year*10) { $output = round($secs/$year)." year";
	}else{ $output = " more than a decade ago"; }
	
	if ($output <> "now"){
		$output = (substr($output,0,2)<>"1 ") ? $output."s" : $output;
	}
	return $output;
}

// from http://snipplr.com/view/35635/

function dateify($date, $epoch='') {
	
	if(empty($epoch)) $epoch = date();
	
	$secs = strtotime($epoch)-strtotime($date);
	$date_formatted = date("F j",strtotime($date));
	$output = '';
	
	$second = 1;
	$minute = 60;
	$hour = 60*60;
	$day = 60*60*24;
	$week = 60*60*24*7;
	$month = 60*60*24*7*30;
	$year = 60*60*24*7*30*365;
	
	if ($secs <= 0) { return "<span class='today'>today</span>";
	}elseif ($secs > $second && $secs < $minute) { $output = "<span class='recent'>".round($secs/$second)." second";
	}elseif ($secs >= $minute && $secs < $hour) { $output = "<span class='recent'>".round($secs/$minute)." minute";
	}elseif ($secs >= $hour && $secs < $day) { $output = "<span class='recent'>".round($secs/$hour)." hour";
	}elseif ($secs >= $day && $secs < $week) { $output = "<span class='recent'>".round($secs/$day)." day";
	}elseif ($secs >= $week && $secs < $month) { return "<span class='old'>".$date_formatted."</span>"; }
	
	$output = (substr($output,0,2)<>"1 ") ? $output."s" : $output;
	$output .= " ago</span>";
	return $output;
}

?>