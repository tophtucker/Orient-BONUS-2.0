<?php
class Bonus_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
	function login($username, $password)
	{
		$this->db->where('username', $username);
		$this->db->where('password', MD5($password));
		$this->db->limit(1);
		$query = $this->db->get('users');
		if($query->num_rows() == 1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}
    
    function get_random_quote($filter = TRUE, $public = '1')
    {
    	$this->db->order_by('id', 'random');
    	if($filter) $this->db->where('public', $public);
    	$query = $this->db->get('quote');
    	if($query->num_rows() > 0)
    	{
    		return $query->row();
    	}
    	else
    	{
    		return false;
    	}
    }
    
}
?>