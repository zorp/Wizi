<?
    if( !$referer ) $referer = $_GET["referer"];
    if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
    $media->loadProperties();
    $media->getDependencies();
    $includers  = $media->getIncluders();
    $lowRes     = $media->lowResDependencies;
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td colspan="3"><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td colspan="3" class="header">
      Dependencies for "<?=$media->name?>"
    </td>
  </tr>
</table>
<br/>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <?if( !count( $media->linksFromDocs ) && !count( $includers ) && !count( $lowRes ) && !$media->hightres ):?>
     <tr class="color1" style="height:30px">
        <td colspan="2" class="tabelText" align="center">
                    No dependencies for <?=$media->name?> 
        </td>
     </tr> 
  <?endif?>
  <?for( $i = 0; $i < count( $media->linksFromDocs ); $i++ ):?>
     <?$rowcount++?>
     <tr class="color<?=($rowcount%2==0)?"1":"2"?>">
        <td class="tabelText">
		<img src="../graphics/pil_left.gif"> 
		<?if( $media->linksFromDocs[$i]["type"]=='m' ):?>
		    Is linked from document
		<?else:?>
		    Is contained in document
		<?endif?>
	</td>
	<td class="tabelText">
	    <img src="../tree/graphics/doc.gif">
	    <a href="../documents/index.php?id=<?=$media->linksFromDocs[$i]["id"]?>&referer=<?=urlencode($PHP_SELF."?id=".$id."&pane=$pane")?>" class="tabelText">'<?=$media->linksFromDocs[$i]["name"]?>'</a>
        </td>
     </tr> 
  <?endfor?>
  <?for( $i = 0; $i < count( $includers ); $i++ ):?>
     <?$rowcount++?>
     <tr class="color<?=($rowcount%2==0)?"1":"2"?>">
        <td class="tabelText">
		<img src="../graphics/pil_left.gif"> Is included by document
	</td>
	<td class="tabelText">

	    <img src="../tree/graphics/doc.gif">
	    <a href="../documents/index.php?id=<?=$includers[$i]["id"]?>&referer=<?=urlencode($PHP_SELF."?id=". $id ."&pane=$pane")?>" class="tabelText">'<?=$includers[$i]["name"]?>'</a>
        </td>
     </tr> 
  <?endfor?>

  <?for( $i = 0; $i < count( $lowRes ); $i++ ):?>
  	 <?$format = ( $lowRes[$i]["format"] )? $lowRes[$i]["format"]:"general"?>
     <?$rowcount++?>
     <tr class="color<?=($rowcount%2==0)?"1":"2"?>">
        <td class="tabelText">
		<img src="../graphics/pil_left.gif"> Is high resolution image to media 
	</td>
	<td class="tabelText">
	    <img src="../mediatree/graphics/file_icons/<?=$format?>.gif">
	    <a href="../media/index.php?pane=settings&id=<?=$lowRes[$i]["id"]?>&pane=<?=$pane?>&referer=<?=urlencode($PHP_SELF."?id=". $id)?>" class="tabelText">'<?=$lowRes[$i]["name"]?>'</a>
        </td>
     </tr> 
  <?endfor?>
   <?if( $media->hightres ):?>
     <?$format = ( $media->hightresFormat )? $media->hightresFormat:"general"?>
     <?$rowcount++?>
     <tr class="color<?=($rowcount%2==0)?"1":"2"?>">
        <td class="tabelText">
    <img src="../graphics/pil_right.gif"> Use image as high resolution 
  </td>
  <td class="tabelText">
      <img src="../mediatree/graphics/file_icons/<?=$format?>.gif">
      <a href="../media/index.php?pane=settings&id=<?=$media->hightres?>&pane=<?=$pane?>&referer=<?=urlencode($PHP_SELF."?id=". $id)?>" class="tabelText">'<?=$media->hightresName?>'</a>
        </td>
     </tr> 
    <?endif?>
</table>
<table width="349" cellpadding="0" cellspacing="0" border="0">
<tr> 
<td ><img src="../graphics/transp.gif" height="15"></td>
</tr>
<tr>
  <td class="tdpadtext">
    <?if( $referer ):?>
      <a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
    <?endif?>
  </td>
 </tr>
</table>
