<?php
include_once( "admin/util/dba.php" );
include_once( "admin/util/tree.php" );
include_once( "admin/util/siteMapTree.php" );

if( !$dba ) $dba = new dba();
$root = 1;
$tree = new siteMapTree( $dba, session_id(), 'tree' );
$tree->fullOpen = true;

$nodes =  $tree->getNodeArray();
$n = count( $nodes );
?>
<h1>Sitemap</h1>
<ul id="sitemap">
<?php for( $i = 0; $i < $n; $i++ ):?>
	<li style="padding-left:<?php echo ($nodes[$i]["level"])*10 ?>px;">
		<a href="index.php?page=<?php echo $nodes[$i]["id"]; ?>">
			<img src="graphics/sideikon.gif" alt="<?=$nodes[$i]["name"] ?>" border="0">&nbsp;<?php echo $nodes[$i]["name"]; ?>
		</a>
	</li>
<?php endfor ?>
</ul>