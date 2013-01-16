<?php
class API extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('America/New_York');
		$this->load->model('issue_model', '', TRUE);
		$this->load->model('article_model', '', TRUE);
		$this->load->model('attachments_model', '', TRUE);
		$this->load->model('series_model', '', TRUE);
		$this->load->model('author_model', '', TRUE);
		$this->load->model('tools_model', '', TRUE);
		$this->load->model('api_model', '', TRUE);
	}
	
	public function index()
	{
		exit("No go!");
	}
	
	public function article_iphone($date, $section_id, $priority)
	{
		$article_id = $this->article_model->get_id_by_triplet($date, $section_id, $priority);		
		redirect('/article/'.$article_id, 'location');
	}
	
	public function xml_articlelist()
	{
		$data = $this->api_model->xml_articlelist($_GET["issue_date"], $_GET["section"]);
		header("Content-Type: application/xml; charset=UTF-8");
		$this->load->view('api/xml_articlelist', $data);
	}
	
	public function xml_issuelist()
	{
		$data->volume_arabic = $_GET["volume"];
		
		$issue_query = $this->db->query("
			select
				date_format(issue.issue_date, '%b %e, %Y') as date,
				issue.issue_number,
				volume.arabic
			from issue
			inner join volume on issue.volume = volume.arabic
			where volume.arabic = '".$data->volume_arabic."'
		");
		$data->issues = $issue_query->result();
		
		header("Content-Type: application/xml; charset=UTF-8");
		$this->load->view('api/xml_issuelist', $data);
	}
	
	public function xml_volumelist()
	{
		$volumes_query = $this->db->query("
			select `id`, `arabic` from volume
		");
		$data->volumes = $volumes_query->result();
		
		header("Content-Type: application/xml; charset=UTF-8");
		$this->load->view('api/xml_volumelist', $data);
	}
	
}
?>