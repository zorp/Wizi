<?php
require_once("../../util/dba.php");
require_once("../../util/media.php");
session_start();

$dba = new dba();
$prefix = $dba->getPrefix();

if ( isset( $_FILES['NewFile'] ) && !is_null( $_FILES['NewFile']['tmp_name'] ) ){
	$media = new media( $dba );
	$media->path = "../../../media/";

	$upload = $_FILES["NewFile"]["tmp_name"];
	$upload_name = $_FILES["NewFile"]["name"];
	if( $upload ){
		$media->uploadMedia( $upload, $upload_name );
		$imgPath = $dba->rootpath."media/".$media->id.".".$media->format;
		SendUploadResults( 0, $imgPath, $media->name );
	}else{
		SendUploadResults( 1, '', '', 'Nothing to  upload' );
	}
}

// This is the function that sends the results of the uploading process.
function SendUploadResults( $errorNumber, $fileUrl = '', $fileName = '', $customMsg = '' ){
	echo '<script type="text/javascript">';
	$rpl = array( '\\' => '\\\\', '"' => '\\"' ) ;
	echo 'window.parent.OnUploadCompleted(' . $errorNumber . ',"' . strtr( $fileUrl, $rpl ) . '","' . strtr( $fileName, $rpl ) . '", "' . strtr( $customMsg, $rpl ) . '") ;' ;
	echo '</script>' ;
	exit ;
}
?>