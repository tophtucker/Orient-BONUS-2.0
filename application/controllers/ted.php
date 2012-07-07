<?php
class Ted extends CI_Controller {

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
		$this->db->order_by("id", "random");
		$this->db->limit("1");
		$query = $this->db->get("ted");
		$row = $query->row();
		$message = $row->message;
		$data->message = $message;
		$this->load->view('ted', $data);
	}
		
	public function add()
	{
		$message = $this->input->post("message");
		$data->message = $message;
		$this->db->insert("ted", $data);
		exit("Message saved!");
	}
	

}
?>