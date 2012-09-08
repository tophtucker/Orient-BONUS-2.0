<?php
class Series_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function get_series($id)
    {
    	$this->db->where("id", $id);
    	
    	// "active" basically means "hasn't been deleted". we should almost never show inactive articles.
		$this->db->where("active", "1");
    	
		$query = $this->db->get("series");
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