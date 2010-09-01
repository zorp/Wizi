<?php
    /*********************************************************************/
    /*   preview.php                                                     */
    /*********************************************************************/
    $document = new document( $dba, $id );
		$document->loadProperties();
		
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><img src="../graphics/transp.gif" height="20"></td>
	</tr>
	<tr>
		<td class="header">Edit Document "<?=$document->name?>"</td>
	</tr>
</table>
<br/>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr class="color1">
		<td class="alert_message" style="padding-left:10px;padding-bottom:5px;padding-top:5px;"><strong>You are only allowed to preview the document "<?=$document->name?>"</strong></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td style="padding-left:10px;padding-bottom:5px;" align="left"><?=$document->getTranslatedContent()?></td>
	</tr>
</table>