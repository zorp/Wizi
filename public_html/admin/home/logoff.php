<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header">&nbsp;</td>
	</tr>
  <tr> 
    <td><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr> 
    <td>
        <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
           <tr>
                <td class="plainText" style="padding-left: 10px; padding-top: 5px;">&nbsp;</td>
           </tr>
           <tr>
                <td class="plainText" style="padding-left: 10px;"><strong>Log-off the account <?=($user->full_name)? $user->full_name: $user->name?>?</strong></td>
           </tr>
           <tr>
                <td class="plainText" style="padding-left: 10px; padding-top: 5px;">&nbsp;</td>
           </tr>
        </table>

    </td>
  </tr>
  <tr>
  	<td>
		<table cellpadding="0" cellspacing="0" border="0" width="310">
			<tr>
				<td align="left" width="310" style="padding-top: 10px;padding-left:10px;">
					<input type="button" value="No" name="cancel" onclick="document.location.href='<?=$PHP_SELF?>'" class="knapred"> 
					<input type="submit" value="Yes" name="logoff" class="knapgreen"> 
				</td>
			</tr>
		</table>
	</td>
  </tr>
  <tr> 
   	<td><img src="../graphics/transp.gif" height="15" width="15"></td>
  </tr>
</table>
