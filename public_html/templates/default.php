<?php
// CREATE DOCTITEL
if (!$docTitle){
	$docTitle = 'Wizi CMS';
	if($historyPath) {
		foreach( $historyPath as $key => $value ) {
			if($key > 1) $docTitle.= ' - '.$value;
		}
	
		$docTitle.= ' - '.$docName;
	}
}
else{
	$docTitle.= ' - Wizi CMS';
}
?>
<!--
***********************************************************
*                                                         *
* THIS WEBSITE IS BASED ON WIZI CONTENT MANAGEMENT SYSTEM *
*                                                         *
* WIZI is developed and maintained by Verk ApS            *
* Visit http://www.verk.dk for more information.          *
*                                                         *
***********************************************************
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?php echo $docTitle; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Description" content="<?php echo $docDescription; ?>">
	<meta http-equiv="Keywords" content="<?php echo $docKeyword; ?>">
	<link href="styles/shared.css" rel="stylesheet" rev="stylesheet" type="text/css">
	<link href="styles/styles.css" rel="stylesheet" rev="stylesheet" type="text/css">
	<script type="text/javascript" src="scripts/global_frontend.js"></script>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1><a href="index.php">Wizi CMS development site</a></h1>
		</div>
		<div id="leftbox">
			<div id="navigation">
				<h1>Top Menu:</h1>
				<?php
					for ($i=0,$n=count($topMenu);$i<$n;$i++){
						echo '<li><a href="?page='.$topMenu[$i]["id"].'">'.$topMenu[$i]["name"].'</a></li>';
					}
				?>
				<h1>Sibling Menu:</h1>
				<?php
					for ($i=0,$n=count($siblingMenu);$i<$n;$i++){
						echo '<li><a href="?page='.$siblingMenu[$i]["id"].'">'.$siblingMenu[$i]["name"].'</a></li>';
					}
				?>
				<h1>Child Menu:</h1>
				<?php
					for ($i=0,$n=count($childMenu);$i<$n;$i++){
						echo '<li><a href="?page='.$childMenu[$i]["id"].'">'.$childMenu[$i]["name"].'</a></li>';
					}
				?>
			</div>
		</div>
		<div id="middlebox">
			<div id="content">
				<?php
					if(sizeof($historyPath) > 0) {
						echo '<p>You are here: ';
						foreach( $historyPath as $key => $value ) {
							echo '<a href="index.php?page='.$key.'">'.$value.'</a> &gt; ';
						}
					
						echo '<strong>'.$docName.'</strong></p>';
					}
				?>
				<?php
					echo $docContent;
					if ($showcomment == 'y') require_once( "comment.php" );
					if( $includeTemplate ) require_once( $includeTemplate );
					echo $stats;
				?>
			</div>
		</div>
		<div id="rightbox">
			<?php
				require_once("login_registration/loginform.php");
				if (count($news)) require_once("news.php");
			?>
		</div>
		<div id="footer"><a href="?action=sitemap">Sitemap</a> | <a href="?action=search">Search</a> | <a href="javascript:printThis(<?=$page?>,'<?=$action?>','<?=$query?>');">Print</a> | <a href="?action=newsfeed">Newsfeeds</a></div>
	</div>
</body>
</html>