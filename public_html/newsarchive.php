<?php
	$topicId = $_GET["topicId"];
	$newsarchive = $myPage->getNewsFromTopic($topicId);

	echo '<h1>'.$newsarchive[0]["topicName"].'</h1>';
	
	echo '<img src="media/'.$newsarchive[0]["topicIcon"].'" border="0">';
	
	for($i=0,$n=count($newsarchive);$i<$n;$i++){
		echo '<p>';
		echo $newsarchive[$i]["date"];
		echo '<br>';
		echo '<strong>'.((trim($newsarchive[$i]["title"]))?$newsarchive[$i]["title"]:$newsarchive[$i]["name"]).'</strong>';
		echo '<br>';
		echo (trim($newsarchive[$i]["description"]))?strip_tags($newsarchive[$i]["description"]):strip_tags($newsarchive[$i]["summary"]);
		echo '<br>';
		echo '<a href="index.php?page='.$newsarchive[$i]["id"].'">Read more</a>';
		echo '</p>';
	}//for
	
	echo '<p><a href="index.php?action=newstopics">Back to all news</a></p>';
?>