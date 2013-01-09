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
		// for bonus merge tool dropdown. shouldn't be used publicly, esp due to
		// htmlentities encoding. 
		$this->db->where('active','1');
		$this->db->order_by('name','asc');
		$query = $this->db->get('author');
		$authors = $query->result();
		$authors_array = array();
		foreach($authors as $author)
		{
			$authors_array[$author->id] = htmlspecialchars($author->name);
		}
		return $authors_array;
	}
	
	function get_author_series($id)
	{
		$this->db->select('title, series, name');
		$this->db->join('articleauthor', 'articleauthor.article_id = article.id');
		$this->db->join('series', 'series.id = article.series');
		$this->db->group_by('series');
		$this->db->where('articleauthor.author_id', $id);
		$this->db->where('name !=', '');
		$query = $this->db->get('article');
		return $query->result();
	}
	
	function get_author_longreads($id)
	{
		$this->db->select('article.id, article.title, length(body) as bodylength');
		$this->db->join('articlebody', 'articlebody.article_id = article.id');
		$this->db->join('articleauthor', 'articleauthor.article_id = article.id');
		$this->db->where('articleauthor.author_id', $id);
		//$this->db->order_by('articlebody.timestamp', 'desc');
		$this->db->order_by('bodylength', 'desc');
		$this->db->group_by('article.id');
		$this->db->limit('3');
		$query = $this->db->get('article');
		return $query->result();		
	}
	
	function get_author_collaborators($id)
	{
		$this->db->select('article_id');
		$this->db->where('author_id', $id);
		$query = $this->db->get('articleauthor');
		$article_ids_2d = $query->result();
		
		$article_ids = array();
		foreach($article_ids_2d as $aid) {
			$article_ids[] = $aid->article_id;
		}
		
		$this->db->select('author.id as author_id, author.name, article.id as article_id, article.title');
		$this->db->where_in('article_id', $article_ids);
		$this->db->where('articleauthor.author_id !=', $id);
		$this->db->join('author', 'author.id = articleauthor.author_id');
		$this->db->join('article', 'article.id = articleauthor.article_id');
		$this->db->group_by('author.id');
		$query2 = $this->db->get('articleauthor');
		return $query2->result();
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