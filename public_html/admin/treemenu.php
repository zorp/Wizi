<?php 
    /*********************************************************************/
    /*   treemenu.php                                                    */
    /*                                                                   */
    /*                                                                   */
    /*********************************************************************/
    /*   MenuPane for open / close sitetree                              */
    /*                                                                   */
    /*********************************************************************/

    require_once("util/dba.php");
    require_once("util/user.php");
    session_start();
    $user = new user( new dba() );
    
    if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Wizi Treemenus</title>
	<link rel="stylesheet" href="style/style.css" type="text/css">
	<script language="javascript">
		var currentTree = 'documents';
		function changeStyle(activeTree) // TO usee call onclick="changePage('endusers')"
		{
			if (activeTree != currentTree)
			{
				activeTD = activeTree+'TD';
				activeTD1 = activeTree+'TD1';
				activeTD2 = activeTree+'TD2';
				lastTD = currentTree+'TD';
				lastTD1 = currentTree+'TD1';
				lastTD2 = currentTree+'TD2';
				document.all[ activeTD1 ].style.background = "#FFFFFF";
				document.all[ lastTD1 ].style.background = "transparent";
				document.all[ activeTD2 ].style.background = "#FFFFFF";
				document.all[ lastTD2 ].style.background = "transparent";
				document.all[ activeTD ].style.background = "#FFFFFF";
				document.all[ lastTD ].style.background = "transparent";
				document.all[ activeTree ].className = 'tree_nav_link_on';
				document.all[ currentTree ].className = 'tree_nav_link';
				currentTree = activeTree;
			}
		}
		</script>
</head>
<body class="color5" style="padding-top: 58px;">
 	<table cellpadding="0" cellspacing="0" border="0" width="100%" height="27">
		<tr>
			<td width="5">&nbsp;</td>
			<td width="5" id="documentsTD1" style="background:#FFFFFF;">&nbsp;</td>
			<td align="center" width="80" nowrap id="documentsTD" style="background:#FFFFFF;"><a href="javascript:parent.parent.changeTree('documents')" onFocus="if(this.blur)this.blur();" class="tree_nav_link_on" id="documents" onClick="changeStyle('documents');top.mainfrm.topfrm.changePage('none');">Documents</a></td>
			<td width="5" id="documentsTD2" style="background:#FFFFFF;">&nbsp;</td>
			<td width="5" id="mediaTD1">&nbsp;</td>
			<td align="center" width="40" nowrap id="mediaTD" style="padding-left:5px;padding-right:5px;"><a href="javascript:parent.parent.changeTree('media')" onFocus="if(this.blur)this.blur();" class="tree_nav_link" id="media" onClick="changeStyle('media');top.mainfrm.topfrm.changePage('none');">Media</a></td>
			<td width="5" id="mediaTD2">&nbsp;</td>
<!--			<td width="5" id="newsletterTD1">&nbsp;</td>
			<td align="center" width="60" nowrap id="newsletterTD" style="padding-left:5px;padding-right:5px;"><a href="javascript:parent.parent.changeTree('newsletter')" onFocus="if(this.blur)this.blur();" class="tree_nav_link" id="newsletter" onClick="changeStyle('newsletter');top.mainfrm.topfrm.changePage('none');">Newsletter</a></td>
			<td width="5" id="newsletterTD2">&nbsp;</td>-->
			<td>&nbsp;</td>
		</tr>
	</table>
</body>
</html>