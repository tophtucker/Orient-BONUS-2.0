<?php
class Tools extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('America/New_York');
		$this->load->model('issue_model', '', TRUE);
		$this->load->model('article_model', '', TRUE);
		$this->load->model('attachments_model', '', TRUE);
		$this->load->model('tools_model', '', TRUE);
	}
	
	public function index()
	{
		exit("No go!");
	}
	
	public function ajax_submittip()
	{
		$tip = $this->input->post('tip');
		$this->tools_model->submittip($tip);
		exit("true");
	}
	
	public function legacycommentexport()
	{
		$comment_id = 1;
	
		$article_query = $this->db->query("
			select count(*) as count, comment.article_date, comment.article_section, comment.article_priority, article.title 
			from `comment` 
			join article on article.date=comment.article_date and article.section_id=comment.article_section and article.priority=comment.article_priority
			where `deleted`='n' 
			group by `article_date`, `article_section`, `article_priority`");
		$articles = $article_query->result();
		
		$comment_query = $this->db->query("
			select * 
			from `comment` 
			where `deleted`='n' 
			order by `article_date`, `article_section`, `article_priority`");
		$comments = $comment_query->result();		
		
		echo '
			<?xml version="1.0" encoding="UTF-8"?>
			<rss version="2.0"
			  xmlns:content="http://purl.org/rss/1.0/modules/content/"
			  xmlns:dsq="http://www.disqus.com/"
			  xmlns:dc="http://purl.org/dc/elements/1.1/"
			  xmlns:wp="http://wordpress.org/export/1.0/"
			>
			  <channel>
			';
		
		foreach($articles as $article) {
			
			$url_title = url_title($article->title, 'underscore', TRUE);
			
			echo '
				<item>
				  <!-- title of article -->
				  <title>'.$article->title.'</title>
				  <!-- absolute URI to article -->
				  <link>http://orient.bowdoin.edu/orient/article.php?date='.$article->article_date.'&section='.$article->article_section.'&id='.$article->article_priority.'</link>
				  <!-- thread body; use cdata; html allowed (though will be formatted to DISQUS specs) -->
				  <content:encoded><![CDATA[Hello world]]></content:encoded>
				  <!-- value used within disqus_identifier; usually internal identifier of article -->
				  <dsq:thread_identifier>'.$url_title.'</dsq:thread_identifier>
				  <!-- creation date of thread (article), in GMT. Must be YYYY-MM-DD HH:MM:SS 24-hour format. -->
				  <wp:post_date_gmt>'.$article->article_date.' 00:00:00</wp:post_date_gmt>
				  <!-- open/closed values are acceptable -->
				  <wp:comment_status>open</wp:comment_status>
				  ';
				  
			foreach($comments as $comment) {
				
				if($comment->article_date     == $article->article_date     && 
				   $comment->article_section  == $article->article_section  &&
				   $comment->article_priority == $article->article_priority)
				{
					
					echo '
					      <wp:comment>
							<!-- sso only; see docs -->
							<dsq:remote>
							  <!-- unique internal identifier; username, user id, etc. -->
							  <dsq:id>'.$comment->username.'</dsq:id>
							  <!-- avatar -->
							  <dsq:avatar></dsq:avatar>
							</dsq:remote>
							<!-- internal id of comment -->
							<wp:comment_id>'.$comment_id.'</wp:comment_id>
							<!-- author display name -->
							<wp:comment_author>'.$comment->realname.'</wp:comment_author>
							<!-- author email address -->
							<wp:comment_author_email>'.$comment->email.'</wp:comment_author_email>
							<!-- author url, optional -->
							<wp:comment_author_url></wp:comment_author_url>
							<!-- author ip address -->
							<wp:comment_author_IP>93.48.67.119</wp:comment_author_IP>
							<!-- comment datetime, in GMT. Must be YYYY-MM-DD HH:MM:SS 24-hour format. -->
							<wp:comment_date_gmt>'.$comment->comment_date.'</wp:comment_date_gmt>
							<!-- comment body; use cdata; html allowed (though will be formatted to DISQUS specs) -->
							<wp:comment_content><![CDATA['.$comment->comment.']]></wp:comment_content>
							<!-- is this comment approved? 0/1 -->
							<wp:comment_approved>1</wp:comment_approved>
							<!-- parent id (match up with wp:comment_id) -->
							<wp:comment_parent>0</wp:comment_parent>
						  </wp:comment>
						';
						
					$comment_id++;
					
				}
			
			}
			
			echo '
				</item>
				';
				
			

				
		}
		
		echo '
			  </channel>
			</rss>
			';
		
	}

}
?>