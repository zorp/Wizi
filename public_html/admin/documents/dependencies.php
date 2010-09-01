<?
    if( !$referer ) $referer = $_GET["referer"];
		if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
    $document = new document( $dba, $id );
    $document->loadProperties();
    $document->getDependencies();
    $document->getIncludes();
    $document->getIncluders();
		
    $colorcount = 0;

   $a = count( $document->links2Docs );
   $b = count( $document->linksFromDocs );
   $c = count( $document->links2Media );
   $d = count( $document->includedBy );
   $e = count( $document->includes );
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr> 
		<td colspan="3"><img src="../graphics/transp.gif" height="20"></td>
	</tr> 
  <tr>
    <td colspan="3" class="header">
        Dependencies for "<?=$document->name?>"
    </td>
  </tr>
</table>
<br/>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <?if( !$a && !$b && !$c && !$d && !$e):?>
     <tr class="color1" style="height:30px">
        <td class="tabelText" colspan="2" align="center">No dependencies for <?=$document->name?></td>
     <tr> 
  <?endif?>

  <?//==========================DOCS THIS DOC LINKS TO ========================//?>
  <?for( $i = 0; $i < $a; $i++ ):?>
     <?$colorcount++;?>
     <tr <?=($colorcount%2==0)?"class=\"color1\"":"class=\"color2\""?>>
        <td class="tabelText">
          <img src="../graphics/pil_right.gif" alt="'<?=$document->name?>' links to '<?=$document->links2Docs[$i]["name"]?>'">
          Links to document 
        </td>
        <td class="tabelText" align="left"><img src="../tree/graphics/doc.gif"> <a href="index.php?id=<?=$document->links2Docs[$i]["id"]?>&referer=<?=urlencode($PHP_SELF."?id=$id")?>" class="tabelText">'<?=$document->links2Docs[$i]["name"]?>'</a></td>
     <tr> 
  <?endfor?>

  <?//==========================OTHER DOCS THAT LINKS TO THIS ONE ========================//?>
  <?for( $i = 0; $i < $b; $i++ ):?>
     <?$colorcount++;?>
     <tr class="color<?=($colorcount%2==0)?"1":"2"?>">
        <td class="tabelText">
          <img src="../graphics/pil_left.gif" alt="'<?=$document->name?>' is linked from '<?=$document->linksFromDocs[$i]["name"]?>'">
          Is linked from document 
        </td>
        <td class="tabelText" align="left"><img src="../tree/graphics/doc.gif"><a href="index.php?id=<?=$document->linksFromDocs[$i]["id"]?>&referer=<?=urlencode($PHP_SELF."?id=$id")?>" class="tabelText">'<?=$document->linksFromDocs[$i]["name"]?>'</a></td>
     <tr> 
  <?endfor?>

  <?//==========================DOC LINKS TO MEDIA OR EMBED MEDIA ========================//?>
  <?for( $i = 0; $i < $c; $i++ ):?>
    <?$colorcount++;?>
		<?$format = ( $document->links2Media[$i]["format"] )? $document->links2Media[$i]["format"]:"general"?>
     <tr <?=($colorcount%2==0)?"class=\"color1\"":"class=\"color2\""?>>
        <td class="tabelText">
						<img src="../graphics/pil_right.gif" alt="'<?=$document->name?>' links to '<?=$document->links2Media[$i]["name"]?>'">
            <?if( $document->links2Media[$i]["type"]=='m' ):?>
              Contains media 
            <?else:?>
              Links to media
            <?endif?>
	    </td>
        <td class="tabelText" align="left" style="table-layout:fixed; height:20px;"><img src="../mediatree/graphics/file_icons/<?=$format?>.gif">
          <a href="../media/index.php?id=<?=$document->links2Media[$i]["id"]?>&referer=<?=urlencode($PHP_SELF."?id=$id")?>" class="tabelText">'<?=$document->links2Media[$i]["name"]?>'</a>
        </td>
     <tr> 
  <?endfor?>

  <?//==========================DOC WHO INCLUDED THIS DOC ========================//?>
  <?for( $i = 0; $i < $d; $i++ ):?>
     <?if( $document->includedBy[$i]["id"] != $document->id ):?>	
	     <?$colorcount++;?>
	     <tr class="color<?=($colorcount%2==0)?"1":"2"?>">
		<td class="tabelText">
		  <img src="../graphics/pil_left.gif" alt="'<?=$document->name?>' is linked from '<?=$document->includedBy[$i]["name"]?>'">
		  Is included by document 
		</td>
		<td class="tabelText" align="left"><img src="../tree/graphics/doc.gif"><a href="index.php?id=<?=$document->includedBy[$i]["id"]?>&referer=<?=urlencode($PHP_SELF."?id=$id")?>" class="tabelText">'<?=$document->includedBy[$i]["name"]?>'</a></td>
	     <tr> 
     <?endif?>
  <?endfor?>

  <?//==========================DOC INCLUDED BY THIS DOC ========================//?>
  <?for( $i = 0; $i < $e; $i++ ):?>
     <?php
		  if( $document->includes[$i]["type"] == 'm' )
      {
			  $format = ( $document->includes[$i]["format"] )? $document->includes[$i]["format"]:"general";
        $label = 'media';
        $icon = '../mediatree/graphics/file_icons/'. $format .'.gif';
        $editurl = '../media/index.php?id='. $document->includes[$i]["id"] ;
        $editurl.= '&referer='. urlencode( $PHP_SELF .'?id='. $id ) ;
      }
		  if( $document->includes[$i]["type"] == 'd' )
      {
			  $icon ='../tree/graphics/doc.gif';
        $label = 'document';
			  $editurl = 'index.php?id='. $document->includes[$i]["id"] ;
        $editurl.= '&referer='. urlencode( $PHP_SELF .'?id='. $id );
      }
		  if( $document->includes[$i]["type"] == 'f' )
      {
			  $icon ='../tree/graphics/form.gif';
        $label = 'form';
			  $editurl = '../forms/form.php?id='. $document->includes[$i]["id"] ;
        $editurl.= '&referer='. urlencode( $PHP_SELF .'?id='. $id );
      }
     ?>
     <?if( $document->includes[$i]["id"] != $document->id ):?>	
	     <?$colorcount++;?>
	     <tr class="color<?=($colorcount%2==0)?"1":"2"?>">
		<td class="tabelText">
		  <img src="../graphics/pil_right.gif" alt="'<?=$document->name?>' includes '<?=$document->includes[$i]["name"]?>'">
      Includes  <?=$label?>
		</td>
		<td class="tabelText" align="left">
			  <img src="<?=$icon?>">
			  <a href="<?=$editurl?>" class="tabelText">'<?=$document->includes[$i]["name"]?>'</a>
		</td>
	     <tr> 
     <?endif?>
  <?endfor?>


</table>
<table width="349" cellpadding="0" cellspacing="0" border="0">
<tr> 
<td><img src="../graphics/transp.gif" height="15"></td>
</tr>
<tr>
  <td class="tdpadtext">
    <?if( $referer ):?>
      <a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
    <?endif?>
  </td>
 </tr>
</table>