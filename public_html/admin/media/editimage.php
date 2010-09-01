<?php
    /*********************************************************************/
    /*   media settings.php                                              */
    /*                                                                   */
    /*********************************************************************/

		require_once("../util/ImageEditor.php");
		
		$media->loadProperties();
		$img = $media->id.".".$media->format;
		$imageEditor = new ImageEditor($img, "../../media/");
		
		if( $savesettings || $_POST["savesettings"] )
    {
				$imageEditor->resize(50, 50);
				$imageEditor->outputFile($img, "../../media/");
		    echo "<script>parent.parent.treefrmfrm.treefrm.location.href='../mediatree/tree.php';</script>";
    }
		
?>
<form enctype="multipart/form-data" name="my_form" action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="id" value="<?=$media->id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr> 
		<td><img src="../graphics/transp.gif" height="20"></td>
	</tr>
	<tr>
		<td class="header">Edit image "<?=$media->name?>"</td>
	</tr>
	<tr>
		<td><img src="../graphics/transp.gif" height="15"></td> 
	</tr>
	<tr>
		<td><br>
			<table width="310" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="tdpadtext">
						<? if( $referer ):?>
							<a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
						<? endif?>
					</td>
					<td align="right"><input type="submit" value="Save" name="savesettings" class="knapgreen"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
