<?php
    /*********************************************************************/
    /*   media settings.php                                              */
    /*                                                                   */
    /*********************************************************************/

		if( !$referer ) $referer = $_GET["referer"];
    if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
		
    
		if ($_GET["width"] && $_GET["height"])
		{
			$media->setHeight( $_GET["height"] );
			$media->setWidth( $_GET["width"] );
			if ($_GET["size"]) $media->setSize( $_GET["size"] );
		}
		
		if ($_GET["removehigh"])
		{
			$media->removeHightRes( $_GET["removehigh"] );
		}
		
		if ($_GET["removemedia"])
		{
			$media->removeMedia( $_GET["removemedia"] );
			echo "<script>parent.parent.treefrmfrm.treefrm.location.href='../mediatree/tree.php';</script>";
		}
		
		if( $savesettings || $_POST["savesettings"] )
    {
        if( !$description ) $description = $_POST["description"];
        if( !$alt ) $alt = $_POST["alt"];
				if( !$height ) $height = $_POST["height"];
				if( !$width ) $width = $_POST["width"];
        if( !$upload ) $upload = $_FILES["upload"]["tmp_name"];
        if( !$upload_name ) $upload_name = $_FILES["upload"]["name"];
        if( !$hightres ) $hightres = $_POST["hightres"];
        if( !$hightresName ) $hightresName = $_POST["hightresName"];
        if( !trim( $hightresName ) ) $hightres = 0;

        $media->setDescription( $description );
        $media->setAlt( $alt );
				$media->setHeight( $height );
				$media->setWidth( $width );
        $media->setHightRes( $hightres );

        if( $upload ) $media->uploadMedia( $upload, $upload_name );
		
		    echo "<script>parent.parent.treefrmfrm.treefrm.location.href='../mediatree/tree.php';</script>";
    }
    $media->loadProperties();
		$img = "/".$media->id.".".$media->format;
		$imgsave = $media->id.".".$media->format;
?>
<script type="text/javascript" language="javascript" src="assets/dialog.js"></script>
<script type="text/javascript" language="javascript" src="../scripts/xp_progress.js"></script>
<script type="text/javascript" language="javascript">
  function chooseHightRes()
  {
    szURL = 'hightResTree.php';
    prop_str = 'resizable=no,scrollbars=no,toolbar=no,location=no,';
    prop_str+= 'directories=no,status=no,width=600,height=465,screenX=50,screenY=50';
		ae_imgwin = window.open(szURL ,"ae_imgwin",prop_str);
  }
  function choosenImage( id, name )
  {
    document.my_form.hightres.value = id;
    document.my_form.hightresName.value = name;
  }
	function removeHighres(id)
	{
		if ( confirm('Sure you want to remove High resolution attachment?') )
		{
			window.location.href = "index.php?pane=settings&id="+document.my_form.id.value+"&removehigh="+id;
		}
	}
	function removeMedia(id)
	{
		if ( confirm('Sure you want to remove Media attachment?') )
		{
			window.location.href = "index.php?pane=settings&id="+document.my_form.id.value+"&removemedia="+id;
		}
	}
	function editImage(image, imagesave) 
	{
		var url = "editor.php?img="+image+"&imgsave="+imagesave;
		Dialog(url, function(param) 
		{
			if (!param) // user must have pressed Cancel
				return false;
			else
			{
				return true;
			}
		}, null);		
	}
</script>
<form enctype="multipart/form-data" name="my_form" action="<?=$_SERVER["PHP_SELF"]?>?id=<?=$media->id?>" method="post">
<input type="hidden" name="id" value="<?=$media->id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr> 
		<td><img src="../graphics/transp.gif" height="20"></td>
	</tr>
	<tr>
		<td class="header">Settings for "<?=$media->name?>"</td>
	</tr>
	<tr>
		<td><img src="../graphics/transp.gif" height="15"></td> 
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
				<tr>
					<td valign="top" width="350">
						<table cellpadding="0" cellspacing="0" border="0" class="color1">
							<? if( in_array( $media->format, $media->graphicshigh ) ):?>
							<tr>
								<td class="tdpadtext" width="350" valign="top" height="20">Alt text<br><span class="plaintext">(Used as default when picture is inserted)</span></td>
							</tr>
							
							<tr>
								<td class="tdpadtext" width="350" valign="top" height="20"><input type="text" name="alt" class="input" value="<?=$media->alt?>"></td>
							</tr>
							<? endif?>
							<? if( in_array( $media->format, $media->graphicshigh ) ):?>
							<tr>
								<td class="tdpadtext" valign="top" width="350" height="20">Attach high resolution image<br><span class="plaintext">(Automaticly linked if media is used as include)</span></td>
							</tr>
							<tr>
								<td class="tdpadtext" width="350" valign="top" height="20">
									<input type="hidden" name="hightres" value="<?=$media->hightres?>">
									<input type="text" name="hightresName" class="input" style="width:<?=($media->hightres)?"140":"220"?>px; background-color:#CCCCCC;"  value="<?=$media->hightresName?>" readonly>
									<input type="button" value="<?=($media->hightres)?"Change":"Choose"?>" onclick="chooseHightRes()" class="knap">
									<? if($media->hightres): ?>
										<input type="button" value="Remove" onclick="removeHighres(<?=$media->id?>);" class="knapred">
									<? endif ?>
								</td>
							</tr>
			        <? endif?>
							<? if ($media->format == "swf"): ?>
							<tr>
								<td class="tdpadtext" valign="top" width="350" height="20">Width:</td>
							</tr>
							<tr>
								<td class="tdpadtext" width="350" valign="top" height="20">
									<input type="text" name="width" class="input" value="<?=$media->width?>">
								</td>
							</tr>
							<tr>
								<td class="tdpadtext" valign="top" width="350" height="20">Height:</td>
							</tr>
							<tr>
								<td class="tdpadtext" width="350" valign="top" height="20">
									<input type="text" name="height" class="input" value="<?=$media->height?>">
								</td>
							</tr>
							<? endif ?>
							<tr>
						    <td class="tdpadtext" width="350" valign="top" height="20">File to upload</td>
							</tr>
							<tr>
						    <td class="tdpadtext" width="350" valign="top" height="20">
									<input type="file" class="input" name="upload">
									<ul type="square">
										<li>Maximum file size is <?=ini_get('upload_max_filesize')?></li>
										<li>Filename must not contain special characters (/,*,&,^,%,!,?\,etc)<BR></li>
									</ul>
								</td>
							</tr>
							<tr>
								<td class="tdpadtext" width="350" valign="top">&nbsp;</td>
							</tr>
						</table>
					</td>
					<td class="tdpadtext" style="padding-left:15px;" valign="top">
						<? if ($media->format):?>
							<? include("preview.php")?><br><br>
							<input type="button" value="Remove media file" onclick="removeMedia(<?=$media->id?>);" class="knapred" style="width:150px; "><br><br>
							<? if ($media->isEditableGraphic()):?>
								<input type="button" value="Edit image" onclick="editImage('<?=$img?>','<?=$imgsave?>');" class="knapgreen" style="width:150px; "><br><br>
							<? endif ?>
						<? else:?>
							&nbsp;
						<? endif?></td>
				</tr>
			</table>
			<br>
			<table width="310" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="tdpadtext">
						<? if( $referer ):?>
							<a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
						<? endif?>
						<script type="text/javascript">
							var bar= createBar(200,15,'white',1,'black','green',150,5,0,"");
							bar.hideBar();
						</script>
					</td>
					<td align="right"><input type="submit" value="Save" name="savesettings" class="knapgreen" onclick="bar.showBar();"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
