<?php
class Author extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('America/New_York');
		$this->load->model('author_model','',TRUE);
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
		$author = $this->author_model->get_author($id);
		
		if(!$author) 
		{
			$this->error("No such author exists.");
		}
		else
		{
			$data->footerdata->quote = $this->attachments_model->get_random_quote();
			$data->headerdata->date = date("Y-m-d");
			$data->author = $author;
			
			$data->articles = $this->article_model->get_articles_by_date(date("Y-m-d"), false, false, false, false, $id);
			$data->popular = $this->article_model->get_popular_articles_by_date(date("Y-m-d"), false, '3',   false, $id, false);
			$data->series = $this->author_model->get_author_series($id);
			$data->longreads = $this->author_model->get_author_longreads($id);
			$data->collaborators = $this->author_model->get_author_collaborators($id);
						
			$this->load->view('author', $data);
		}
	}
	
}
?>