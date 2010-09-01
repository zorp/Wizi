<?php
require_once("admin/util/dba.php");
require_once("admin/util/menu.php");
require_once("admin/util/page.php");

//instantiate the objects we need
$myPage = new page( new dba() );
$news = $myPage->getNews();
Header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="iso-8859-1"?>';
?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:taxo="http://purl.org/rss/1.0/modules/taxonomy/" xmlns:syn="http://purl.org/rss/1.0/modules/syndication/">
<channel rdf:about="<?php echo $_SERVER["HTTP_HOST"].dirname($_SERVER['PHP_SELF']).'index.php'; ?>">
<title>WIZI WEBSITE</title>
<link><?php echo $_SERVER["HTTP_HOST"].dirname($_SERVER['PHP_SELF']).'index.php'; ?></link>
<description>Website powered by WIZI 5.0</description>
<dc:language>da</dc:language>
</channel>
<?php for( $i=0;$i< count($news); $i++ ):?>
  <item rdf:about="<?=$link?>">
  	<title><?php echo ( trim( $news[$i]["heading"] ) )? $news[$i]["heading"]:( ( trim( $news[$i]["title"] ) )? $news[$i]["title"]:$news[$i]["name"] );?></title>
	  <link><?php echo $_SERVER["HTTP_HOST"].dirname($_SERVER['PHP_SELF'])?>/index.php?page=<?=$news[$i]["id"]?></link>
	  <description><?php echo (trim($news[$i]["description"] ) )? $news[$i]["description"]:strip_tags( $news[$i]["summary"] );?></description>
  </item>
<?php endfor ?>
</rdf:RDF>