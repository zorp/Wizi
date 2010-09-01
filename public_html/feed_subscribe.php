<?php
require_once('admin/util/newsFeeds.php');
require_once('admin/util/rss_parser.php');
require_once('admin/util/rdf_parser.php');

if( !$user->isLogged() ) die("You have to be logged in to subscribe to feeds");

if ( $_POST["submitted"] ){
	if (!$feed) $feed = $POST["feed"];
	$user->subscribeNewsfeed( $feed,$user->id );
	$message = "Changes saved";
}//if

$feeds						= new newsFeeds( new dba() );
$newsfeeds				= $feeds->getFeeds();
$subscribedFeeds	= $user->getNewsfeedSubscribtion($user->id);

echo '<h1>Select which newsfeeds to show</h1>';
echo ($message)?'<p style="color:green;">'.$message.'</p>':'';

echo '<form name="feedsubscribe" action="index.php?action=newsfeedsub" method="post">';
echo '<input type="hidden" name="submitted" value="1">';

for( $i = 0; $i < count( $newsfeeds ); $i++ ){
	echo '<p><input type="checkbox" name="feed[]" value="'.$newsfeeds[$i]["id"].'" '.(($subscribedFeeds && in_array($newsfeeds[$i]["id"],$subscribedFeeds))?'checked="true"':'').'>&nbsp;'.$newsfeeds[$i]["name"].'</p>';
}//for

echo '<p><input type="submit" name="subscribe" value="Save"></p>';
echo '</form>';
?>