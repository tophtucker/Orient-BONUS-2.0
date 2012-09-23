<?php
class Rss extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('America/New_York');
		$this->load->model('author_model','',TRUE);
		$this->load->model('series_model','',TRUE);
		$this->load->model('article_model', '', TRUE);
		$this->load->model('attachments_model', '', TRUE);
	}
	
	public function error($message = '')
	{
		$data->message = $message;
		$this->load->view('error', $data);
	}
	
	public function index()
	{
		$data->articles = $this->article_model->get_articles_by_date(date("Y-m-d"), false, false, 50);
		$data->title = "The Bowdoin Orient";
		$data->url = "http://bowdoinorient.com/";
		$data->description = "The nation’s oldest continuously published college weekly";
		$this->load->view('rss', $data);
	}
	
	public function section($id)
	{
		switch ($id) {
			case 1:
				$section = "News";
				break;
			case 2:
				$section = "Opinion";
				break;
			case 3:
				$section = "Features";
				break;
			case 4:
				$section = "Arts & Entertainment";
				break;
			case 5:
				$section = "Sports";
				break;
			default:
				$this->error("No such section exists.");
		}
		
		$data->articles = $this->article_model->get_articles_by_date(date("Y-m-d"), false, $id, 50);
		$data->title = $section." - The Bowdoin Orient";
		$data->url = "http://bowdoinorient.com/#".$section;
		$data->description = "The nation’s oldest continuously published college weekly";
		$this->load->view('rss', $data);
	}
		
	public function author($id)
	{
		$author = $this->author_model->get_author($id);
		
		if(!$author) 
		{
			$this->error("No such author exists.");
		}
		else
		{
			$data->articles = $this->article_model->get_articles_by_date(date("Y-m-d"), false, false, false, false, $id);
			$data->title = $author->name." - The Bowdoin Orient";
			$data->url = "http://bowdoinorient.com/author/".$id;
			$data->description = $author->bio;
			$this->load->view('rss', $data);
		}
	}
	
	public function series($id)
	{
		$series = $this->series_model->get_series($id);
		
		if(!$series) 
		{
			$this->error("No such series exists.");
		}
		else
		{
			$data->articles = $this->article_model->get_articles_by_date(date("Y-m-d"), false, false, false, false, false, $id);
			$data->title = $series->name." - The Bowdoin Orient";
			$data->url = "http://bowdoinorient.com/series/".$id;
			$data->description = $series->description;
			$this->load->view('rss', $data);
		}
	}
	
}
?>