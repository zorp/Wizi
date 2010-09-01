<?php 
    /*********************************************************************/
    /*   main.php                                                        */
    /*                                                                   */
    /*                                                                   */
    /*********************************************************************/
    if( !$pane ) $pane = $_GET["pane"];
    if( !$pane ) $pane = $_POST["pane"];
    if( !$PHP_SELF ) $_SERVER["PHP_SELF"];
    
    if( !$logoff ) $logoff = $_POST["logoff"];
    if( $logoff ) die("<script>top.document.location.href='../log.php';</script>");

    require("../util/dba.php");
    require("../util/user.php");

    session_start();
    $dba  = new dba();
    $user = new user( $dba );
    if( !$user->isLogged() ) die("<script>top.document.location.href='../log.php';</script>");

    $panes = array( "welcome"=>"Welcome",
    		            "settings"=>"Your personal settings",
		                "logoff"=>"Log off"
		 );
    if( !$pane ) $pane = "welcome";
    if( !$panes[ $pane ] ) $pane = "welcome";

    switch( $pane )
    {
        case("welcome"):
            $paneinclude="welcome.php";
            break;
        case("settings"):
            $paneinclude="settings.php";
            break;
        case("logoff"):
            $paneinclude="logoff.php";
            break;
    }
?>
<html>
<head>
<title>Home</title>
	<link rel="stylesheet" href="../style/style.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" class="content_body">
<!--referer <?=$referer?> ref name: <?=$referer_name?>-->
<form name="tree" action="<?=$PHP_SELF?>" method="post">
	<table cellpadding="0" cellspacing="0" border="0">
 	<tr>
			<td><img src="../graphics/transp.gif" /></td>
   <?foreach( $panes as $key => $value ):?>
   <td onClick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=( $pane == $key )? "_selected":"_unselected"?>.gif"></td>
   <td  onclick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'"class="faneblad<?=( $pane == $key )? "_selected":"_unselected"?>" style="cursor:hand;" ><?=$value?> </td>
   <td onClick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane==$key )? "_selected":"_unselected"?>.gif"></td>
   <td><img src="../graphics/transp.gif" width="4"></td>
   <?endforeach?>
  </tr>
 </table>
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="table-layout:fixed; min-height:350px;">
	<tr>
		<td class="tdborder_content" valign="top" style="min-height:350px;">
  	<?if($paneinclude ) require_once( $paneinclude )?>&nbsp;
  </td>
 </tr>
</table>
</form>
</body>
</html>