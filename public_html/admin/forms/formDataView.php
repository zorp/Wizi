<?php
  if( !$antal ) $antal = $_POST['antal'];
  if( !$antal ) $antal = $_GET['antal'];
  if( !$antal ) $antal = 10;

  if( !$index ) $index = $_POST['index'];
  if( !$index ) $index = $_GET['index'];
  if( !$index ) $index = 0;
	
	if( !$deleteId ) $deleteId = $_GET['deleteId'];
	if ($deleteId)
	{
		$delete = $forms->deleteFormData( $deleteId );
		$msg = "Data as been deleted";
	}

  if( !$deleteFileName ) $deleteFileName = $_POST['deleteFileName'];

  if( $forms->action_type == 'csv' )
  {
    $path = 'formdata';
    $csvfiles = array();

    if( !is_dir( $path ) ) return;

    if( $deleteFileName )
    {
      unlink( $path.'/'.$deleteFileName );  
    }
    $handle = opendir( $path );
    if( !$handle ) return;
    while( $file = readdir( $handle ) )
    {
        if( stristr( $file, ".csv" ) )
        {
            $f = explode("_", $file );
            if( $f[1] == $forms->id )
            $csvfiles[ count( $csvfiles ) ] = $file;
        }
    }
  }
  if( $forms->action_type == 'db' || $forms->action_type == 'newsletter' )
  {
			$dbrecords = $forms->getFormData( $index, $antal );
  }
?>
<script language="javascript">
  function deleteCVS( fileName )
  {
    if( confirm('Sure you want to delete the CVS data?') )
    {
			document.my_form.deleteFileName.value = fileName;
    	document.my_form.submit();
		}
  }
  function flip( index )
  {
    document.my_form.index.value = index;
    document.my_form.submit();
  }
	function deleteData(formId,dataId)
	{
		if( confirm('Sure you want to delete the selected data?') )
    {
			document.location.href= "form.php?pane=formdata&id="+formId+"&deleteId="+dataId;
		}
	}
</script>
<input type="hidden" name="pane" value="formdata">
<input type="hidden" name="antal" value="<?=$antal?>">
<input type="hidden" name="index" value="<?=$index?>">
<input type="hidden" name="deleteFileName">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td bgcolor="#FFFFFF" colspan="3"><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header">Collected data for form '<?=$forms->name?>'</td>
  </tr> 
    <td bgcolor="#FFFFFF" class="save_message"><?=$msg?></td>
  </tr>
	<tr> 
    <td bgcolor="#FFFFFF" colspan="3"><img src="../graphics/transp.gif" height="10"></td>
  </tr> 
  <tr> 
    <td bgcolor="#FFFFFF" class="plainText">
      <table width="100%">
        <? if( $forms->action_type == 'csv' ):?>
          <? if( !count( $csvfiles ) ):?>
            <tr>
              <td class="tabelText" align="center" style="padding:25px">No csv files availables</td>
            </tr>
          <? endif?>
          <? for( $i = 0; $i < count( $csvfiles ); $i++ ):?>
            <tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
              <td class="tabelText"><a class="tabelText" href="displaycsv.php?csv=<?=$csvfiles[$i]?>&type=csv&ext=csv.xls" target="_blank"><?=$csvfiles[$i]?></a></td>
              <td><a class="tabelText" href="displaycsv.php?csv=<?=$csvfiles[$i]?>&type=xsl&ext=csv.xls" target="_blank"><img src="../mediatree/graphics/file_icons/xls.gif" border="0"></a></td>
              <td><a class="tabelText" href="#" onclick="deleteCVS('<?=$csvfiles[$i]?>')" class="redlink">Delete</a></td>
            </tr>
          <? endfor?>
        <? else:?>
          <? if( !count( $dbrecords ) ):?>
            <tr>
              <td class="tabelText" align="center" style="padding:25px">No records availables</td>
            </tr>
          <? else:?>
            <? foreach( $dbrecords as $record):?>
              <? if( $i == 0 ):?>
                <tr class="color3" style="padding:4px">
                  <td class="tdpadtext"> Dato </td>
                  <? for( $j = 0; $j < count( $record ); $j++ ):?>
                    <td class="tdpadtext" nowrap>
                      <?=$record[$j]['fieldname']?>
                    </td>
                  <? endfor?>
									<td>&nbsp;</td>
                </tr>
              <? endif?>
              <tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
                <? for( $j = 0; $j < count( $record ); $j++ ):?>
                  <? if( $j == 0 ):?>
                    <td class="tabelText"> <?=$record[0]['submited']?></td>
                  <? endif?>
                  <td class="tabelText"> <?=$record[$j]['fieldvalue']?> </td>
									<? $dataId = $record[$j]['requestid'];?>
                <? endfor?>
								<td><a href="#" onclick="deleteData(<?=$id?>,<?=$dataId?>);" class="redlink">Delete</a></td>
              </tr>
              <? $i++?>
            <? endforeach?>
            <tr>
              <td align="center" colspan="<?=( count( $record ) + 2 )?>" height="75">
                <table  cellpadding="4" cellspacing="0" border="0">
                  <tr>
                    <td>
                      <?if( $index >= $antal ):?>
                        <a href="#" onclick="flip(<?=($index - $antal )?>)"><img src="../graphics/back_arrow_simple.gif" border="0"></a>
                      <?else:?>
                        &nbsp;
                      <?endif?>
                    </td>
                    <td align="center" class="tabelText">
                      <strong>Select how many recordes showed on one page: </strong>
											<select name="antal" onchange="document.my_form.submit()"> 
                        <option value="5" <?=( $antal==5 )?'selected':''?>>5</option>
                        <option value="10" <?=( $antal==10 )?'selected':''?>>10</option>
                        <option value="20" <?=( $antal==20 )?'selected':''?>>25</option>
                        <option value="50" <?=( $antal==50 )?'selected':''?>>50</option>
                        <option value="75" <?=( $antal==75 )?'selected':''?>>75</option>
                        <option value="all" <?=( $antal=='all' )?'selected':''?>>all</option>
                      </select>
                    </td>
                    <td>
                      <?if(  $antal == count( $dbrecords ) ):?>
                        <a href="#" onclick="flip(<?=( $index + $antal )?>)"><img src="../graphics/forward_arrow_simple.gif" border="0"></a>
                      <?else:?>
                        &nbsp;
                      <?endif?>
                    </td>
                 </tr>
                </table>
              </td>
            </tr>
          <?endif?>
        <?endif?>
      </table>
    </td>
  </tr>
</table>
<?
/*
echo "<xmp>";
echo print_r($dbrecords);
echo "</xmp>";
*/
?>
