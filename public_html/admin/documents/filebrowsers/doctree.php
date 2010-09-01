<?php
include_once( "../../util/dba.php" );
include_once( "../../util/tree.php" );

session_start();
$dba = new dba();
$root = 1;
$tree = new tree( $dba, session_id(), 'tree' );

//get parameters
$id			= $_POST["id"];
$action	= $_POST["action"];
$toggle = $_POST["toggle"];

if (!$id) 		$id			= $_GET["id"];
if (!$action) $action	= $_GET["action"];
if (!$toggle) $toggle	= $_GET["toggle"];

$tree->toggle( $toggle );
?>
<html>
	<head>
  	<title>Site tree</title>
    <link rel="stylesheet" href="../../style/style.css" type="text/css">
		<script src="thescript.js" language="javascript" type="text/javascript"></script>
  </head>
  <body bgcolor="#FFFFFF">
	  <form type="submit" name="tree" action="<?=$PHP_SELF?>" method="POST">
	    <input type="hidden" name="toggle">
			<?
	    	$nodes =  $tree->getNodeArray();
	      $n = count( $nodes );
	    ?>
			<h1>Choose intern page:</h1>
	    <table cellpadding="0" cellspacing="0" border="0">
	      <? for( $i = 0; $i < $n; $i++ ):?>
	        <tr>
	          <? //==================NODE TABLE CELL============================?>
	          <td align="left">
	            <table cellpadding="0" cellspacing="0" border="0">
	              <tr>
	                <? //==================SPACER CELL============================?>
	                <td width="<?=( $nodes[$i]["level"] ) * 10 ?>"><img src="../../tree/graphics/space.gif" width="<?=( $nodes[$i]["level"] ) * 10 ?>" height="10" alt="space" /></td>
		                <? //==================DISCLOSURE TRIANGLE CELL============================?>
	                <td>
										<a href="<?=$_SERVER["PHP_SELF"]?>?toggle=<?=$nodes[$i]["id"]?>" title="Toggle"><img src="../../tree/graphics/<?=( $nodes[$i]["open"] )? "down": "up" ?><?=( $nodes[$i]["node"] )? "node":"leaf"?>.gif" alt="Toggle" border="0" /></a>
									</td>
		                <? //==================NODE ICON CELL============================?>
	                <td>
										<a href="javascript:" onClick="OpenFile( '<?php echo $_SERVER["HTTP_HOST"].$dba->rootpath.'?page='.$nodes[$i]["id"]; ?>' );" title="Select" class="nodeName"><img src="../../tree/graphics/doc_normal.gif" alt="Select" border="0"/></a>
									</td>
		                <? //==================SPACER CELL============================?>
	                <td><img src="../../tree/graphics/space.gif" width="5" height="10" alt="space" /></td>
		                <? //==================ITEM NAME CELL============================?>
	                <td>
										<a href="javascript:" onClick="OpenFile( '<?php echo $_SERVER["HTTP_HOST"].$dba->rootpath.'?page='.$nodes[$i]["id"]; ?>' );" title="Select" class="nodeName"><?=$nodes[$i]["name"] ?></a>
									</td>
	              </tr>
	            </table>
	          </td>
	      	</tr>
	      <? endfor?>
	    </table>
	  </form>
    </body>
</html>
