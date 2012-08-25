<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('America/New_York');
		$this->load->model('attachments_model', '', TRUE);
	}
	
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function error()
	{
		$this->load->view('error');
	}
	
	public function about()
	{
		$data->footerdata->quote = $this->attachments_model->get_random_quote();
		$data->headerdata->date = date("Y-m-d");
		$this->load->view('about', $data);
	}
	
	public function subscribe()
	{
		$data->footerdata->quote = $this->attachments_model->get_random_quote();
		$data->headerdata->date = date("Y-m-d");
		$this->load->view('subscribe', $data);
	}
	
	public function advertise()
	{
		$data->footerdata->quote = $this->attachments_model->get_random_quote();
		$data->headerdata->date = date("Y-m-d");
		$this->load->view('advertise', $data);
	}
	
	public function search()
	{
		$data->footerdata->quote = $this->attachments_model->get_random_quote();
		$data->headerdata->date = date("Y-m-d");
		$this->load->view('search', $data);
	}
	
	public function phpinfo()
	{
		if(!bonus()) 
		{
			$this->load->view('error');
		}
		else
		{
			exit(phpinfo());
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */