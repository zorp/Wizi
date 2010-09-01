<?php
  require_once("../util/forms.php");

  
  $forms = new forms( $dba );
  $formList  = $forms->getForms();
  
  $n = count( $formList );
	
	if( !$referer ) $referer = $_GET["referer"];
  if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
  
?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td colspan="5"><img src="../graphics/transp.gif" height="20"></td>
		</tr>
		<tr>
			<td colspan="5" class="header">Forms</td>
		</tr>
		<tr>
			<td colspan="5"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
    <? if( !$n ):?>
    <tr class="color1">
			<td colspan="5" align="center" class="tabelText" style="padding:25px">No forms available</td>
    </tr>
    <? endif?>  
		<? for( $i = 0; $i< $n; $i++ ):?>
		<tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
			<td class="tabelText"><?=$formList[$i]["name"]?></td>
				<td colspan="2"><a href="deleteForm.php?id=<?=$formList[$i]["id"]?>" class="redlink">Delete</a></td>
				<td><a href="form.php?id=<?=$formList[$i]["id"]?>" class="greenlink">Edit</a></td>
				<td><? if ($formList[$i]["action_type"] != "mail" || $formList[$i]["action_type"] != "custom"):?><a href="form.php?id=<?=$formList[$i]["id"]?>&pane=formdata" class="tabelText">See collected data</a><? else:?>&nbsp;<? endif?></td>
		</tr>
		<? endfor?>
		<tr>
			<td colspan="5"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
		<tr>
			<form name="my_name" action="form.php" method="post">
			<input type="hidden" name="action" value="add">
			<td colspan="5" class="tabelText"><input type="submit" value="Add Form" name="addForm" class="knapgreen"></td>
			</form>
		</tr>
		<tr>
			<td colspan="5"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
		<tr>
			<td class="tdpadtext" colspan="5">
		    <? if( $referer ):?>
    		  <a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
		    <? endif?>
		  </td>
		 </tr>
	</table>