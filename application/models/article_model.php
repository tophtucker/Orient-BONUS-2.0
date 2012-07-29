<?php
class Article_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function get_last_updated($prior_to = false)
    {
    	$this->db->select("date");
    	if($prior_to) $this->db->where("date <=", $prior_to);
    	$this->db->from("article");
    	$this->db->order_by("date", "desc");
    	$this->db->limit(1);
    	$query = $this->db->get();
    	$row = $query->row();
    	return $row->date;
    }
    
    function get_articles($vol, $no, $sec)
    {
    	$this->db->select("article.id, article.date, article.title, article.subhead, article.pullquote, series.name 'series', articletype.name 'type', photo.filename_small");
		$this->db->from("article");
		$this->db->join("series", "series.id=article.series");
		$this->db->join("articletype", "articletype.id=article.type");
		$this->db->join("photo", "photo.article_id=article.id", "left");
		$this->db->where("article.volume", $vol);
		$this->db->where("article.issue_number", $no);
		$this->db->where("article.section_id", $sec);
		$this->db->group_by("article.id");
		$this->db->order_by("article.priority", 'asc');
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
    }
    
    function get_articles_by_date($date_up_to, $date_since=false, $sec=false, $limit=false)
    {
    	$this->db->select("
    		article.id, 
    		article.date, 
    		article.title, 
    		article.subhead, 
    		article.pullquote, 
    		series.name 'series', 
    		articletype.name 'type', 
    		photo.filename_small"
    		);
		$this->db->from("article");
		
		$this->db->join("series", "series.id=article.series");
		$this->db->join("articletype", "articletype.id=article.type");
		$this->db->join("photo", "photo.article_id=article.id", "left");
		
		// note: date_up_to is inclusive; date_since is exclusive
		$this->db->where("article.date <=", $date_up_to);
		if($date_since) $this->db->where("article.date >", $date_since);
		if($sec) $this->db->where("article.section_id", $sec);
		if($limit) $this->db->limit($limit);
		
		$this->db->group_by("article.id");
		$this->db->order_by("article.date", "desc");
		$this->db->order_by("article.priority", "asc");

		
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
    }
        
    function get_popular_articles($vol, $no, $limit = '10')
    {
    	$this->db->select("article.id, article.date, article.title, article.subhead, article.pullquote, series.name 'series', articletype.name 'type', photo.filename_small");
		$this->db->from("article");
		$this->db->join("series", "series.id=article.series");
		$this->db->join("articletype", "articletype.id=article.type");
		$this->db->join("photo", "photo.article_id=article.id", "left");
		$this->db->where("article.volume", $vol);
		$this->db->where("article.issue_number", $no);
		$this->db->group_by("article.id");
		$this->db->order_by("article.views_bowdoin", 'desc');
		$this->db->limit($limit);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
    }
    
    function get_popular_articles_by_date($date_up_to, $date_since = false, $limit = '10')
    {
    	$this->db->select("
    		article.id, 
    		article.date, 
    		article.title, 
    		article.subhead, 
    		article.pullquote, 
    		series.name 'series', 
    		articletype.name 'type', 
    		photo.filename_small"
    		);
		$this->db->from("article");
		
		$this->db->join("series", "series.id=article.series");
		$this->db->join("articletype", "articletype.id=article.type");
		$this->db->join("photo", "photo.article_id=article.id", "left");
		
		// note: date_up_to is inclusive; date_since is exclusive
		$this->db->where("article.date <=", $date_up_to);
		if($date_since) $this->db->where("article.date >", $date_since);
		if($limit) $this->db->limit($limit);
		
		$this->db->group_by("article.id");
		$this->db->order_by("article.views_bowdoin", 'desc');
		$this->db->limit($limit);
		
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}    	
    }
    
    function get_article($id)
    {
    	$this->db->where("id", $id);
		$query = $this->db->get("article");
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
	
	function get_random()
	{
		$query = $this->db->query("SELECT * FROM article ORDER BY (RAND() * ln(views)) desc limit 10,1;");
		return $query->row();
	}
	
	function get_body($id)
	{
		$this->db->where("article_id", $id);
		$this->db->order_by("created", "desc");
		$query = $this->db->get("articlebody");
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
	
	function get_article_type($id)
	{	
		$this->db->where("id", $id);
		$query = $this->db->get("articletype");
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
	
	function get_article_series($id)
	{
		$this->db->where("id", $id);
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
	
	function get_article_authors($id)
	{	
		$this->db->select("author.id as authorid, author.name as authorname, job.name as jobname");
		$this->db->where("article_id", $id);
		$this->db->from("articleauthor");
		$this->db->join("author", "author.id=articleauthor.author_id");
		$this->db->join("job", "job.id=articleauthor.job_id");
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}
	
	function get_suggestions($table, $field, $term)
	{
		// boot if trying to access anything other than allowed tables
		// (this could be a major security risk if we're sloppy)
		if($table != ('articletype' || 'author' || 'job' || 'series')) return false; 
		
		$this->db->select($field." as value");
		$this->db->like($field, $term);
		$query = $this->db->get($table);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}
	
	function add_blank_article($volume, $issue_number, $section)
	{
		$data = array(
		   'date' => '',
		   'volume' => $volume,
		   'issue_number' => $issue_number,
		   'section_id' => $section,
		   'priority' => '100',
		   'title' => "Enter title here",
		   'subhead' => '',
		   'pullquote' => '',
		   'bodybackup' => ''
		);
		$query = $this->db->insert('article', $data);
		return $this->db->insert_id();
	}
	
	function edit_article($id, $data)
	{
		$this->db->where("id", $id);
		return $this->db->update("article", $data);
	}
	
	function add_articlebody_version($article_id, $body, $creator_id)
	{
		$data = array(
		   'article_id' => $article_id,
		   'body' => $body,
		   'creator_id' => $creator_id
		);
		return $this->db->insert('articlebody', $data);
	}
	
	function add_article_author($article_id, $author_name, $authorjob_name)
	{
		$author = $this->get_author_by_name($author_name);
		if(!$author)
		{
			$this->add_author($author_name);
			$author = $this->get_author_by_name($author_name);
		}
		
		$authorjob = $this->get_job_by_name($authorjob_name);
		if(!$authorjob)
		{
			$this->add_job($authorjob_name);
			$authorjob = $this->get_job_by_name($authorjob_name);
		}
		
		$data = array(
		   'article_id' => $article_id ,
		   'author_id' => $author->id ,
		   'job_id' => $authorjob->id
		);
		
		return $this->db->insert('articleauthor', $data); 
	}
	
	function get_author_by_name($name)
	{
		$this->db->where('name', $name);
		$query = $this->db->get('author');
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
	
	function add_author($name, $photo='', $job='0', $classyear='', $bio='')
	{
		$data = array(
		   'name' => $name,
		   'photo' => $photo,
		   'job' => $job,
		   'classyear' => $classyear,
		   'bio' => $bio
		);
		return $this->db->insert('author', $data);
	}
	
	function get_job_by_name($name)
	{
		$this->db->where('name', $name);
		$query = $this->db->get('job');
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
	
	function add_job($name)
	{
		$data = array(
		   'name' => $name ,
		);
		return $this->db->insert('job', $data);
	}

}
?>