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

    $panes = array(
                    "forms"=>"Forms"
                  );
    if( !$pane ) $pane = "forms";

    switch( $pane )
    {
        case("forms"):
            $pane_include = "forms.php";
            break;
    }
?>
<html>
<head>
	<title>Forms</title>
	<link rel="stylesheet" href="../style/style.css" type="text/css">
</head>
<body class="content_body">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td><img src="../graphics/transp.gif"></td>
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
			<td width="1"><img src="graphics/transp.gif" border="0" width="1" height="350"></td>
			<td class="tdborder_content" valign="top">
				<?if( $pane_include ):?>
				<?require_once($pane_include);?>
				<?else:?>
				&nbsp;
				<?endif?>
			</td>
		</tr>
	</table>
</body>
</html>
