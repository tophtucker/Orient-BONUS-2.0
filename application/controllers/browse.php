<?php
class Browse extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('America/New_York');
		$this->load->model('issue_model', '', TRUE);
		$this->load->model('article_model', '', TRUE);
		$this->load->model('attachments_model', '', TRUE);
	}
	
	public function index()
	{
		$this->date();
	}
	
	public function error($message = '')
	{
		$data->message = $message;
		$this->load->view('error', $data);
	}
	
	public function date($date = '')
	{
		// just for testing
		//$this->output->enable_profiler(TRUE);
		
		// if no date specified, use current date
		if(!$date) $date = date("Y-m-d");
		$date_week_ago = date("Y-m-d", time()-(7*24*60*60));
		
		// get last updated date, PRIOR TO $date requested.
		$last_updated = $this->article_model->get_last_updated($date);
		$last_updated_week_ago = date("Y-m-d", strtotime($last_updated)-(7*24*60*60));
		$last_updated_fivemonths_ago = date("Y-m-d", strtotime($last_updated)-(5*4*7*24*60*60));
		
		// get latest issue <= date specified
		$issue = $this->issue_model->get_latest_issue($date);
				
		if(!$issue)
		{
			$this->error();
		}
		else
		{
			$volume = $issue->volume;
			$issue_number = $issue->issue_number;
			
			// get adjacent issues (for next/prev buttons)
			$nextissue = $this->issue_model->get_adjacent_issue($volume, $issue_number, 1);
			$previssue = $this->issue_model->get_adjacent_issue($volume, $issue_number, -1);
			
			// featured articles for carousel
			$featured = $this->article_model->get_articles_by_date($date, false, false, '10', true);
			
			// popular articles
			$popular = $this->article_model->get_popular_articles_by_date($last_updated, $last_updated_week_ago, $limit = '10');
			if(count($popular) < 10)
			{
				$popular = $this->article_model->get_popular_articles_by_date($last_updated, $last_updated_fivemonths_ago, $limit = '10');
			}
			
			// get random quote
			$data->footerdata->quote = $this->attachments_model->get_random_quote();
			
			// get sections
			$sections = $this->issue_model->get_sections();
			
			foreach($sections as $section)
			{
				// get articles
				$articles[$section->name] = $this->article_model->get_articles_by_date($date, $date_week_ago, $section->id);
				if(count($articles[$section->name]) < 10) 
				{
					$articles[$section->name] = $this->article_model->get_articles_by_date($date, false, $section->id, 10);
				}
			}
			
			// load data, view
			
			$data->headerdata->date = $date;
			$data->headerdata->volume = $volume;
			$data->headerdata->issue_number = $issue_number;
			$data->headerdata->issue = $issue;
			
			$data->date = $date;
			$data->issue = $issue;
			$data->nextissue = $nextissue;
			$data->previssue = $previssue;
			$data->featured = $featured;
			$data->popular = $popular;
			$data->sections = $sections;
			$data->articles = $articles;	
			$this->load->view('browse', $data);
		}
	}
	
	public function issue($volume,$issue_number)
	{
		// get issue
		$issue = $this->issue_model->get_issue($volume, $issue_number);
		
		if(!$issue)
		{
			$this->error();
		}
		else
		{
			redirect('browse/'.$issue->issue_date, 'refresh');
			
			/*
			// get adjacent issues (for next/prev buttons)
			$nextissue = $this->issue_model->get_adjacent_issue($volume, $issue_number, 1);
			$previssue = $this->issue_model->get_adjacent_issue($volume, $issue_number, -1);
			
			// popular articles
			$popular = $this->article_model->get_popular_articles($volume, $issue_number, '10'); 
			
			// get random quote
			$data->footerdata->quote = $this->attachments_model->get_random_quote();
			
			// get sections
			$sections = $this->issue_model->get_sections();
			
			foreach($sections as $section)
			{
				// get articles
				$articles[$section->name] = $this->article_model->get_articles($volume, $issue_number, $section->id);
			}
			
			// load data, view
			$data->issue = $issue;
			$data->nextissue = $nextissue;
			$data->previssue = $previssue;
			$data->popular = $popular;
			$data->sections = $sections;
			$data->articles = $articles;	
			$this->load->view('browse', $data);
			*/
		}
	}
}
?>