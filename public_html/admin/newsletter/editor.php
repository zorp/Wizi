<?
include("../documents/FCKeditor/fckeditor.php");
?>
<?php
$oFCKeditor = new FCKeditor('content') ;
$oFCKeditor->BasePath = $dba->rootpath.'admin/documents/FCKeditor/';
$oFCKeditor->Config['SkinPath'] = $oFCKeditor->BasePath . 'editor/skins/silver/';
$oFCKeditor->ToolbarSet = "Wizi";
$oFCKeditor->Width = "620";
$oFCKeditor->Height = "400";
$oFCKeditor->Value = $newsletter->body;
$oFCKeditor->Create() ;
?>