<?
    require_once("../util/dba.php");
    require_once("../util/user.php");

    session_start();
    $dba    = new dba();
    $prefix = $dba->getPrefix();
    $user   = new user( $dba );

    if( !$user->isLogged() ) die("<script language=\"JavaScript\">top.document.location.href='../log.php';</script>");
    if( !$pane ) $pane = $_GET["pane"];
    if( !$pane ) $pane = $_POST["pane"];

    $panes = array( "users"=>"Users",
    		    "roles"=>"User groups",
		    "realms"=>"Group areas"
		 );

    if( !$pane ) $pane = "users";

    switch( $pane )
    {
        case("users"):
            $paneinclude="users.php";
            break;
        case("roles"):
            $paneinclude="roles.php";
            break;
        case("realms"):
            $paneinclude="realms.php";
            break;
    }
?>
<html>
<head>
	<title>User administration</title>
	<link rel="stylesheet" href="../style/style.css" type="text/css" />
</head>
<body bgcolor="#FFFFFF" class="content_body">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td><img src="../graphics/transp.gif" /></td>
			<?foreach( $panes as $key => $value ):?>
			<td onClick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=( $pane == $key )? "_selected":"_unselected"?>.gif" /></td>
			<td onClick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'" class="faneblad<?=($pane==$key)? "_selected":"_unselected"?>" style="cursor:hand;"><?=$value?></td>
			<td onClick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane==$key )? "_selected":"_unselected"?>.gif" /></td>
			<td><img src="../graphics/transp.gif" width="4" /></td>
			<?endforeach?>
		</tr>
	</table>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="1"><img src="graphics/transp.gif" border="0" width="1" height="350" /></td>
			<td class="tdborder_content" valign="top"><?require_once( $paneinclude );?></td>
		</tr>
	</table>
</body>
</html>
