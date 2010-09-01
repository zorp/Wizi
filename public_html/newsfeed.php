<?php
require_once('admin/util/newsFeeds.php');
require_once('admin/util/rss_parser.php');
require_once('admin/util/rdf_parser.php');

$feeds = new newsFeeds( new dba() );

if( $user->isLogged() ){
	$newsfeeds = $feeds->getUserNews($user->id);
}else{
	$newsfeeds = $feeds->getNews();
}//if else

echo '<h1>News Feeds</h1>';

if ($user->isLogged()) echo '<p><a href="index.php?action=newsfeedsub">Select which newsfeeds to show, when logged in.</a></p>';

for ($i=0,$n=count($newsfeeds); $i<$n; $i++){
	$cache = $newsfeeds[$i]["cache"];
	$itemcount = count($cache["ITEM"]);
	
	echo '<p><a href="JavaScript:showFeed('.$newsfeeds[$i]["id"].');">'.$newsfeeds[$i]["name"].' ('.$itemcount.' items)</a></p>';
	
	echo '<div id="'.$newsfeeds[$i]["id"].'" style="display:none;">';
	for( $j = 0; $j < count( $cache["ITEM"] ); $j++ ){
		echo '<p><a href="'.$cache["ITEM"][$j]["LINK"].'" target="_blank">'.$cache["ITEM"][$j]["TITLE"].'</a>';
		echo ($cache["ITEM"][$j]["DESCRIPTION"])?'<br>'.$cache["ITEM"][$j]["DESCRIPTION"]:'';
		echo '</p>';
	}//for
	echo '</div>';
}//for
?>