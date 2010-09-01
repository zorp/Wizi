<?php
require_once("util/dba.php");
require_once("util/user.php");

session_start();
$dba    = new dba();
$user   = new user( $dba );

if( !$user_name ) $user_name = $_POST["user_name"];
if( !$user_password ) $user_password = $_POST["user_password"];

if ($_POST["issubmitted"])
{
	if (!$user_name) $wrong_str = "Please type your login name";
	if (!$user_password) $wrong_str = "Please type your password";
	if (!$user_name && !$user_password) $wrong_str = "Please type your login name and password";
}

if( trim( $user_name ) && trim( $user_password ) )
{
        $user->log( trim( $user_name ), trim( $user_password ) );
        if( $user->isLogged() ) die("<script>top.document.location.href='index.php';</script>");
        else $wrong_str = "Wrong login name or password";

}
else
{
				$user->logoff();
        session_destroy();
}
?>
<html>
	<head>
		<title>Log in</title>
		<link rel="stylesheet" href="style/style.css" type="text/css">
	</head>
	<body bgcolor="#454545" style="overflow:hidden;">
		<form name="my_form" action="<?=$PHP_SELF; ?>" method="post">
		<input type="hidden" name="issubmitted" value="1">
		<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td valign="middle" align="center">
				<table width="380" border="0">
	<tr>
		<td class="header3">WIZI CMS Login</td>
		<td>&nbsp;</td>
	</tr>
</table>

				
					<table width="380" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border: 5px solid #666666; background: #fff url(graphics/adminlogin-back.png) repeat-x;">
						<tr>
							<td width="330" class="header">&nbsp;</td>
							<td rowspan="2" align="right" style="padding:10px">&nbsp;</td>
						</tr>
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" border="0">
									<tr>
								  	<td colspan="2"><img src="graphics/transp.gif" border="0" width="10" height="15"></td>
									</tr>
									<? if ($wrong_str):?>
									<tr>
								  	<td colspan="2" class="tdText"><font color="FF0000"><?=$wrong_str?></font></td>
									</tr>
									<tr>
								  	<td colspan="2" class="tdText">&nbsp;</td>
									</tr>
									<? endif?>
									<tr>
								    <td width="110" class="tdText">Login name:<br>
							    	<br></td>
								    <td width="220"><div align="right">
								    	<input type="text" name="user_name" class="input" style="width:220px; padding: 4px 0 3px 3px;" value="<?=$user_name?>">
							    	</div><br></td>
									</tr>
									<tr>
								    <td width="110" class="tdText">Password:</td>
								    <td><div align="right">
								    	<input type="password" name="user_password" class="input" style="width:220px; padding: 4px 0 3px 3px;">
							    	</div></td>
									</tr>
									<tr>
								    <td colspan="2"><img src="graphics/transp.gif" border="0" width="10" height="15"></td>
									</tr>
									<tr>
										<td colspan="2" align="right"><input type="submit" value="Log in" class="knap"></td>
									</tr>
								</table>							</td>
						</tr>
						<tr>
							<td colspan="2" align="right" style="padding:10px;padding-top:20px;padding-bottom:5px;"><a href="http://www.wizi.dk" target="_blank"><img src="graphics/cms_by_execube.gif" border="0"></a></td>
						</tr>
			    </table>
				</td>
			</tr>
		</table>
		</form>
	</body>
</html>


