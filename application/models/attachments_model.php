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
    	$this->db->where("photo.active", "1");
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
    
	function get_author_photos($author_id)
	{
    	$this->db->select("
    		photo.id as photo_id, 
    		photo.filename_small, 
    		photo.filename_large, 
    		photo.credit, 
    		photo.caption, 
    		photo.article_id,
    		article.title,
    		article.date");
    	$this->db->join("article", "article.id = photo.article_id", 'left');
    	$this->db->from("photo");
    	$this->db->where("photographer_id", $author_id);
    	$this->db->where("photo.active", "1");
    	$this->db->order_by("article.date", "desc");
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
    	$this->db->where("feature_section", $section_id);
    	$this->db->where("active", "1");
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
    
    function add_photo($filename_small, $filename_large, $filename_original, $credit, $caption, $article_id, $priority='1')
    {
    	$photographer = $this->article_model->get_author_by_name($credit);
		if(!$photographer)
		{
			$this->article_model->add_author($credit);
			$photographer = $this->article_model->get_author_by_name($credit);
		}
		
		$data = array(
		   'filename_small' 	=> $filename_small,
		   'filename_large' 	=> $filename_large,
		   'filename_original' 	=> $filename_original,
		   'photographer_id' 	=> $photographer->id,
		   'caption' 			=> $caption,
		   'article_id' 		=> $article_id,
		   'priority' 			=> $priority
		);
		return $this->db->insert('photo', $data);
    }
    
    // #TODO
    function edit_photo($credit, $caption)
    {
    	return true;
    }
    
    function delete_photo($photo_id)
    {
    	$this->db->set('active', '0');
		$this->db->where('id', $photo_id);
		return $this->db->update('photo');
    }
    
    function remove_article_photos($article_id)
    {
    	$photo_count = $this->count_article_photos($article_id);
    	if($photo_count == 0) return "No photos to remove.";
    	
    	$this->db->set('active','0');
    	$this->db->where('article_id', $article_id);
    	$this->db->update('photo');
    	
    	return "Photos removed.";
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
    
    function count_article_photos($id)
    {
    	$this->db->select("count(*) as count");
    	$this->db->where("article_id", $id);
    	$query = $this->db->get("photo");
    	$result = $query->row();
    	return $result->count;
    }

}
?>