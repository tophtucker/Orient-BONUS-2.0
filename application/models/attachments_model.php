<?php
class Attachments_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('article_model', '', TRUE);
    }
    
    function get_photos($article_id)
    {
    	$this->db->select("
    		photo.id as photo_id, 
    		photo.filename_small, 
    		photo.filename_large, 
    		photo.credit, 
    		photo.caption, 
    		author.id as photographer_id, 
    		author.name as photographer_name");
    	$this->db->join("author", "author.id = photo.photographer_id", 'left');
    	$this->db->from("photo");
    	$this->db->where("article_id", $article_id);
    	$this->db->order_by("priority", "asc");
    	$query = $this->db->get();
    	if($query->num_rows() > 0)
    	{
    		return $query->result();
    	}
    	else
    	{
    		return FALSE;
    	}
    }
    
    function get_feature_photos($article_date, $section_id)
    {
    	$this->db->select("
    		photo.id as photo_id, 
    		photo.filename_small, 
    		photo.filename_large, 
    		photo.credit, 
    		photo.caption, 
    		author.id as photographer_id, 
    		author.name as photographer_name");
    	$this->db->join("author", "author.id = photo.photographer_id", 'left');
    	$this->db->from("photo");
    	$this->db->where("article_date", $article_date);
    	$this->db->where("feature", "1");
    	$this->db->where("feature_section", $section_id)
    	$this->db->order_by("priority", "asc");
    	$query = $this->db->get();
    	if($query->num_rows() > 0)
    	{
    		return $query->result();
    	}
    	else
    	{
    		return FALSE;
    	}
    }
    
    function add_photo($filename_small, $filename_large, $credit, $caption, $article_id)
    {
    	$photographer = $this->article_model->get_author_by_name($credit);
		if(!$photographer)
		{
			$this->article_model->add_author($credit);
			$photographer = $this->article_model->get_author_by_name($credit);
		}
		
		$data = array(
		   'filename_small' => $filename_small,
		   'filename_large' => $filename_large,
		   'photographer_id' => $photographer->id,
		   'caption' => $caption,
		   'article_id' => $article_id
		);
		return $this->db->insert('photo', $data);
    }
    
    // TODO
    function edit_photo($credit, $caption)
    {
    	return true;
    }

}
?>