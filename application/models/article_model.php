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
    	
    	// "active" basically means "hasn't been deleted". we should almost never show inactive articles.
		$this->db->where("article.active", "1");
		// show draft (unpublished) articles only if logged into bonus.
		if(!bonus()) $this->db->where("article.published", "1");
    	
    	$this->db->from("article");
    	$this->db->order_by("date", "desc");
    	$this->db->limit(1);
    	$query = $this->db->get();
    	$row = $query->row();
    	return $row->date;
    }
    
    function get_all_article_ids()
	{
		$this->db->select('id');
		$this->db->where('active','1');
		$query = $this->db->get('article');
		return $query->result();
	}
    
    // for the love of god, either use a finite date span or a limit!
    // i.e. don't let both $date_since and $limit stay false.
    // maybe this function should control for that ugly possibility.
    function get_articles_by_date($date_up_to, $date_since=false, $sec=false, $limit=false, $featured=false, $author=false, $series=false)
    {
    	$this->db->select("
    		article.id, 
    		article.date, 
    		article.title, 
    		article.subhead, 
    		article.pullquote, 
    		article.published,
    		article.featured,
    		author.name 'author',
    		series.name 'series', 
    		articletype.name 'type', 
    		photo.filename_small"
    		);
		$this->db->from("article");
		
		$this->db->join("series", "series.id=article.series");
		$this->db->join("articletype", "articletype.id=article.type");
		$this->db->join("photo", "photo.article_id=article.id", "left");
		
		// join author
		$this->db->join("articleauthor", "articleauthor.article_id=article.id", "left");
		$this->db->join("author", "author.id=articleauthor.author_id", "left");
		
		// "active" basically means "hasn't been deleted". we should almost never show inactive articles (or photos).
		$this->db->where("photo.active", "1");
		$this->db->where("article.active", "1");
		// show draft (unpublished) articles only if logged into bonus.
		if(!bonus()) $this->db->where("article.published", "1");
		
		// for carousel or whatever, may choose to just fetch featured articles
		if($featured) $this->db->where("article.featured", "1");
		
		if($series) $this->db->where('article.series', $series);
		
		if($author) {
			$this->db->where("articleauthor.author_id", $author);
		}
		
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
    
    function get_popular_articles_by_date($date_up_to, $date_since = false, $limit = '10')
    {
    	$this->db->select("
    		article.id, 
    		article.date, 
    		article.title, 
    		article.subhead, 
    		article.pullquote,
    		article.published,
    		article.featured,
    		series.name 'series', 
    		articletype.name 'type', 
    		photo.filename_small"
    		);
		$this->db->from("article");
		
		$this->db->join("series", "series.id=article.series");
		$this->db->join("articletype", "articletype.id=article.type");
		$this->db->join("photo", "photo.article_id=article.id", "left");
		
		// "active" basically means "hasn't been deleted". we should almost never show inactive articles.
		$this->db->where("article.active", "1");
		// show draft (unpublished) articles only if logged into bonus.
		if(!bonus()) $this->db->where("article.published", "1");
		
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
    	
    	// "active" basically means "hasn't been deleted". we should almost never show inactive articles.
		$this->db->where("active", "1");
    	
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
		// weighted by popularity; truncates the head of the list (offset of 10) because
		// it kept returning the same articles too often, even with the natural-log
		// softening. there's surely a better algorithm, but it works well. 
		$query = $this->db->query("SELECT * FROM article ORDER BY (RAND() * ln(views)) desc limit 10,1;");
		return $query->row();
	}
	
	function get_body($id)
	{
		$this->db->where("article_id", $id);
		$this->db->order_by("timestamp", "desc");
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
		   'date' => date("Y-m-d"),
		   'volume' => $volume,
		   'issue_number' => $issue_number,
		   'section_id' => $section,
		   'priority' => '10',
		   'title' => "Enter title here",
		   'subhead' => '',
		   'pullquote' => '',
		);
		$query = $this->db->insert('article', $data);
		return $this->db->insert_id();
	}
	
	function edit_article($id, $data)
	{
		$this->db->where("id", $id);
		return $this->db->update("article", $data);
	}
	
	function delete_article($id)
	{
		$this->db->set('active', '0');
		$this->db->where('id', $id);
		return $this->db->update('article');
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
	
	function increment_article_views($article_id)
	{
		$this->db->where('id', $article_id);
		$this->db->set('views', 'views+1', FALSE);
		$this->db->update('article');
		
		// Now add to Bowdoin views.
		// Not sure why this is doing !==, but I guess I trust it. "Is not identical to"?
		// Code comes directly from the old version of the site. I'm not on-campus
		// to test atm, lololol.
		if (strpos(gethostbyaddr($_SERVER['REMOTE_ADDR']), "bowdoin") !== false) {
			$this->db->where('id', $article_id);
			$this->db->set('views_bowdoin', 'views_bowdoin+1', FALSE);
			$this->db->update('article');
		}
	}
	
	function add_article_series($article_id, $series_name)
	{
		$series = $this->get_series_by_name($series_name);
		if(!$series)
		{
			$this->add_series($series_name);
			$series = $get_series_by_name($series_name);
		}
		
		$this->db->where('id',$article_id);
		$this->db->set('series',$series->id);
		return $this->db->update('article');
	}
	
	function remove_article_series($article_id)
	{
		$this->db->where('id',$article_id);
		$this->db->set('series','0');
		return $this->db->update('article');		
	}
	
	function get_series_by_name($series_name)
	{
		$this->db->where('name', $series_name);
		$query = $this->db->get('series');
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
	
	function add_series($name, $photo='', $description='')
	{
		$data = array(
		   'name' => $name,
		   'photo' => $photo,
		   'description' => $description
		);
		return $this->db->insert('series', $data);
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
	
	function get_id_by_triplet($date, $section_id, $priority)
	{
		$this->db->select('id');
		$this->db->where('date', $date);
		$this->db->where('section_id', $section_id);
		$this->db->where('priority', $priority);
		$query = $this->db->get('article');
		$result = $query->row();
		return $result->id;
	}
}
?>