<?
    require_once("../util/dba.php");
    require_once("../util/user.php");

    session_start();
    $dba    = new dba();
    $user   = new user( $dba );
    unset( $include_pane );

    if( !$user->isLogged() ) die("<script language=\"JavaScript\">top.document.location.href='../log.php';</script>");
    if( !$pane ) $pane = $_GET["pane"];
    if( !$pane ) $pane = $_POST["pane"];
    if( !$pane ) $pane = "users";

    switch( $pane )
    {
        case( $pane == "users" ): 
            $include_pane = "endUsers.php";
            break;
        case( $pane == "roles" ): 
            $include_pane = "roles.php";
            break;
        case( $pane == "realms" ): 
            $include_pane = "realms.php";
            break;
				case( $pane == "forwards" ): 
            $include_pane = "forwards.php";
            break;
	default:
            $include_pane = "endUsers.php";
    }
?>
<html>
	<head>
		<title>End user administration</title><link rel="stylesheet" href="../style/style.css" type="text/css">
	</head>
	<body bgcolor="#FFFFFF" class="content_body">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><img src="../graphics/transp.gif"></td>
				<td onClick="document.location.href='<?=$PHP_SELF?>?pane=users'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=($pane=="users")? "_selected":"_unselected"?>.gif"></td>
				<td onClick="document.location.href='<?=$PHP_SELF?>?pane=users'" class="faneblad<?=($pane=="users")? "_selected":"_unselected"?>" style="cursor:hand;">Website users</td>
				<td onClick="document.location.href='<?=$PHP_SELF?>?pane=users'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane=="users")? "_selected":"_unselected"?>.gif"></td>
				<td><img src="../graphics/transp.gif" width="4"></td>
				<td onClick="document.location.href='<?=$PHP_SELF?>?pane=roles'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=($pane=="roles")? "_selected":"_unselected"?>.gif"></td>
				<td onClick="document.location.href='<?=$PHP_SELF?>?pane=roles'" class="faneblad<?=($pane=="roles")? "_selected":"_unselected"?>" style="cursor:hand;">Website restrictions</td>
				<td onClick="document.location.href='<?=$PHP_SELF?>?pane=roles'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane=="roles")? "_selected":"_unselected"?>.gif"></td>
				<td><img src="../graphics/transp.gif" width="4"></td>
				<td onClick="document.location.href='<?=$PHP_SELF?>?pane=realms'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=($pane=="realms")? "_selected":"_unselected"?>.gif"></td>
				<td onClick="document.location.href='<?=$PHP_SELF?>?pane=realms'" class="faneblad<?=($pane=="realms")? "_selected":"_unselected"?>" style="cursor:hand;">Restriction areas</td>
				<td onClick="document.location.href='<?=$PHP_SELF?>?pane=realms'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane=="realms")? "_selected":"_unselected"?>.gif"></td>
				<td><img src="../graphics/transp.gif" width="4"></td>
				<td onClick="document.location.href='<?=$PHP_SELF?>?pane=forwards'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=($pane=="forwards")? "_selected":"_unselected"?>.gif"></td>
				<td onClick="document.location.href='<?=$PHP_SELF?>?pane=forwards'" class="faneblad<?=($pane=="forwards")? "_selected":"_unselected"?>" style="cursor:hand;">User logon forwarding</td>
				<td onClick="document.location.href='<?=$PHP_SELF?>?pane=forwards'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane=="forwards")? "_selected":"_unselected"?>.gif"></td>
			</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="1"><img src="graphics/transp.gif" border="0" width="1" height="350"></td>
				<td class="tdborder_content" valign="top"><?require_once($include_pane);?></td>
			</tr>
		</table>
	</body>
</html>
