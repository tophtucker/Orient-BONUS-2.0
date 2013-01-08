<?php
class Tools_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
	function submittip($data)
	{
		return $this->db->insert('tips', $data); 
	}
	
	function get_tips()
	{
		$this->db->order_by('submitted', 'desc');
		$query = $this->db->get('tips');
		return $query->result();
	}
	
	function get_alerts($include_future = false)
	{
		$this->db->where('active','1');
		if(!$include_future) $this->db->where('start_date <= ', 'NOW()', false);
		$this->db->where('end_date >= ', 'NOW()', false);
		$query = $this->db->get('alerts');
		return $query->result();
    }
    
    function add_alert($data)
    {
    	return $this->db->insert('alerts', $data); 
    }
    
    function delete_alert($id)
    {
    	$this->db->set('active','0');
    	$this->db->where('id',$id);
    	return $this->db->update('alerts');
	}

}
?>