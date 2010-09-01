<?php 
if( $image || $_GET['image']  )
{
  require_once("../util/import.php");
  $import = new import( $dba, 'img' );

  if( $import->status )
  {
    echo "<script>
		top.treefrmfrm.treefrm.location.href = 'tree.php';
		top.treefrmfrm.topfrm.changeStyle('media');
		top.mainfrm.topfrm.changePage('none');
		</script>";
    $message = 'Images succesfully imported <img src="../graphics/yes.gif">';
  }
}
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><img src="../graphics/transp.gif" width="15" height="20"></td>
	</tr>	
	<tr>	
		<td class="header">Import media files</td>			
	</tr>
	<tr>	
		<td class="save_message"><?=$message?></td>			
	</tr>
</table>
<br/>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>	
		<td class="plainText" style="padding-left: 10px;">
			To add multiple files to the media library follow these steps:
			<ol>
			<li>Connect to your site via FTP</li>
			<li>Upload the files you want to import to the folder <strong>/import/images</strong>.<br>
			Tip! you can create folders (one level) wich will be imported as well.</li>
			<li>When you have uploaded your files and folders. Go to this page and press the button Import images.</li>
			</ol>
		</td>
	</tr>
	<tr>	
		<td class="plainText" style="padding-left: 10px;"><br/><input type="button" value="Import images" onclick="document.location.href='index.php?pane=import&image=1'" class="knapgreen" style="width:250px"></td>			
	</tr>
</table>