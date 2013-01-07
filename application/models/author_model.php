<?php
class Author_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function get_author($id)
    {
    	$this->db->where("id", $id);
    	
    	// "active" basically means "hasn't been deleted". we should almost never show inactive articles.
		$this->db->where("active", "1");
    	
		$query = $this->db->get("author");
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
	
	function get_authors()
	{
		$this->db->where('active','1');
		$query = $this->db->get('author');
		return $query->result();
	}
	
	function get_authors_array()
	{
		$this->db->where('active','1');
		$query = $this->db->get('author');
		$authors = $query->result();
		$authors_array = array();
		foreach($authors as $author)
		{
			$authors_array[$author->id] = $author->name;
		}
		return $authors_array;
	}
	
	function get_all_author_ids()
	{
		$this->db->select('id');
		$this->db->where('active','1');
		$query = $this->db->get('author');
		return $query->result();
	}
	
	function merge_authors($from_id, $to_id)
	{
		//change articles
		$this->db->where('author_id', $from_id);
		$this->db->set('author_id', $to_id);
		$query1 = $this->db->update('articleauthor');
		
		//change photos
		$this->db->where('photographer_id', $from_id);
		$this->db->set('photographer_id', $to_id);
		$query2 = $this->db->update('photo');
		
		//deactivate the from_ author
		$this->db->set('active', '0');
		$this->db->where('id', $from_id);
		$query3 = $this->db->update('author');
		
		return ($query1 && $query2 && $query3);
	}
	
}
?>