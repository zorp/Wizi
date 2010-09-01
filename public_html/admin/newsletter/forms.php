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
			<td colspan="2"><img src="../graphics/transp.gif" height="20"></td>
		</tr>
		<tr>
			<td colspan="2" class="header">Mailinglist - Forms</td>
		</tr>
		<tr>
			<td colspan="2"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
		<? for( $i = 0; $i< $n; $i++ ):?>
			<? if ($formList[$i]["action_type"] == "newsletter"):?>
				<? $formsPresent = true; ?>
				<tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
					<td class="tabelText"><?=$formList[$i]["name"]?></td>
					<td><a href="index.php?id=<?=$formList[$i]["id"]?>&pane=formdata" class="tabelText">See collected data</a></td>
				</tr>
			<? endif ?>
		<? endfor?>
		<? if( !$formsPresent ):?>
    <tr class="color1">
			<td colspan="2" align="center" class="tabelText" style="padding:25px">No forms available</td>
    </tr>
    <? endif?>
		<tr>
			<td colspan="2"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
	</table>