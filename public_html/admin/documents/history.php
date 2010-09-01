<?php
    $document = new document( $dba, $id );

    if( !$restore ) $restore = $_POST["restore"];
    if( !$revid ) $revid = $_POST["revid"];
    if( !$view ) $view = $_GET["view"];
    if( !$remove ) $remove = $_POST["remove"];
		if( !$remove ) $remove = $_GET["remove"];

    if( $restore  )
    {
    	$document->restore( $revid );
    	unset( $view );
    }

    if( $cancel || $_POST["cancel"] ) unset( $view );

    $document->loadProperties();
    $document->removeRevision( $remove );
    $constrains = $user->getConstrains();

    if( !$view ) $document->getHistory();
    else $document->getRevision( $view );
?>
<?if( $view ):?>
<form name="my_form" action="<?=$PHP_SELF?>" method="post">
	<input type="hidden" name="id" value="<?=$document->id?>">
	<input type="hidden" name="pane" value="history">
	<input type="hidden" name="revid" value="<?=$view?>">
	<input type="hidden" name="view" value="">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	  <tr> 
	    <td colspan="2"><img src="../graphics/transp.gif" height="20"></td>
	  </tr> 
	  <tr>
	    <td colspan="2" class="header">Document revision id: <?=$view?></td>
	  </tr>
	</table>
	<br/>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	  <tr class="color1">
	    <td class="small_header" style="padding-top:4px" width="100">Edited:</td>
	    <td class="plainText"><?=$document->revision["date"]?></td>
	  </tr>
	  <tr class="color1">
	    <td class="small_header" style="padding-top:4px;">Editor:</td>
	    <td class="plainText"><?=( $document->revision["editor_fullname"])? $document->revision["editor_fullname"]:$document->revision["editor"]?></td>
	  </tr>
	  <tr class="color1">
	    <td class="small_header" style="padding-top:4px">Title:</td>
	    <td class="plainText"><?=($document->revision["title"])?$document->revision["title"]:"&nbsp;"?></td>
	  </tr>
	  <!--<tr class="color1">
	    <td class="small_header" style="padding-top:4px">Heading:</td>
	    <td class="plainText"><?=($document->revision["heading"])?$document->revision["heading"]:"&nbsp;";?></td>
	  </tr>-->
	  <tr class="color1">
	    <td class="small_header" style="padding-top:4px">Description:</td>
	    <td class="plainText"><?=($document->revision["description"])?$document->revision["description"]:"&nbsp;";?></td>
	  </tr>
	  <tr class="color1">
	    <td class="small_header" style="padding-top:4px;padding-bottom:4px;">Keywords:</td>
	    <td class="plainText"><?=($document->revision["meta"])?$document->revision["meta"]:"&nbsp;";?></td>
	  </tr>
	</table>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	  <tr> 
	    <td><img src="../graphics/transp.gif" height="15"></td>
	  </tr> 
	  <tr>
	    <td style="padding-left:10px;"><?=$document->revision["content"]?></td>
	  </tr>
	  <tr> 
	    <td><img src="../graphics/transp.gif" height="15"></td>
	  </tr> 
	  <?if( $constrains["Restore"][$id] ):?>
		  <tr> 
		    <td style="padding-left:10px;">
			<input type="submit" class="knapred" name="cancel" value="Cancel">
			<input type="submit" class="knapgreen" name="restore" value="Restore document" style="width:150px;">
		    </td>
		  </tr> 
	  <?endif?>
	</table>
</form>
<?else:?>
  <script language="javascript">
      function removing( id )
      {
        if( confirm( 'Are you sure you want to delete revision number '+ id +' ?' ) )
        {
            document.location.href = '<?=$PHP_SELF?>?id=<?=$document->id?>&pane=history&remove='+ id;
        }
      }
  </script>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	  <tr> 
	    <td colspan="3"><img src="../graphics/transp.gif" height="20"></td>
	  </tr> 
	  <tr>
	    <td colspan="3" class="header">History for "<?=$document->name?>"</td>
	  </tr>
	</table>
	<br/>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	  <tr>
	    <td class="small_header">Revision id</td> 
	    <td class="small_header" style="padding-top: 2px; padding-bottom:5px;">Editor</td> 
	    <td class="small_header">Date</td> 
	    <td class="small_header">&nbsp;</td> 
	  </tr>
      <?if( !count( $document->history ) ):?>
         <tr class="color1" style="height:30px">
            <td class="tabelText" colspan="4" align="center">No revisions for <?=$document->name?></td>
         <tr> 
      <?endif?>
	  <?for( $i = 0; $i < count( $document->history ); $i++ ):?>
	     <tr class="<?=($i%2==0)?"color1":"color2"?>">
					<td class="tabelText">Rev. <?=$document->history[$i]["rev"]?></td>
					<td><a href="../users/editUser.php?id=<?=$document->history[$i]["uid"]?>&referer=<?=urlencode('../documents/index.php?id='. $id .'&pane='.$pane)?>" class="tabelText"><?=$document->history[$i]["editor"]?></a></td>
					<td class="tabelText"><?=$document->history[$i]["date"]?></td>
					<td class="tabelText">
	          <?if( $constrains["Remove version"][$id] ):?>
              <a href="#" onclick="removing(<?=$document->history[$i]["rev"]?>);" class="redlink">Delete</a>
              &nbsp;
            <?endif?>
            <a href="<?=$PHP_SELF?>?id=<?=$document->id?>&pane=history&view=<?=$document->history[$i]["rev"]?>" class="greenlink">View</a>
          </td>
	     <tr> 
	  <?endfor?>
	</table>
  <br />
<?endif?>
