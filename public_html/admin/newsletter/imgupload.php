<?
require_once("../util/dba.php");
require_once("../util/media.php");
session_start();

$dba = new dba();
$prefix = $dba->getPrefix();

if( $_POST["uploadDo"] )
{
	$media = new media( $dba );
	$media->path = "../../media/";

	$upload = $_FILES["upload"]["tmp_name"];
	$upload_name = $_FILES["upload"]["name"];
	if( $upload ) $media->uploadMedia( $upload, $upload_name );
	
	//reload the tree
	//echo "<script>opener.location.reload();</script>";
	
	echo "<script>
					Url = opener.frames['IMGPICK'].location.href;
					opener.frames['IMGPICK'].location.href = Url;
					window.close();
				</script>";
}
?>
<html>
<head>
<title>Upload</title>
<style>
  html, body, button, div, input, select, fieldset { font-family: MS Shell Dlg; font-size: 8pt;  position: absolute; };
</style>
</head>

<body style="background: threedface; color: windowtext;" scroll=no>
	<FIELDSET id="fldSpacing" style="left: 1.2em; top: 0.7em; width: 31.3em; height: 6.6em;">
		<LEGEND>Upload File</LEGEND>
	</FIELDSET>
	<FIELDSET id="fldSpacing" style="left: 1.2em; top: 7.9em; width: 31.3em; height: 8.6em;">
		<LEGEND>The following restrictions apply:</font></LEGEND>
	</FIELDSET>
	<div style="left: 0.2em; top: 9.9em; width: 31.3em; height: 8.6em;">
		<ul type="square">
			<li>File extension must be <b>&nbsp; .gif&nbsp; .jpg&nbsp; .jpeg&nbsp; .png</b></li>
			<li>Maximum file size is 2 Mb</li>
			<li>No spaces in the filename</li>
			<li>Filename must not contain special characters (/,*,&,^,%,!,?\,etc)<BR></li>
		</ul>
	</div>
	<form method="POST" action="<?=$_SERVER["PHP_SELF"]?>" enctype="multipart/form-data">
		<input type="hidden" name="uploadDo" value="1">
		<p align="center">
			<input type="file" name="upload" size="30" style="left: 1.8em;  top: 2.2em;  width: 30em; height: 2.1294em; ime-mode: disabled;" tabIndex="10"><br>
			<br>
			<input type="submit" name="submit" value="Upload" style="left: 12.8em;  top: 4.7em;  width: 7em; height: 2.1294em; ime-mode: disabled;" tabIndex="10">
		</p>
	</form>
</body>
</html>