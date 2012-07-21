<?php
class Tools_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
	function submittip($tip)
	{
		$data = array(
		   'tip' => $tip
		);
		$this->db->insert('tips', $data); 
	}
        
}
?>