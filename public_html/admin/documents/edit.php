<?php
include("fckeditor/fckeditor.php");
$document = new document( $dba, $id );

if( $_POST["action"] == "restorelive" ){
	$document->setDraft(false);
}

if( $_POST["action"] == "savecontent" ){
	$title = $_POST["title"];
	$description = $_POST["description"];
	$meta = $_POST["meta"];
	$heading = $_POST["heading"];
	$content = $_POST["content"];

	$document->setTitle( $title );
	$document->setHeading( $heading );
	$document->setDescription( $description );
	$document->setMeta( $meta );
	$document->setContent( $content );

	$message = '<br>Your last save was on '.date("H:i").' <img src="../graphics/yes.gif">';
}

if( $_POST["action"] == "savedraft" ){
	$title = $_POST["title"];
	$description = $_POST["description"];
	$meta = $_POST["meta"];
	$heading = $_POST["heading"];
	$content = $_POST["content"];

	$document->setDraftTitle( $title );
	$document->setDraftHeading( $heading );
	$document->setDraftDescription( $description );
	$document->setDraftMeta( $meta );
	$document->setDraftContent( $content );

	$message = '<br>Your last save was on '.date("H:i").' <img src="../graphics/yes.gif">';
}


if ($document->isDraft()){
	$isDraft = true;
	$document->loadDraftProperties();
	$documentContent = $document->getDraftTranslatedContent();
}else{
	$isDraft = false;
	$document->loadProperties();
	$documentContent = $document->getTranslatedContent();
}


?>
<form action="<?=$_SERVER["PHP_SELF"]?>" name="edit" id="edit" method="post">
<input type="hidden" name="action" value="">
<input type="hidden" name="id" value="<?=$id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr> 
		<td><img src="../graphics/transp.gif" height="20"></td>
	</tr> 
	<tr>
		<td class="header">Edit Document "<?=$document->name?>"</td>
	</tr>
	<tr>
		<td class="tdpadtext">
				<table cellpadding="5" cellspacing="0" border="0" width="400">
					<tr>
						<td>
							<? if ($isDraft):?>
								<table cellpadding="0" cellspacing="0" border="0" width="396">
								<tr>
									<td valign="top">
										<div style="padding-left:57px;padding-right:20px;padding-top:15px;border-bottom:1px solid #E2E2E2;background-image: 
url('../graphics/test.gif');background-repeat:no-repeat;">
											<span style="font-size:18px;color:#24BE00;">IMPORTANT</span><br><br>
											You are editing a draft version of this document.<br> Use "Save for online" to publish content on the website.
											<br><br>
										- <a href="preview_liveedit.php?id=<?=$id?>" target="_blank" style="text-decoration:underline;color:#333333;">View Live content</a> (Will open in a new window)<br>
										- <a href="javascript:restoreLive('restorelive');" style="text-decoration:underline;color:#333333;">Restore Live content for editing</a> <br><br><br>
										</div>
									</td>
								</tr>
								</table>
							<? endif?>
						</td>
					</tr>
				</table>
		</td>
	</tr>
	<? if ($message):?>
	<tr>
		<td class="save_message"><?=$message?></td>
	</tr>
	<? endif?>
</table>
<br/>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
	<tr class="color1">
		<td width="10"><img src="../graphics/transp.gif" width="10" height="10"></td>
		<td style="padding-top:5px;padding-bottom:5px;">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr class="color1">
					<td colspan="3" class="tdpadtext" style="padding-left:0px">Document Title</td>
			  </tr>
				<tr>    
					<td colspan="3" class="tdpadtext" style="padding-left:0px"><input type="text" size="53" style="width:600px;" name="title" class="input" value="<?=$document->title?>"></td>
			  </tr>
				<tr class="color1">
					<td class="tdpadtext" style="padding-left:0px" valign="top">Description (used as short text in<br>search results and on news publish)</td>
				  <td class="tdpadtext" style="padding-left:0px">&nbsp;</td>
				  <td class="tdpadtext" style="padding-left:0px" valign="top">Keywords<br>(seperate with space)</td>
				</tr>
				<tr>
					<td class="tdpadtext" style="padding-left:0px"><textarea name="description" rows="4" cols="53" class="input" wrap="virtual"><?=$document->description?></textarea></td>
				  <td class="tdpadtext" style="padding-left:0px">&nbsp;</td>
				  <td class="tdpadtext" style="padding-left:0px"><textarea name="meta" rows="4" cols="53" class="input" wrap="virtual"><?=$document->meta?></textarea></td>
				</tr>
			</table>
		<td>&nbsp;</td>
	</tr>
	<tr class="color1">
		<td width="10"><img src="../graphics/transp.gif" width="10" height="10"></td>
		<td colspan="2" rowspan="4"><br />
		<?php
			$oFCKeditor = new FCKeditor('content') ;
			$oFCKeditor->BasePath = $dba->rootpath.'admin/documents/fckeditor/';
			$oFCKeditor->Config['SkinPath'] = $oFCKeditor->BasePath . 'editor/skins/silver/';
			$oFCKeditor->ToolbarSet = "Wizi";
			$oFCKeditor->Width = "99%";
			$oFCKeditor->Height = "400";
			$oFCKeditor->Value = $documentContent;
			$oFCKeditor->Create() ;
			?>		
			<div align="left"><?if( $referer ):?>
				<a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
			<?else:?>
				&nbsp;
			<?endif?>
			</div>
			<input type="button" name="cancel" value="Cancel Changes" class="knapred" style="position:relative;width:125px;" onclick="updateAction('cancel');">
			<input type="button" name="savedraft" value="Save as Draft" class="knaporange" style="position:relative;width:125px;" onclick="updateAction('savedraft');">
		<input type="button" name="savecontent" value="Save for Online" class="knapgreen" style="position:relative;width:125px;" onclick="updateAction('savecontent');">		</td>
	</tr>
	<tr class="color1">
		<td width="10" bgcolor="#F9FAEF"><img src="../graphics/transp.gif" width="10" height="10"></td>
	</tr>
	<tr>
		<td width="10" bgcolor="#F9FAEF"><img src="../graphics/transp.gif" width="10" height="10"></td>
	</tr>
	<tr>
		<td width="10" bgcolor="#F9FAEF">&nbsp;</td>
	</tr>
</table>
</form>