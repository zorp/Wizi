<?php
	require_once('admin/util/topics.php');
	$topics	= new topics($dba);
	$list	= $topics ->getTopics();
	
	for($i=0,$n=count($list);$i<$n;$i++){
		echo '<a href="index.php?action=newstopicarchive&topicId='.$list[$i]["id"].'"><h1>'.$list[$i]["name"].' ('.count($myPage->getNewsFromTopic($list[$i]["id"])).' items)</h1></a>';
		echo '<p><a href="index.php?action=newstopicarchive&topicId='.$list[$i]["id"].'"><img src="media/'.$list[$i]["iconId"].'.'.$list[$i]["iconFormat"].'" border="0"></a></p>';
		echo '<p>'.$list[$i]["description"].'</p>';
	}//for
?>