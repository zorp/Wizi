<?php
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
include_once( "../util/mediaTree.php" );

session_start();
$dba = new dba();
$root = 1;
$tree = new mediaTree( $dba, session_id(), 'mediatree' );

//get parameters
$id			= $_POST["id"];
$action	= $_POST["action"];
$toggle = $_POST["toggle"];
$browser = $_GET["browser"];

if (!$id) 		$id			= $_GET["id"];
if (!$action) $action	= $_GET["action"];
if (!$toggle) $toggle	= $_GET["toggle"];
if (!$browser) $browser	= $_POST["browser"];

$formats = array("doc","gif","jpg","mdb","pdf","ppt","png","swf","wvx","xls");

$tree->toggle( $toggle );
?>
<html>
	<head>
		<title>Site tree</title>
		<link rel="stylesheet" href="../style/style.css" type="text/css">
		<script>
			<?if ($browser):?>
				function chooseImg (imgPath)
				{
					top.document.forms[0].elements['link_value'].value = 'media.php?id='+imgPath;
				}
			<?else:?>
				function chooseImg (imgPath)
				{
					top.document.forms[0].elements['link_value'].value = 'media.php?id='+imgPath;
				}
			<?endif?>
		</script>
	</head>
	<body bgcolor="#FFFFFF">
		<?
			$nodes =  $tree->getNodeArray();
			$n = count( $nodes );
		?>
		<table cellpadding="0" cellspacing="0" border="0">
			<?for( $i = 0; $i < $n; $i++ ):?>
				<?$imgPath = $nodes[$i]["id"];?>
				<?$format = $nodes[$i]["format"];?>
				<?if( !in_array($format, $formats) ) $format = 'general';?>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="<?=( $nodes[$i]["level"] ) * 10 ?>"><img src="../mediatree/graphics/space.gif" width="<?=( $nodes[$i]["level"] ) * 10 ?>" height="10" alt="space"\></td>
							<td>
								<a href="<?=$PHP_SELF?>?toggle=<?=$nodes[$i]["id"]?>" title="Toggle"><img src="../mediatree/graphics/<?=( $nodes[$i]["open"] )? "down": "up" ?><?=( $nodes[$i]["node"] )? "node":"leaf"?>.gif" alt="Toggle" border="0"\></a></td>
							<td>
									<a href="javascript:chooseImg('<?=$imgPath?>')" title="Select" class="nodeName"><img src="../mediatree/graphics/file_icons/<?=$format?>.gif" alt="Select" border="0"/></a>
							</td>
							<td><img src="../mediatree/graphics/space.gif" width="5" height="10" alt="space"\></td>
							<td class="nodeName">
									<a href="javascript:chooseImg('<?=$imgPath?>')" title="Select" class="nodeName"><?=$nodes[$i]["name"] ?></a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?endfor?>
		</table>
	</body>
</html>
