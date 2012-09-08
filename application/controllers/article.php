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
			// add one to article views if not logged in
			if(!bonus()) $this->article_model->increment_article_views($id);
			
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
		
		$title 		= trim(urldecode($this->input->post("title")));
		$subtitle 	= trim(urldecode($this->input->post("subtitle")));
		$author 	= trim(urldecode($this->input->post("author")));
		$authorjob 	= trim(urldecode($this->input->post("authorjob")));
		$body 		= trim(urldecode($this->input->post("body")));
		
		$published = ($this->input->post("published") == 'true' ? '1' : '0');
		$featured = ($this->input->post("featured") == 'true' ? '1' : '0');
		
		$data = array(
			'title' 		=> $title,
			'subhead' 		=> $subtitle,
			'pullquote'		=> trim(urldecode($this->input->post("pullquote"))),
			'volume' 		=> trim(urldecode($this->input->post("volume"))),
			'issue_number' 	=> trim(urldecode($this->input->post("issue_number"))),
			'section_id'	=> trim(urldecode($this->input->post("section_id"))),
			'priority'		=> trim(urldecode($this->input->post("priority"))),
			'published'		=> $published,
			'featured'		=> $featured,
			);
		$articlesuccess = $this->article_model->edit_article($id, $data);
		
		if($body) 
		{
			$bodysuccess = $this->article_model->add_articlebody_version($id, $body, userid());
		}
		
		$series = trim(urldecode($this->input->post("series")));
		$seriessuccess = true;
		if(strlen($series) > 1)
		{
			$seriessuccess = $this->article_model->add_article_series($id, $series);
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
		
		if($articlesuccess && $authorsuccess && $seriessuccess) 
		{
			exit("Success!");
		}
		else 
		{
			exit("Failure.");
		}
	}
	
	// for forwarding from old site, which doesn't have article_ids
	public function triplet($date, $section_id, $priority)
	{
		$article_id = $this->article_model->get_id_by_triplet($date, $section_id, $priority);
		redirect('/article/'.$article_id, 'refresh');
	}
	
	/**
	  * Currently only supports jpg.
	  **/
	public function ajax_add_photo($article_date, $article_id)
	{
		if(!bonus()) exit("Permission denied.");
		
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
		
		// prepare names
		$filename_root = $article_id.'_'.$article_photo_number;
		$filename_original = $filename_root.'.jpg';
		$filename_small = $filename_root.'_small.jpg'; //width: 400px
		$filename_large = $filename_root.'_large.jpg'; //width: 1000px
		
		// write full-size image
		write_file('images/'.$article_date.'/'.$filename_original, base64_decode($img_fixed));
		
		// resize to small
		$img_config['image_library']	= 'gd2';
		$img_config['source_image']		= 'images/'.$article_date.'/'.$filename_original;
		$img_config['new_image'] 		= $filename_small;
		$img_config['maintain_ratio']	= TRUE;
		$img_config['width'] 			= 400;
		$img_config['height']			= 400;
		$this->load->library('image_lib', $img_config);
		$this->image_lib->resize();
		
		// resize to large
		$img_config2['image_library']	= 'gd2';
		$img_config2['source_image']	= 'images/'.$article_date.'/'.$filename_original;
		$img_config2['new_image'] 		= $filename_large;
		$img_config2['maintain_ratio']	= TRUE;
		$img_config2['width'] 			= 1000;
		$img_config2['height']			= 1000;
		$this->image_lib->clear(); // gotta clear the library config in-between operations
		$this->image_lib->initialize($img_config2);
		$this->image_lib->resize();
		
		// add photo to database and return the response to the ajax request
		exit($this->attachments_model->add_photo($filename_small, $filename_large, $filename_original, $credit, $caption, $article_id));
	}
	
	public function ajax_remove_photos($article_id)
	{
		if(!bonus()) exit("Permission denied.");
		
		exit($this->attachments_model->remove_article_photos($article_id));
	}
	
	public function ajax_delete_article($article_id)
	{
		if(!bonus()) exit("Permission denied.");
		
		//exit("Deleting articles doesn't work yet! Ask Toph to do it.");
		exit($this->article_model->delete_article($article_id));
	}
	
	public function ajax_suggest($table, $field)
	{
		if(!bonus()) exit("Permission denied.");

		// this general-purpose function is potentially wildly insecure.
		if(!($table == 'author' || $table == 'job' || $table == 'articletype' || $table == 'series')) exit("Disallowed.");
		
		$term = $this->input->get('term', true);
		$suggestions = $this->article_model->get_suggestions($table, $field, $term);
		exit(json_encode($suggestions));
	}
}
?>