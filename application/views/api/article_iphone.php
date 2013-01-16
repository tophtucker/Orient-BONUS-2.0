<?php

include("dbconnect.php");
include("util.php");
include("getcurrentdate.php");

$date = mysql_real_escape_string($_GET['date']);
$section = mysql_real_escape_string($_GET['section']);
$priority = mysql_real_escape_string($_GET['id']);
$authorID =  mysql_real_escape_string($_GET['authorid']);
$seriesID = mysql_real_escape_string($_GET['seriesid']);
$featureSection = mysql_real_escape_string($_GET['featuresection']);

if(strcmp($date, "") == 0)
	$date = $currentDate;

if(strcmp($section, "") == 0)
	$section = 1;

if(strcmp($priority, "") == 0)
	$priority = 1;

include("issuequery.php");
include("template/functions.php");

// First update the 'read' count of this article.
// Check to make sure it's "ready", and that this actually qualifies as a view.

$commentQuery = 
	"SELECT
		username,
		email,
		comment,
		id,
		comment_date,
		realname
	FROM
		comment
	WHERE
		deleted='n' AND 
		article_date = '$date' AND 
		article_section = '$section' AND 
		article_priority = '$priority'
	ORDER BY
		id ASC
	";
	$comments = mysql_query($commentQuery);	
	$numComments = mysql_num_rows($comments);

$readyQuery = 
	"SELECT
		issue.ready
	FROM
		article,
		issue
	WHERE
		article.date = '$date' AND
		article.section_id = '$section' AND
		article.priority = '$priority' AND
		issue.issue_date = article.date
	";
if (mysql_result(mysql_query($readyQuery), 0, 'ready') == 'y') {
	$countPage = 
	"UPDATE
		article
	SET
		article.views = article.views + 1
	WHERE
		article.date = '$date'
		AND article.section_id = '$section'
		AND article.priority = '$priority'
	";
	mysql_query($countPage);
	// Now add to Bowdoin views
	if (strpos(gethostbyaddr($_SERVER['REMOTE_ADDR']), "bowdoin") !== false) {
		$countPage = 
		"UPDATE
			article
		SET
			bowdoin_views = bowdoin_views + 1
		WHERE
			date = '$date'
			AND section_id = '$section'
			AND priority = '$priority'
		";
		mysql_query($countPage);
	}
}

// This grabs the information about the article.  More queries to come.
$articleQuery = 
	"SELECT
		s.name AS sectionname,
		ar.priority,
		ar.author1,		
		a1.name AS author1name,
		a1.photo AS author1photo,
		ar.author2,
		a2.name AS author2name,
		a2.photo AS author2photo,
		ar.author3,
		a3.name AS author3name,
		a3.photo AS author3photo,		
		j.name AS jobname,		
		ar.title AS title,
		ar.subhead,		
		ar.text,
		at.id AS typenumber,
		at.name AS type,
		series.name AS series,
		series.id AS series_id,
		s.order_flag
	FROM 
		section s,
		article ar,
		author a1,
		author a2,
		author a3,
		job j,
		articletype at,
		series
	WHERE
		ar.author_job = j.id AND
		ar.section_id = s.id AND
		ar.author1 = a1.id AND
		ar.author2 = a2.id AND 
		ar.author3 = a3.id AND
		ar.type = at.id AND
		ar.series = series.id AND
		ar.date = '$date' AND
		ar.section_id = '$section' AND 
		ar.priority = '$priority'
	";

$result = mysql_query ($articleQuery);
if ($row = mysql_fetch_array($result)) {	
	$sectionName = $row["sectionname"];
	$articleAuthor1 = $row["author1name"];
	$articleAuthor2 = $row["author2name"];
	$articleAuthor3 = $row["author3name"];
	$articleAuthorID1 = $row["author1"];
	$articleAuthorID2 = $row["author2"];
	$articleAuthorID3 = $row["author3"];
	$articleAuthorPhoto1 = $row["author1photo"];
	$articleAuthorPhoto2 = $row["author2photo"];
	$articleAuthorPhoto3 = $row["author3photo"];
	$articleJob = $row["jobname"];
	$articleTypeNumber = $row["typenumber"];
	$articleType = $row["type"];
	$articleSubhead = $row["subhead"];	
	$articleTitle = $row["title"];	
	$articleText = $row["text"];
	$articleSeries = $row["series"];
	$seriesID = $row["series_id"];
	$orderFlag = $row["order_flag"];
} else {
	header("Location: index.php");
	exit;
}

$title = $articleTitle." - The Bowdoin Orient";

global $title, $volumeNumber, $issueNumber, $date, $section, $priority, $articleDate;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name = "viewport" content = "initial-scale = 1.0, user-scalable = no">
	<title><?php echo $title; ?></title>
	
	<link rel="alternate" type="application/rss+xml" title="The Bowdoin Orient" href="http://orient.bowdoin.edu/orient/rss.php" />
	<link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico" />
	
  <!-- Framework CSS -->
	<link rel="stylesheet" href="css/blueprint/screen.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="css/blueprint/print.css" type="text/css" media="print">
	
	<!-- Import fancy-type plugin for the sample page. -->
	<link rel="stylesheet" href="css/blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">
	<link rel='stylesheet' href='css/nyroModal.css' type='text/css' media='screen, projection'>
	<link rel='stylesheet' href='css/lightbox.css' type='text/css' media='screen, projection'>
	<link rel="stylesheet" href="css/orient.css" type="text/css" media="screen, projection">

	<style>	
	.iphonewrapper {
		width:300px;
		padding:10px;
	}
	
	#comments {
		width:300px;
		padding:0;
		padding-left:0;
		margin:0;
	}
	
	.comment {
		width:290px;
		padding:5px;
		margin:0;
	}
	
	.articletitle {
		font-size:150%;
	}
	</style>
	
	<script type='text/javascript' src='js/jquery.js'></script>
	<script type="text/javascript" charset="utf-8">
		function showComments() {
			$("#view-comments").hide("normal");
			$("#comments").show("fast");
		}
		
		function submitComment() {
			username = $("#username").val();
			password = $("#password").val();
			comment = $("#comment").val();
			var url = "ajaxaddcomment.php";
			var ardate = '<?php echo $date; ?>';
			var section = '<?php echo $section; ?>';
			var priority = '<?php echo $priority; ?>';
			var data = {
				"username": username,
				"password": password,
				"comment": comment,
				"id": priority,
				"section": section,
				"date": ardate
			};
			$.post(url, data, commentSubmitted, "text");
			showComments();
		}
		
		function commentSubmitted(data) {
			if (data.substring(0, 9) == "Success: ") {
				// If they've done more than one comment, remove the 'edit' link on the older one, and make it normal.
				/*
				if ($("#new-comment")) {
					$("#new-comment").removeClass('newcomment').attr("id","");
					$("#editlink").text("Report").attr("onclick",'reportComment(this); return false;');
				}
				 */
				var numbers = data.substring(9);
				commentID = numbers.substring(0,numbers.indexOf(","));
				commentSecret = numbers.substring(numbers.indexOf(",") + 1);
				var commentDiv = document.createElement("div");
				commentDiv.setAttribute("class", "comment hide newcomment");
				commentDiv.setAttribute("id", "new-comment");
				commentDiv.setAttribute("commentid", commentID);
				commentDiv.setAttribute("secret", commentSecret);
				var user = "<p class='bottom'>" + username + "<\/p>";
				user = "<p class='bottom edit'><strong>" + username + "<\/strong> (just now)<\/p>";
				var content = "<p class='bottom edit_area'>" + comment + "<\/p>";
				// "<span class='edit-text'><br />Double-click to edit text<\/span><\/p>";
				commentDiv.innerHTML = user + content;
				hideError();
				$("#nocomments").hide("fast");
				$("#comments").append(commentDiv);
				$("#new-comment").show("normal");
				$("#comment").val("");
				editable();
			} else {
				$("#comment-error").text(data);
				$("#comment-error").show("normal");
			}
		}
		
		function hideError() {
			$("#comment-error").hide("normal");
		}
	</script>
	
	
</head>

<body style="background-color:#FFFFFF; background-image:none;">
		
	<div class="iphonewrapper">
		<h3 class='bottom'><?php echo $articleType; ?></h3>
		<?php 
			// If it's part of a series, link to the entire series.
			if ($articleSeries) { ?>
				<p style="margin:0"><?php echo strtoupper($articleSeries); ?></p>
			<?php } ?>
		<h2 class='top bottom' style="margin:0 0 5px 0;"><strong><?php echo $articleTitle; ?></strong></h2>
		<?php if ($articleSubhead) { 
			echo "<h2 class='top bottom' style='font-size:150%'>" . $articleSubhead . "</h2><br/>";
		}
		?>
		
		<div class='articleauthor'><?php echo authorsLinksMobile($articleAuthor1, $articleAuthor2, $articleAuthor3); ?></div>
		<p style="margin-bottom:6px"><?php echo $articleDate; ?></p>
		
		<div class='articletext'>
			<?php
			// Displays photos / documents / embeddables.
			// echoMedia($date, $section, $priority);
			?>
			
			<?php echoMobilePhoto($date, $section, $priority); ?>
					
			<?php echo strip_tags($articleText, '<p><b><i><u><strong><em>'); ?>
			
			<div class='comments-div'>
				<hr />
				<a name='comments'></a>
				<h3>Comments</h3>
				
				<?php if ($numComments == 0) { ?>
					<div id='nocomments'>There are no comments for this article yet.</div>
				<?php } else { ?>
					<div id='comment-disclaimer'>
						Please note that comments do not in any way reflect the opinion of <em>The Orient</em>, nor of Bowdoin College.
						<br />
						<a href='#' id='view-comments' onclick='showComments(); return false;'>Click here to view all comments (<?php echo $numComments; ?>)</a>
						<br />
					</div>
				<?php } ?>
				<div id='comments'>
					
					<?php
						$counter = 0;
						while ($row = mysql_fetch_array($comments)) {
							$counter = $counter % 2;
							echo "<div class='comment comment$counter' commentid='" . $row['id'] . "'>";
								echo "<p class='bottom edit'>";
								echo "<strong>".$row['realname']."</strong>";
								$commentDate = strtotime($row['comment_date']);
								echo "<span class='commentdate'>" . date(" (M j, Y, g:i a)", $commentDate) . "</span>";
								echo "</p>";
								echo "<div class='bottom edit_area'><p>" . stripslashes(implode("</p><p>", explode("\n", $row['comment']))) . "</p></div>";
							echo "</div>";
							spacer();
							$counter++;
						}
					?>
				</div>
				
				<?php spacer(); ?>
				<p id='comment-error' class='error'></p>
				<p id='comment-success' class='success'></p>
				<label>Username: <input type='text' name='username' id='username'/></label><br/>
				<label>Password: <input type='password' name='password' id='password'/></label><br/>
				<label><textarea name='comment' id='comment' style="width: 290px"></textarea></label>
				<button id='submitComment' onclick='submitComment();'>Submit</button>
				
			</div>
		</div>
	</div>
					
<?php
include("template/bottom.php");
?>