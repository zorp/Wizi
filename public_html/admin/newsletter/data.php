<?php
    /*********************************************************************/
    /*   newsletter settings.php                                         */
    /*                                                                   */
    /*********************************************************************/

    if( $savesettings || $_POST["savesettings"] )
    {
        if( !$fromname ) $fromname = $_POST["fromname"];
				if( !$fromemail ) $fromemail = $_POST["fromemail"];
				if( !$bounceemail ) $bounceemail = $_POST["bounceemail"];
				if( !$bounceemail ) $bounceemail = $fromemail;

				$newsletter->setData( $fromname, $fromemail, $bounceemail );

				$message = '<br>Your last save was on '.date("H:i").' <img src="../graphics/yes.gif">';
    }
    
		$data = $newsletter->loadStandardData();
?>
<form action="<?=$PHP_SELF?>" method="post" name="my_form" id="my_form">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="id" value="<?=$id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr> 
    	<td colspan="2"><img src="../graphics/transp.gif" height="20"></td>
	</tr>
	  <tr>
    <td colspan="2" class="header">Edit standard settings for newsletter</td>
  </tr>
	<?if ($message):?>
	<tr>
		<td colspan="2" class="save_message"><?=$message?></td>
	</tr>
	<?endif?>
    <tr>
    	<td colspan="2"><img src="../graphics/transp.gif" height="15"></td> 
  </tr>
    <tr>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
		    <tr>
        	<td class="tdpadtext">From name</td>
				</tr>
					<td class="tdpadtext"><input type="text" name="fromname" class="input" value="<?=$data[0]?>"></td>
	   		</tr>
				<tr>
					<td class="tdpadtext">&nbsp;</td>
				</tr>
				<tr>
        	<td class="tdpadtext">From email</td>
				</tr>
					<td class="tdpadtext"><input type="text" name="fromemail" class="input" value="<?=$data[1]?>"></td>
	   		</tr>
        <tr>
					<td class="tdpadtext">&nbsp;</td>
				</tr>
				<tr>
        	<td class="tdpadtext">Bounce email (Errors are sent to this email address)</td>
				</tr>
					<td class="tdpadtext"><input type="text" name="bounceemail" class="input" value="<?=$data[2]?>"></td>
	   		</tr>
        <tr>
					<td class="tdpadtext">&nbsp;</td>
				</tr>
		</table>
				<br>
				<table width="620"  cellpadding="0" cellspacing="0" border="0">
					<tr>
						<?if( $referer ):?>
            	<td class="tdpadtext">
	            	<a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
            	</td>
						<?endif?>
						<td align="right"><input type="submit" value="Save" name="savesettings" class="knapgreen"></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
   		</tr>
</table>
</form>