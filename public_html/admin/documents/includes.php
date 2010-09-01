<?
	$document = new document( $dba, $id );
	$document->loadProperties();
	
	if( !$referer ) $referer = $_GET["referer"];
  if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
		
	if( !$includeType ) $includeType = $_POST["includeType"];
	if( !$includeType ) $includeType = $_GET["includeType"];
	if( !$removeInclude ) $removeInclude = $_GET["removeInclude"];
	if( !$moveDownId ) $moveDownId = $_POST["moveDownId"];
	if( !$moveUpId ) $moveUpId = $_POST["moveUpId"];
	if( !$docId ) $docId = $_POST["docId"];
	if( !$url ) $url = $_POST["url"];
	
	if( $moveDownId ) $document->moveIncludeDown( $moveDownId );
	if( $moveUpId   ) $document->moveIncludeUp( $moveUpId );
	if( $docId      ) $document->addInclude( $docId, $includeType );	
	if( $url				) $document->addIncludeExternal( $url, $includeType );
	if( $url				) $docId = true;
	if( is_numeric( $removeInclude ) ) $document->removeInclude( $removeInclude );
	
	if( !$includeType ) $includeType = "d";
?>
<script language="javascript">
	function movingUp( id )
	{
		document.tree.moveUpId.value = id;
		document.tree.submit();
	}
	function movingDown( id )
	{
		document.tree.moveDownId.value = id;
		document.tree.submit();
	}
</script>
<form name="tree" action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="id" value="<?=$document->id?>">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="moveDownId">
<input type="hidden" name="moveUpId">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr> 
		<td><img src="../graphics/transp.gif" height="20"></td>
	</tr> 
	<tr>
		<td class="header">Includes for document "<?=$document->name?>"</td>
	</tr>
</table>
<? if( !$docId && ( $addinclude || $_POST["addinclude"] )  ):?>
	<input type="hidden" name="addinclude" value="1">
	<br/>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="color1">
		<tr> 
			<td class="plainText" width="150" style="padding-left:10px;padding-top:5px;">
				<b>Select what to include:</b>
			</td>
			<td width="200" style="padding-top:5px;">
				<select name="includeType" onchange="document.tree.submit()">
					<option value="d" <?=( $includeType == "d" )?"selected":""?>>Document</option>
					<option value="e" <?=( $includeType == "e" )?"selected":""?>>External URL</option>
					<option value="m" <?=( $includeType == "m" )?"selected":""?>>Media</option>
					<option value="f" <?=( $includeType == "f" )?"selected":""?>>Form</option>
				</select>
			</td>
			<td>&nbsp;</td>
		</tr> 
	</table>
	<? if( $includeType =="d" ):?>
		<div style="position:relative; padding-left:10px; padding-top:20px;" class="color1">
			<span class="plaintext"><b>Select document to include:</b></span>
			<br><br>
			<? require_once("includesDocTree.php");?><br/>
		</div>
	<? endif?>
	<? if( $includeType =="m" ):?>
		<div style="position:relative; padding-left:10px; padding-top:20px;" class="color1">
			<span class="plaintext"><b>Select media to include:</b><br><br>Formats wich embed:<br>gif, jpg, png, swf, htm & html</span>
			<br><br>
			<? require_once("includesMediaTree.php");?><br/>
		</div>
	<? endif?>
	<? if( $includeType =="f" ):?>
		<div style="position:relative; padding-left:0px; padding-top:20px;" class="color1">
			<span class="plaintext" style="padding-left:10px;"><b>Select form to include:</b></span>
			<? require_once("includesForms.php");?><br/>
		</div>
	<? endif?>
	<? if( $includeType =="e" ):?>
		<div style="position:relative; padding-left:0px; padding-top:20px;" class="color1">
			<span class="plaintext" style="padding-left:10px;">External reference ( url must include http:// ):</span>
			<br>
			<div style="padding-left:10px;"><input type="text" size="40" name="url" value="http://"></div>
			<br><br>
			<div style="padding-left:10px;"><input type="submit" class="knapgreen" value="Save"></div>
		</div>
	<? endif?>
<? else:?>
	<? $document->getNestedIncludes()?>
	<br/>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="small_header">Name</td> 
			<td class="small_header" style="padding-top: 2px; padding-bottom:5px;">Type</td> 
			<td class="small_header" colspan="2">&nbsp;</td> 
		</tr>
	  <? if( !count( $document->allIncludes ) ):?>
			<tr class="color1" style="height:30px">
				<td class="tabelText" colspan="4" align="center">
					No includes for <?=$document->name?>
				</td>
			<tr>
	  <? endif?>
		<? for( $i = 0; $i < count( $document->allIncludes ); $i++ ):?>
			<?php
				if(  $document->allIncludes[$i]["type"]=='m' )
				{
					$editUrl = '../media/index.php';
					$format = ( $document->allIncludes[$i]['format'] )? $document->allIncludes[$i]['format']:'general';
					$icon ='../mediatree/graphics/file_icons/'. $format .'.gif';
					$iconalt = "Media file type: ".$document->allIncludes[$i]['format'];
				}
				if(  $document->allIncludes[$i]["type"]=='d' )
				{
					$editUrl = 'index.php';
					$icon ='../tree/graphics/doc.gif';
					$iconalt = "Document";
				}
				if(  $document->allIncludes[$i]["type"]=='f' )
				{
					$editUrl = '../forms/form.php';
					$icon ='../tree/graphics/form.gif';
					$iconalt = "Form";
				}
				if(  $document->allIncludes[$i]["type"]=='e' )
				{
					$editUrl = $document->allIncludes[$i]["incurl"];
					$icon ='../tree/graphics/external.gif';
					$document->allIncludes[$i]["name"] = $document->allIncludes[$i]["incurl"];
					$iconalt = "External URL";
				}
			?>
			<? $countRow++?>
			<? if( $document->allIncludes[$i]["level"] ):?>
				<tr bgcolor="#e3e3e3">
					<td>
						<img src="../graphics/transp.gif" height="10" width="<?=( $document->allIncludes[$i]["level"] * 14 )?>">
						<img src="../graphics/include_arrow.gif">
						<? if ( $document->allIncludes[$i]["type"] == "e" ):?>
							<a href="<?=$editUrl?>" class="tabelText" style="padding-left:0px" target="_blank"><?=$document->allIncludes[$i]["name"]?></a>
						<? else:?>
							<a href="<?=$editUrl?>?id=<?=$document->allIncludes[$i]["id"]?>" class="tabelText" style="padding-left:0px"><?=$document->allIncludes[$i]["name"]?></a>
						<? endif ?>
					</td>
					<td class="tabelText" valign="bottom">
						<img src="<?=$icon?>" alt="<?=$iconalt?>">
					</td>
					<td colspan="2" class="tabelText">&nbsp;</td>
				</tr> 
			<? else:?>
				<tr class="<?=($countRow%2==0)?"color1":"color2"?>" style="padding-top:4px;padding-bottom:4px;">
					<td>
						<? if ( $document->allIncludes[$i]["type"] == "e" ):?>
							<a href="<?=$editUrl?>" class="tabelText" target="_blank"><?=$document->allIncludes[$i]["name"]?></a>
						<? else: ?>
							<a href="<?=$editUrl?>?id=<?=$document->allIncludes[$i]["id"]?>&referer=<?=urlencode($PHP_SELF.'?id='.$id.'&pane='.$pane )?>" class="tabelText"><?=$document->allIncludes[$i]["name"]?></a>
						<? endif ?>
					</td>
					<td class="tabelText" valign="bottom">
						<img src="<?=$icon?>" alt="<?=$iconalt?>">
					</td>
					<td class="tabelText">
						<? if( $i != 0 ):?>
							<a href="#" onclick="movingUp( <?=$document->allIncludes[$i]["inckey"]?> )"><img src="../graphics/pil_up.gif" alt="Move up" border="0"></a>
            <? else:?>
							<img src="../graphics/transp.gif" width="11" height="9">
						<? endif?>
						<? if( $i != ( count( $document->allIncludes ) - 1 ) ):?>
							<a href="#" onclick="movingDown( <?=$document->allIncludes[$i]["inckey"]?> )"><img src="../graphics/pil_down.gif" alt="Move down" border="0"></a>
						<? else:?>
							<img src="../graphics/transp.gif" width="11" height="9">
						<? endif?>
					</td>
					<td class="tabelText">
						<a href="<?=$PHP_SELF?>?id=<?=$document->id?>&pane=<?=$pane?>&removeInclude=<?=$document->allIncludes[$i]["inckey"]?>" class="redlink">Remove</a>
					</td>
				</tr> 
			<? endif?>
		<? endfor?>
	</table>
	<div style="padding-left:10px; padding-top:10px;">
		<input type="submit" class="knapgreen" name="addinclude" value="Add">
	</div>
<? endif?>
</form>
<br/>
<table width="310"  cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="tdpadtext">
			<? if( $referer ):?>
				<a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
			<? else:?>
				&nbsp;
			<? endif?>
		</td>
	</tr>
</table>
