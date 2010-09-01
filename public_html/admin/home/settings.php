<?
if( $usersubmited || $_POST["usersubmited"] )
{
  if( !$name )         $name = $_POST["name"];
  if( !$full_name )    $full_name = $_POST["full_name"];
  if( !$password )     $password = $_POST["password"];
  if( !$confirm_password ) $confirm_password = $_POST["confirm_password"];
  if( !$mail )         $mail = $_POST["mail"];


  if( $password == $confirm_password )
  {
    if ($name != $_POST["prevname"]) $dupname = $user->checkName( $name );
		if ($dupname)
		{
			$message = "The username you typed is allready in use, please choose a different udername.";
		}
		else
		{
			$user->setName( $name );
	    $user->setFull_name( $full_name );
	    $user->setMail( $mail );
	    if( trim( $password ) ) $user->setPassword( $password );
	    $message = '<font color="green">Your changes has been saved <img src="../graphics/yes.gif"></font>';
		}
  }
  else
  {
    $message = "The passwords you typed didn't match, please try again";
  }
}
?>
<input type="hidden" name="pane" value="<?=$pane?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header">Edit your personal account settings</td>
  </tr> 
  <tr>
    <td align="left" class="alert_message" <?if ($message):?>style="padding-bottom:20px;padding-top:20px;"<?endif?>><?=$message?>&nbsp;</td>
  </tr> 
    <td align="center">
        <table width="100%" cellpadding="3" cellspacing="0" border="0" class="color1">
		       <tr>
                <td class="tdpadtext">Your name (Only for internal use)</td>
           </tr>
            <tr>
                <td class="tdpadtext"><input type="text" class="input" name="full_name" value="<?=$user->full_name?>"></td>
           </tr>
		        <tr>
                <td class="tdpadtext">E-mail (Only for internal use)</td>
           </tr>
            <tr>
                <td class="tdpadtext" style="padding-bottom: 20px;"><input type="text" class="input" name="mail" value="<?=$user->mail?>"></td>
           </tr>
           <tr>
                <td class="tdpadtext">Choose a login Name</td>
           </tr>
			     <tr>
                <td class="tdpadtext"><input type="text" class="input" name="name" value="<?if ($dupname) {echo $name;} else { echo $user->name; }?>"></td>
           </tr>
		        <tr>
                <td class="tdpadtext">Choose or change your password</td>
           </tr>
            <tr>
               <td class="tdpadtext"><input type="password" class="input" name="password"></td>
           </tr>
		        <tr>
                <td class="tdpadtext">Confirm your password</td>
           </tr>
            <tr>
               <td class="tdpadtext" style="padding-bottom: 10px;"><input type="password" class="input" name="confirm_password"></td>
           </tr>
        </table>
    </td>
  <tr>
  <tr>
  	<td>
		<table cellpadding="0" cellspacing="0" border="0" width="310">
			<tr>
				<td align="right" width="310" style="padding-top: 10px;"><input type="hidden" name="prevname" class="input"  value="<?=$user->name?>"><input type="button" value="Cancel" onclick="document.location.href='<?=$PHP_SELF?>'" class="knapred"> 
	<input type="submit" value="Save" name="usersubmited" class="knapgreen"></td>
			</tr>
		</table>
	</td>
  </tr>
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  <tr>
</table>