<?php
    require_once("../util/document.php");
    $document  = new document( $dba, $id );
    $document->loadProperties();
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><img src="../graphics/transp.gif" height="20"></td>
	</tr>
	<tr>
		<td class="header">Properties for "<?=$document->name?>"</td>
	</tr>
</table>
<br/>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr class="color1">
		<td colspan="2" class="alert_message" style="padding-left:10px;padding-bottom:5px;padding-top:5px;"><strong>You are not entitled to change the document properties for "<?=$document->name?>"</strong></td>
	</tr>
	<tr class="color2">
		<td class="tdpadtext">Document title:</td>
		<td class="plainText"><?=$document->title?></td>
	</tr>
	<tr class="color1">
		<td class="tdpadtext">Description:</td>
		<td class="plainText"><?=$document->description?></td>
	</tr>
	<tr class="color2">
		<td class="tdpadtext">Meta keywords:</td>
		<td class="plainText"><?=$document->meta?></td>
	</tr>
	<tr class="color1">
		<td class="tdpadtext">Template used:</td>
		<td class="plainText"><?=( $document->template )? $document->template:"default.php"?></td>
	</tr>
	<tr class="color2">
		<td class="tdpadtext">Navigation:</td>
		<td class="plainText"><?=( $document->nav )? "D":"Don't d"?>isplay document on navigation</td>
	</tr>
	<tr class="color1">
		<td class="tdpadtext">Publish status:</td>
		<td class="plainText"><?=( $document->publish )?"Published":"Unpublished"?>  document</td>
	</tr>
	<?if( $document->publishDate["y"] ):?>
	<tr class="color2">
		<td class="tdpadtext">Publish on date [ d.m.y ]</td>
		<td class="plainText"><?=$document->publishDate["d"]?>.<?=$document->publishDate["m"]?>.<?=$document->publishDate["y"]?></td>
	</tr>
	<?endif?>
	<?if( $document->unpublishDate["y"] ):?>
	<tr class="<?=( $document->publishDate["y"] )? "color1":"color2"?>">
		<td class="tdpadtext">Unpublish on date [ d.m.y ]</td>
		<td class="plainText"><?=$document->unpublishDate["d"]?>.<?=$document->unpublishDate["m"]?>.<?=$document->unpublishDate["y"]?></td>
	</tr>
	<?endif?>
</table>
<br/>
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="tdpadtext">
			<?if( $referer ):?>
				<a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
			<?endif?>
		</td>
	</tr>
</table>