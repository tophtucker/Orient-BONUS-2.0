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
			$type = $this->article_model->get_article_type($id);
			$series = $this->article_model->get_article_series($id);
			$authors = $this->article_model->get_article_authors($id);
			$photos = $this->attachments_model->get_photos($id);
			
			// get random quote
			$data->footerdata->quote = $this->attachments_model->get_random_quote();
			
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
		redirect('/article/view/'.$article_id, 'refresh');
		
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
		
		$offset = $css_offset + $jpg_offset;
		$offset_tail = $css_offset_tail;
		$strlen_offset = $offset + $offset_tail;
		
		$img = substr($this->input->post("img"), $offset, strlen($this->input->post("img"))-($strlen_offset));
		$credit = $this->input->post("credit");
		$caption = $this->input->post("caption");
		
		// bug: "When Base64 gets POSTed, all pluses are interpreted as spaces."
		// this corrects for it.
		$img_fixed = str_replace(' ','+',$img);
		
		write_file('images/'.$article_date.'/'.$article_id.'_fullsize.jpg', base64_decode($img_fixed));
		return $this->attachments_model->add_photo($article_id.'_fullsize.jpg', $article_id.'_fullsize.jpg', $credit, $caption, $article_id);
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