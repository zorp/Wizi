<?php
/*
A FILE FULL OG FUNCTIONS THAT CAN BE USEFULL TO HAVE IN A WIZI INSTALL
*/


// This function finds the id of top page the current page is under
// usefull for instance if you need .active class on a top menu item at all times
function getTopActive ($page){
	global $dba;
	global $myPage;
	$toppages = $myPage->getTopPages();
	if ($page == 1) return $page;
	
	if (in_array($page,$toppages)){
		$topActive = $page;
		$parentCheck = true;
	}else{
		$currentParent = $page;
		$parentCheck = false;
		if (in_array($currentParent,$toppages)) $parentCheck = true;
	}   
	
	while ( $parentCheck != true ){
		if ( in_array($currentParent,$toppages) ){
			$topActive = $currentParent;
			$parentCheck = true;
		}else{
			$checkPage = new page( $dba, $currentParent );
			$currentParent = $checkPage->getParent( $currentParent );
			$parentCheck = false;
		}
	}
	return $topActive;
}

// This baby picks up the name of a document based on a given page id
function getDocName($page){
	global $dba;
	$sql = "SELECT name FROM ".$dba->prefix."tree WHERE id=".$page;
	return $dba->singleQuery($sql);
}

// same stuff as the topactive, but this one get's the second level active id instead.
function getSubActive ($page){
	global $dba;
	global $myPage;
	global $topActive;
	
	$subpages = $myPage->getSubPages($topActive);
	if ($page == $topActive) return false;
	
	if (in_array($page,$subpages)){
		$subActive = $page;
		$parentCheck = true;
	}else{
		$currentParent = $page;
		$parentCheck = false;
		if (in_array($currentParent,$subpages)) $parentCheck = true;
	}   
	
	while ( $parentCheck != true ){
		if ( in_array($currentParent,$subpages) ){
			$subActive = $currentParent;
			$parentCheck = true;
		}else{
			$checkPage = new page( $dba, $currentParent );
			$currentParent = $checkPage->getParent( $currentParent );
			$parentCheck = false;
		}
	}
	return $subActive;
}

// need to check if the current page has children that are published
function checkIfChildren( $page ){
	global $dba;
	$sql = "SELECT id FROM ".$dba->prefix."tree WHERE PARENT = ".$page." AND ( timepublish < NOW() OR timepublish IS NULL ) AND ( timeunpublish > NOW() OR timeunpublish IS NULL ) AND ( nav = 1 OR nav IS NULL ) ORDER BY position LIMIT 0,1";
	$result = $dba->singleQuery($sql);
	if ($result){
		return $result;
	}else{
		return $page;
	}
}

// THESE FUNCTIONS HAS BEEN ADD DIRECTLY INTO ROOT INDEX.PHP

// need to pick up some content from a specific page id
function getSinglePageContent( $pageID ){
	global $dba;
	$p = $dba->getPrefix();
	$sql = "SELECT content FROM ".$p."tree WHERE id = ".$pageID;
	return stripslashes($dba->singleQuery($sql));
}

// need to pick up some content including all includes/layout from a specific page id
function getSinglePageLayout($pageID){
	global $dba;
	global $layout;
	global $frontdev;
	$tempPage = new page( $dba, $pageID );
	$tempPage->getProperties();
	$templayout = new layout( $dba, $pageID, false, $frontdev );
	$templayout->layoutPath = "layouts/";
	$templayout->mediaPath  = "";
	$content = stripslashes($templayout->buildLayout($tempPage->properties["layout"]));
	return $content;
}

/*This function will add whatever you send through as repl in the end of a string that is longer than whatever limit you send through*/
function add_3dots($string,$repl,$limit){
	if(strlen($string) > $limit){
		 return substr_replace(strip_tags($string),$repl,$limit-strlen($repl));
	}else{
		 return $string;
	}//if else
}//function

//Validate if an email is correct
function validateEmail($email){
	if (!trim($email)) return false;
	if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
		return true;
	}else{
		return false;
	}
}//function

// function that takes the news array and turns it into a updated news array with only the news published under a given language
// this function is only used when working with at multilingual system
function makeNews($news, $lang){
	if (!count($news) && $lang) return false;

	global $dba;
	$temp = array();
	if( count($news) ){
		$tempCheck = false;
		for( $i=0;$i< count($news); $i++ ){
			$newslang = getLang($news[$i]["id"],$dba);
			if ($newslang == $lang){
				$tempCheck = true;
			}
		}
		$j = 0;
		for( $i=0;$i< count($news); $i++ ){
			$newslang = getLang($news[$i]["id"],$dba);
			if ($newslang == $lang && $tempCheck){
				$temp[$j]["id"] = $news[$i]["id"];
				$temp[$j]["name"] = $news[$i]["name"];
				$temp[$j]["title"] = $news[$i]["title"];
				$temp[$j]["heading"] = $news[$i]["heading"];
				$temp[$j]["description"] = $news[$i]["description"];
				$temp[$j]["headerimage"] = $news[$i]["headerimage"];
				$temp[$j]["summary"] = $news[$i]["summary"];
				$temp[$j]["edited"] = $news[$i]["edited"];
				$temp[$j]["creator"] = $news[$i]["creator"];
				$temp[$j]["newscontent"] = $news[$i]["newscontent"];
				$temp[$j]["newstitle"] = $news[$i]["newstitle"];
				$j++;
			}
		}
	}
	return $temp;
}//function
?>