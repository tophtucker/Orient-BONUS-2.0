<?php
class Article extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('America/New_York');
		$this->load->model('issue_model', '', TRUE);
		$this->load->model('article_model', '', TRUE);
		$this->load->model('attachments_model', '', TRUE);
	}
	
	public function index($id = '')
	{
		if(!$id) 
		{
			$this->error();
		}
		else
		{
			$this->view($id);
		}
	}
	
	public function error($message = '')
	{
		$data->message = $message;
		$this->load->view('error', $data);
	}
	
	public function testview($date, $section, $priority)
	{
		$this->db->where('date', $date);
		$this->db->where('section_id', $section);
		$this->db->where('priority', $priority);
		$query = $this->db->get('article');
		$article = $query->row();
		$data->article = $article;
		$this->load->view('dbtest', $data);
	}
	
	public function view($id)
	{		
		$article = $this->article_model->get_article($id);
		
		if(!$article) 
		{
			$this->error("No such article exists.");
		}
		else
		{
			$body = $this->article_model->get_body($id);
			$type = $this->article_model->get_article_type($article->type);
			$series = $this->article_model->get_article_series($article->series);
			$authors = $this->article_model->get_article_authors($id);
			$photos = $this->attachments_model->get_photos($id);
			
			// get random quote
			$data->footerdata->quote = $this->attachments_model->get_random_quote();
			
			$data->headerdata->date = $article->date;
			$data->headerdata->volume = $article->volume;
			$data->headerdata->issue_number = $article->issue_number;
			$data->headerdata->section_id = $article->section_id;
						
			$data->article = $article;
			$data->body = $body;
			$data->type = $type;
			$data->series = $series;
			$data->authors = $authors;
			$data->photos = $photos;
			
			$this->load->view('article', $data);
		}
	}
	
	public function add($volume, $issue_number, $section)
	{
		if(!bonus())
		{
			exit("Not logged in!");
		}
		$article_id = $this->article_model->add_blank_article($volume, $issue_number, $section);
		redirect('/article/'.$article_id, 'refresh');
		
		/*
		$this->load->model('issue_model', '', TRUE);
		$issue = $this->issue_model->get_issue($volume, $issue_number);
		
		$data->article->date = $issue->issue_date;
		$data->article->volume = $volume;
		$data->article->issue_number = $issue_number;
		$data->article->title = "Title";
		$data->article->subhead = '';
		
		$data->body->body = '<p>Body</p>';
		$data->type = false;
		$data->series = false;
		$data->authors = false;
		$data->photos = false;
				
		$this->load->view('article', $data);
		*/
	}
		
	public function edit($id)
	{
		if(!bonus())
		{
			exit("Not logged in!");
		}
		$title = $this->input->post("title");
		$subtitle = $this->input->post("subtitle");
		$author = $this->input->post("author");
		$authorjob = $this->input->post("authorjob");
		$body = $this->input->post("body");
		
		$data = array(
			'title' => $title,
			'subhead' => $subtitle
			);
		$articlesuccess = $this->article_model->edit_article($id, $data);
		
		if($body) 
		{
			$bodysuccess = $this->article_model->add_articlebody_version($id, $body, userid());
		}
		
		$authorsuccess = true;
		if(strlen($author) > 1 && strlen($authorjob) > 1)
		{
			$authorsuccess = $this->article_model->add_article_author($id, $author, $authorjob);
			if($authorsuccess)
			{
				exit("refresh");
			}
		}
		
		if($articlesuccess && $authorsuccess) 
		{
			exit("Success!");
		}
		else 
		{
			exit("Failure."); 
		}
	}
	
	/**
	  * Currently only supports jpg.
	  **/
	public function ajax_add_photo($article_date, $article_id)
	{
		$this->load->helper('file');
		
		$css_offset = 4;
		$css_offset_tail = 1;
		$png_offset = 22;
		$jpg_offset = 23;
		
		// atm we only support jpg :( #todo
		$offset = $css_offset + $jpg_offset;
		$offset_tail = $css_offset_tail;
		$strlen_offset = $offset + $offset_tail;
		
		$img = substr($this->input->post("img"), $offset, strlen($this->input->post("img"))-($strlen_offset));
		$credit = $this->input->post("credit");
		$caption = $this->input->post("caption");
		
		// bug: "When Base64 gets POSTed, all pluses are interpreted as spaces."
		// this corrects for it.
		$img_fixed = str_replace(' ','+',$img);
		
		// create directory for relevant date if necessary
		if(!is_dir('images/'.$article_date))
		{
			mkdir('images/'.$article_date);
		}
		
		// so that you can upload multiple photos to an article and the filenames won't collide,
		// we write it $articleid."_1" for the first photo attached to an article, $articleid."_2", etc.
		$article_photo_number = $this->attachments_model->count_article_photos($article_id) + 1;
		
		$filename_root = $article_id.'_'.$article_photo_number.'.jpg';
		
		// at the moment we are not processing & saving different sizes :( #todo
		//$filename_small = $filename_root.'_small.jpg';
		//$filename_large = $filename_root.'_large.jpg';
		//$filename_original = $filename_root.'_original.jpg';
		
		write_file('images/'.$article_date.'/'.$filename_root, base64_decode($img_fixed));
		exit($this->attachments_model->add_photo($filename_root, $filename_root, $filename_root, $credit, $caption, $article_id));
	}
	
	public function ajax_suggest($table, $field)
	{
		// this general-purpose function is potentially wildly insecure.
		if(!($table == 'author' || $table == 'job' || $table == 'articletype' || $table == 'series')) exit("Disallowed.");
		
		$term = $this->input->get('term', true);
		$suggestions = $this->article_model->get_suggestions($table, $field, $term);
		exit(json_encode($suggestions));
	}
}
?>