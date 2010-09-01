<?php
require_once("admin/util/dba.php");
require_once("admin/util/media.php");
require_once("admin/util/browser_check.php");
$br = new Browser;

$id = $_GET["id"];
if (!$id) $id = $_POST["id"];
if( !$id ) die("Missing media id parametre");

$media  = new media( new dba(), $id );

$media->loadProperties();
$media->updateDownloadCount($media->downloadcount);

if ( $media->isGraphic() || $media->format == "pdf" || $media->format == "htm" || $media->format == "html"){
	Header("Location:media/". $media->id . ".". $media->format);
}

if ( $media->isMedia() ){
	$filename = "media/". $media->id . ".". $media->format;

	require_once('admin/util/getid3/getid3.php');
	$getID3 = new getID3;
	$fileinfo = $getID3->analyze($filename);
	getid3_lib::CopyTagsToComments($fileinfo);
	
	$height = $fileinfo['video']['resolution_y'];
	$width = $fileinfo['video']['resolution_x'];
	
	echo '<html><head><title>Showing: '.$media->name.'</title></head><body>';

	if ($media->format == "mov" || $br->Platform == "MacIntosh" ){	
		echo '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="'.($height+45).'" width="'.(($width)?$width:'300').'" name="wizimedia" align="left">
		<param name="src" value="'.$filename.'">
		<param name="autoplay" value="true">
		<param name="controller" value="true">
		<embed height="'.(($height)?$height+45:'45').'" width="'.(($width)?$width:'300').'" align="left" src="'.$filename.'" autoplay="true" controller="true"></embed>
		</object>';
	}else{
		echo '<object id="wizimedia" CLASSID="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/ en/nsmp2inf.cab#Version=5,1,52,701" standby="Loading Microsoft Windows® Media Player components..." TYPE="application/x-oleobject" width="'.(($width)?$width:'300').'" height="'.(($height)?$height+45:'45').'">
		<param name="fileName" value="'.$filename.'">
		<param name="animationatStart" value="true">
		<param name="transparentatStart" value="true">
		<param name="autoStart" value="true">
		<param name="showControls" value="true">
		<param name="Volume" value="-20">
		<embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" src="'.$filename.'" name="MediaPlayer1" width="'.(($width)?$width:'300').'" height="'.(($height)?$height+45:'45').'" autostart="1" showcontrols="1" volume="-20">
		</object>';
	}//if else
	
	echo '</body></html>';

}else{
	$filename = "media/". $media->id . ".". $media->format;
	header("Cache-control: private"); // fix for IE
	header("Content-Type: application/octet-stream"); 
	header("Content-Length: ".filesize($filename));
	header("Content-Disposition: attachment; filename=".$media->name.".".$media->format);

	$fp = fopen($filename, 'r');
	fpassthru($fp);
	fclose($fp);
}//if else
?>