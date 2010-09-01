<?php
    /*********************************************************************/
    /*   media statistics.php                                              */
    /*                                                                   */
    /*********************************************************************/

		if( !$referer ) $referer = $_GET["referer"];
    if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
		
    $media->loadProperties();
?>
<input type="hidden" name="id" value="<?=$media->id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr> 
		<td><img src="../graphics/transp.gif" height="20"></td>
	</tr>
	<tr>
		<td class="header">Download statistics for "<?=$media->name?>"</td>
	</tr>
	<tr>
		<td><img src="../graphics/transp.gif" height="15"></td> 
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
				<tr>
					<td><img src="../graphics/transp.gif" height="15"></td> 
				</tr>
				<tr>
					<td valign="top" style="padding-left:10px;">
						The download statistics show how many times<br>a user has clicked on a hyperlink to download this media file.<br>
						The statistics are not updated if you embed the media file in your pages.
					</td>
				</tr>
				<tr>
					<td><img src="../graphics/transp.gif" height="15"></td> 
				</tr>
				<tr>
					<td valign="top" class="tdpadtext">
						This file has been downloaded a total of: <font color="green"><?=$media->downloadcount;?></font>
					</td>
				</tr>
				<tr>
					<td valign="top" class="tdpadtext">
						The last download was on: <font color="green"><?=$media->lastdownload;?></font>
					</td>
				</tr>
				<tr>
					<td><img src="../graphics/transp.gif" height="15"></td> 
				</tr>
			</table>
			<br>
			<table width="310" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="tdpadtext">
						<? if( $referer ):?>
							<a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
						<? endif?>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
