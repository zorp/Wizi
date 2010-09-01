<?php
include_once( "admin/util/dba.php" );
include_once( "admin/util/tree.php" );
include_once( "admin/util/dhtmlMenuTree.php" );

if( !$dba ) $dba = new dba();
$root = 1;
$tree = new dhtmlMenuTree( $dba, '', 'tree' );

$nodes =  $tree->getNodeArray();
$parentStack = array();

$n = count( $nodes );

function array_combine($keys, $vals){
	$retour= FALSE;
  $limite= count($keys);
  if (0!=$limite && $limite==count($vals)){
		$retour= array();
		foreach (array_map(NULL, $keys, $vals) as $item){
			$retour[ $item[0] ]= $item[1];
		}//foreach
  }//if
  return ($retour);
}//function


$letters = array_merge(range('a','z'), range('A','Z'));
$signs = array('!','"','#','¤','%','&','/','(',')','=','?','@','£','$','*','§');

$letters_signs = array_merge($letters, $signs);

// letters
$verdana_10px = array('5','5','4','5','5','4','5','5','1','3','5','1','9','5','5','5', '5','3','4','4','5','4','5','5','4','4',
//CAPITAL letters
'7','5','6','6','4','5','6','6','3','4','6','5','7','5','7','5','7','6','5','7','6','7','9','5','7','5',
//Signs Width
'1','4','6','5','9','6','4','3','3','6','4','8','5','5','5','4');

// letters
$verdana_bold_12px = array('6','6','6','6','6','5','6','6','2','4','7','2','10','6','6','6', '6','5','5','5','6','6','10','6','6','5',
//CAPITAL letters
'7','7','7','8','6','6','8','8','4','6','7','7','9','8','9','7','9','8','7','8','8','7','12','7','8','6',
//Signs Width
'2','5','8','7','13','8','6','4','4','7','5','10','7','7','5','7');

$widths['verdana'] = array_combine($letters_signs,$verdana_bold_12px);



function string_width($string,$widths) {

 # $widths is an associative array of pixelwidths for each character

 # function returns the (estimated) pixel width of a string

 for ($i=0; $i<strlen($string); $i++) {

   $length += $widths['verdana'][$string[$i]];

 }

 $spacing = (strlen($string))*2;
 return $length+$spacing;

}

//echo "<xmp>";
//print_r($widths['arial']);
//echo "</xmp>";
?>

oM=new makeCM("oM");
oM.resizeCheck=1;
oM.rows=1;
oM.onlineRoot="";
oM.pxBetween=0;
oM.fillImg="";
oM.fromTop=163;
oM.fromLeft=20;
oM.menuPlacement="left";
oM.wait=300;
oM.zIndex=400;
oM.useBar=0;
oM.barWidth="";
oM.barHeight="";
oM.barX=0;
oM.barY=0;
oM.barClass="clBar";
oM.barBorderX=0;
oM.barBorderY=0;

/*
	Explanation of the cm_makeLevel call
	cm_makeLevel(width,height,"regClass","overClass",borderX,borderY,"borderClass",rows,"align",offsetX,offsetY,"arrowImage",arrowWidth,arrowHeight);
*/
oM.level[0]=new cm_makeLevel(75,20,"clT","clTover",0,0,"clB2",0,"bottom",0,0,0,0,0);
oM.level[1]=new cm_makeLevel(75,20,"clS","clSover",0,0,"clB",0,"right",0,0,0,0,0);


/*
oM.makeMenu(name, parent_name, text, link, target, width, height, regImage, overImage, regClass, overClass , align, rows, nolink, onclick, onmouseover, onmouseout)
*/
<?
//$totalWidth = 0;
for( $i = 0; $i < $n; $i++ )
{
		if ( $nodes[$i]["id"] != 1 )
		{ 
		   $nodes[$i]["name"] = ereg_replace ("'", "\'", $nodes[$i]["name"]);
			if ( $nodes[$i]["parent"] == 1)
			{
				$width = string_width($nodes[$i]["name"],$widths)+20;
				//$totalWidth = $totalWidth+$width;
				//Writes the script used to build dhtmlmenu
				echo "oM.makeMenu('m". $nodes[$i]["id"] ."','m". $nodes[$i]["parent"] ."','". $nodes[$i]["name"] ."','index.php?page=". $nodes[$i]["id"] ."','','".$width."');\n";
			}
			else
			{
				$widthCheck = string_width($nodes[$i]["name"],$widths);
				$width = 135;
				$height = 20;
				if ( $widthCheck > 125 ) $height = 32;
				if ( $widthCheck > 245 ) $height = 44;
				//Writes the script used to build dhtmlmenu
				echo "oM.makeMenu('m". $nodes[$i]["id"] ."','m". $nodes[$i]["parent"] ."','". $nodes[$i]["name"] ."','index.php?page=". $nodes[$i]["id"] ."','','".$width."','".$height."');\n";
			}
		}
}
//$lastWidth = 772-$totalWidth;
//echo "oM.makeMenu('m999','m1','','','','".$lastWidth."','','','','last','lastOver');\n";

?>

// Construct the menu
oM.construct()