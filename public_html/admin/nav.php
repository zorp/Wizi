<?php 
/*********************************************************************/
/*   nav.php                                                         */
/*                                                                   */
/*********************************************************************/
require_once("util/dba.php");
require_once("util/user.php");
session_start();
$user = new user( new dba() );
if( !$user->isLogged() ) die("<script language=\"JavaScript\">top.document.location.href='log.php'</script>");
?>
<html>
    <head>
        <title>navigation</title>
        <link rel="stylesheet" href="style/style.css" type="text/css">
    </head>
		<script language="javascript">
		var currentPane = 'home';
		
		function changePage(activePane) // TO usee call onclick="changePage('endusers')"
		{
			if (activePane == 'none' && currentPane)
			{
				lastTD = currentPane+'TD';
				document.all[ lastTD ].style.background = "transparent";
				document.all[ currentPane ].className = 'top_nav_link';
				currentPane = false;
			}
			if (activePane != currentPane && activePane != 'none')
			{
				activeTD = activePane+'TD';
				document.all[ activeTD ].style.background = "#FFFFFF";
				document.all[ activePane ].className = 'top_nav_link_on';
				if (currentPane)
				{
					lastTD = currentPane+'TD';				
					document.all[ lastTD ].style.background = "transparent";
					document.all[ currentPane ].className = 'top_nav_link';
				}
				currentPane = activePane;
			}
		}
		
		</script>
    <body class="color4" style="margin-left:40px; padding-top: 58px;">
<table height="27" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td nowrap id="homeTD" style="padding-left:10px;padding-right:10px;" bgcolor="#FFFFFF">
		<a href="home/index.php" id="home" target="contentfrm" class="top_nav_link_on" onFocus="if(this.blur)this.blur();" onClick="changePage('home')">Home</a></td>
		<?if( $user->rolesById[1] ):?>
		<!--<td nowrap id="usersTD" style="padding-left:10px;padding-right:10px;"><a href="users/index.php" id="users" target="contentfrm" class="top_nav_link" onFocus="if(this.blur)this.blur();" onClick="changePage('users')">Wizi users & groups</a></td>
		<td nowrap id="templatesTD" style="padding-left:10px;padding-right:10px;"><a href="templates/index.php" id="templates" target="contentfrm" class="top_nav_link" onFocus="if(this.blur)this.blur();" onClick="changePage('templates')">Website elements</a></td>
		<td nowrap id="formsTD" style="padding-left:10px;padding-right:10px;"><a href="forms/index.php" id="forms" target="contentfrm" class="top_nav_link" onFocus="if(this.blur)this.blur();" onClick="changePage('forms')">Forms</a></td>
		<td nowrap id="endusersTD" style="padding-left:10px;padding-right:10px;"><a href="endusers/index.php" id="endusers" target="contentfrm" class="top_nav_link" onFocus="if(this.blur)this.blur();" onClick="changePage('endusers')">Website users & restrictions</a></td>-->
				<td nowrap id="administrationTD" style="padding-left:10px;padding-right:10px;"><a href="administration.php" id="administration" target="contentfrm" class="top_nav_link" onFocus="if(this.blur)this.blur();" onClick="changePage('administration')">Administration</a></td>
		<?endif?>
		<!--<td nowrap id="helpTD" style="padding-left:10px;padding-right:10px;"><a href="help/index.php" id="help" target="contentfrm" class="top_nav_link" onfocus="if(this.blur)this.blur();">Help</a></td>
		A Fake Cell Must be hidden-->
		<td nowrap id="fakeTD" style="visibility:hidden;"><a href="#" id="fake">&nbsp;</a></td>
	</tr>
</table>
</body>
</html>