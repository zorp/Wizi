<?
require_once("admin/util/dba.php");
require_once("admin/util/document.php");

$doc = new document( new dba() );
$numberOfResults = 20;

if( !$query ) $query = $_GET["query"];
if( !$query ) $query = $_POST["query"];
if( !$from  ) $from = $_POST["from"];
if( !$from  ) $from = $_GET["from"];

if( !$from ) $from = 0;
else $from += $numberOfResults;

if( !$to ) $to = $_POST["to"];
if( !$to ) $to = $_GET["to"];
if( !$to ) $to = 10;
else $to += $numberOfResults;

//return array with score
$results = $doc->search( trim( $query ), $from, $to );

if(count($results)){
	foreach($results as $key=>$value){
		//for each result get a list of includers
		$doc->id = $key;
		$includers = $doc->getTopIncluders();
		//loop trough the list
		for( $i=0,$n=count($includers); $i<$n; $i++ ){
			//if the doc is allready in results array, increase its score
			if( array_key_exists( $includers[$i]["id"], $results ) ){
				$results[ $includers[$i]["id"] ]["score"]++;
			}else {
				$results[ $includers[$i]["id"] ] = $includers[$i];
				$results[ $includers[$i]["id"] ]["score"] = 1;
			}//if else
		}//for
	}//foreach
  $results = $doc->getPublished( $results );
}//if

$resultsNum = count($results);
if(!$resultsNum){
	echo '<p>Your search'.(($query)?' for <strong>'.$query.'</strong>':'').' did not return any results.</p>';
}else{
	echo '<p>Your search for <strong>'.$query.'</strong> returned '.$resultsNum.' result'.(($resultsNum> 1)?'s':'').'</p>';
	echo '<hr noshade="noshade">';
	for( $i=0; $i<$resultsNum; $i++ ){
		echo '<p><strong><a href="index.php?page='.$results[$i]["id"].'">'.((stripslashes($results[$i]["title"]))?stripslashes($results[$i]["title"]):stripslashes($results[$i]["name"])).'</a></strong>';
		echo '<br>';
		echo ($results[$i]["description"])?$results[$i]["description"]:substr(strip_tags($results[$i]["content"]),0,250).'...</p>';
	}//for
}//if else
?>