<?
		require_once("../util/dba.php");
		require_once("../util/user.php");
		require_once("../util/document.php");
		
		session_start();
		$dba    = new dba();
		$prefix = $dba->getPrefix();
		$user   = new user( $dba );
		if( !$user->isLogged() ) die("<script language=\"JavaScript\">top.document.location.href='../log.php';</script>");
		
		if( !$referer ) $referer = $_GET["referer"];
		if( !$referer ) $referer = $_POST["referer"];

    if(!$id) $id = $_GET["id"];
		if(!$id) $id = $_POST["id"];
    if(!$id) die("Parameter spected id ");

    if( !$content ) $content = $_POST["content"];

    if( !$pane ) $pane = $_GET["pane"];
    if( !$pane ) $pane = $_POST["pane"];

    $constrains = $user->getConstrainsOnDoc( $id );

    $panes = array( "edit"=>"Edit",
    		"settings"=>"Properties",
		    "includes"=>"Includes",
		    "layout"=>"Layout",
		    "dependencies"=>"Dependencies",
		    "history"=>"History",
        "statistics"=>"Statistics"
		 );

    if( !$pane )
    {
    	//try to get the pane from the users properties
      $pane = $user->pane;
      //check if the pane is on this section lists
      if( !$panes[ $pane ] ) $pane = "edit";
    }
    else
    {
    	//the user has active selected a pane, make it a default
	    $user->setPane( $pane );
    }

    switch( $pane )
    {
        case("edit"):
	    $paneinclude = ( !$constrains["Edit"] )?"preview.php":"edit.php";
            break;
        case("settings"):
	    $paneinclude = ( !$constrains["Properties"] )?"settings_preview.php":"settings.php";
            break;
        case("dependencies"):
            $paneinclude="dependencies.php";
            break;
        case("history"):
            $paneinclude="history.php";
            break;
        case("includes"):
            $paneinclude="includes.php";
            break;
        case("layout"):
            $paneinclude="layout.php";
            break;
        case("statistics"):
            $paneinclude="statistics.php";
            break;
    }

    $document = new document( $dba, $id );
    $document->loadProperties();
?>
<html>
	<head>
		<title>User administration</title>
		<link rel="stylesheet" href="../style/style.css" type="text/css">
		<script language="JavaScript" src="../scripts/global_funcs.js"></script>
		<script>
		function updateAction (state)
		{
			document.edit.action.value = state;
			document.edit.submit();
		}
		function restoreLive (state)
		{
			if( confirm('Restoring live content, will erase your current draft.\rSure you want to restore live content?') )
    	{
				document.edit.action.value = state;
				document.edit.submit();
			}
		}
		</script>
	</head>
	<body bgcolor="#FFFFFF" class="content_body">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><img src="../graphics/transp.gif"></td>
				<? foreach( $panes as $key => $value ):?>
				<td onclick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=( $pane == $key )? "_selected":"_unselected"?>.gif"></td>
				<td  onclick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'"class="faneblad<?=( $pane == $key )? "_selected":"_unselected"?>" style="cursor:hand;" ><?=$value?></td>
				<td onclick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane==$key )? "_selected":"_unselected"?>.gif"></td>
				<td><img src="../graphics/transp.gif" width="4"></td>
		    <? endforeach?>
			</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="1"><img src="../graphics/transp.gif" border="0" width="1" height="350"></td>
				<td class="tdborder_content" valign="top">
					<!--include pane-->
					<? if( $paneinclude ):?>
						<? require_once( $paneinclude );?>
					<? else:?>
						&nbsp;
					<? endif?>
				</td>
			</tr>
		</table>
	</body>
</html>

