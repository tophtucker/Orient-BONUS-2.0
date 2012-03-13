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

?>