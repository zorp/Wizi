<?php
	if( count($news) ){
		echo '<h1>Latest News</h1>';
		for( $i=0;$i< count($news); $i++ ){
			echo '<p>';
			echo '<img src="media/'.$news[$i]["topicIcon"].'" border="0" align="right">';
			echo $news[$i]["date"];
			echo '<br>';
			echo '<strong>'.((trim($news[$i]["title"]))?$news[$i]["title"]:$news[$i]["name"]).'</strong>';
			echo '<br>';
			echo (trim($news[$i]["description"]))?strip_tags($news[$i]["description"]):strip_tags($news[$i]["summary"]);
			echo '<br>';
			echo '<a href="index.php?page='.$news[$i]["id"].'">Read more</a>';
			echo '</p>';
		}//for
		echo '<p><a href="index.php?action=newstopics">View all news</a></p>';
	}//if
?>