<?
    require_once("../util/dba.php");
    require_once("../util/user.php");
    require_once("../util/media.php");

    session_start();
    $dba    = new dba();
    $user   = new user( $dba );

    if(!$id) $id = $_GET["id"];
    if(!$id) $id = $_POST["id"];
    if(!$id) die("Parameter spected id ");

    if(!$pane ) $pane = $_GET["pane"];
    if(!$pane ) $pane = $_POST["pane"];

    if( !$user->isLogged() ) die("<script language=\"JavaScript\">top.document.location.href='../log.php';</script>");

    $media  = new media( $dba, $id );

    $panes = array( "settings"=>"Properties",
		    //"preview"=>"Preview",
				//"edit"=>"Edit", UNDER DEVELOPMENT .... may never finish
		    "dependencies"=>"Dependencies",
				"statistics"=>"Statistics"
		 );

    if( !$pane ) 
    {
	$pane = $user->pane;
    	if( !$panes[ $pane ] ) $pane = "settings";
    }
    else
    {
    	//the user has active selected a pane, make it a default
	$user->setPane( $pane );
    }

    switch( $pane )
    {
        case("settings"):
            $paneinclude="settings.php";
            break;
        //case("preview"):
        //    $paneinclude="preview.php";
        //    break;
				//case("edit"):
        //    $paneinclude="editimage.php";
        //    break;
        case("dependencies"):
            $paneinclude="dependencies.php";
            break;
				case("statistics"):
            $paneinclude="statistics.php";
            break;
    }
?>
<html>
    <head>
        <title>Media administration</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" class="content_body">
    <table cellpadding="0" cellspacing="0" border="0">
          <tr>

	    <?foreach( $panes as $key => $value ):?>
		    <td><img src="../graphics/transp.gif"></td>
		    <td onClick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=( $pane == $key )? "_selected":"_unselected"?>.gif"></td>
		    <td  onclick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'"class="faneblad<?=( $pane == $key )? "_selected":"_unselected"?>" style="cursor:hand;" ><?=$value?> </td>
		    <td onClick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane==$key )? "_selected":"_unselected"?>.gif"></td>

		    <td><img src="../graphics/transp.gif" width="4"></td>
	    <?endforeach?>
          </tr>
       </tr>
    </table>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="1"> <img src="../graphics/transp.gif" border="0" width="1" height="350"> </td>
            <td class="tdborder_content" valign="top">
                <!--include pane-->
                <?if( $paneinclude ):?>
                    <?require_once( $paneinclude );?>
                <?else:?>
                    &nbsp;
                <?endif?>
            </td>
       </tr>
      </table>
    </body>
</html>

