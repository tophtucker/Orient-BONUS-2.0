<?php
class Random extends CI_Controller {

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
		$random = $this->article_model->get_random();
		redirect('article/'.$random->id, 'refresh');
	}

}
?>