<?php
    require_once("../util/dba.php");
    require_once("../util/user.php");
    require_once("../util/endUserRole.php");
    require_once("../util/endUserRoles.php");
     
    $dba   = new dba();
    session_start();
    $user  = new user( $dba );
    if( !$user->isLogged() ) die("<script language=\"javascript\">top.document.location.href='../log.php'</script>");

    if( !$id ) $id = $_GET["id"];
    if( !$id ) $id = $_POST["id"];
    if( !$id ) die( "Parameter required: id ");

    $role  = new endUserRole( $dba, $id );

    if( !$constrain ) $constrain = $_POST["constrain"];
    if( !$constrain ) $constrain = $role->constrain;

    if( $submited || $_POST["submited"] )
    {
        if( !$description ) $description = $_POST["description"];
				if( !$name ) $name = $_POST["name"];
        if( !$users ) $users         = $_POST["users"];
        if( !$password ) $password   = $_POST["password"];
        if( !$showLogin ) $showLogin = $_POST["showLogin"];

        $role->setName( $name );
        $role->setPassword( $password );
        $role->setDescription( $description );
        $role->setUser( explode( ",",$users ) );
        $role->setConstrain( $constrain );
        $role->setShowLogin( $showLogin );

        Header("Location:index.php?pane=roles" ); 
    }

    $roles = new endUserRoles( $dba );    
    $itemList = $roles->user2roles( $role->id );
    $constrains = $roles->getAllConstrains();

    $paneinclude="roleForm.php";
    $title = "Properties for restriction \"". $role->name ."\"";
?>
<html>
	<head>
		<title>Role administration</title>
		<link rel="stylesheet" href="../style/style.css" type="text/css">
	</head>
	<body bgcolor="#FFFFFF" class="content_body">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><img src="../graphics/transp.gif"></td>
				<td style="cursor:hand;"><img src="../graphics/horisontal_button/left_selected.gif"></td>
				<td class="faneblad_selected" style="cursor:hand;">Properties for restriction <?=$role->name?></td>
				<td style="cursor:hand;"><img src="../graphics/horisontal_button/right_selected.gif"></td>
			</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="1"><img src="../graphics/transp.gif" border="0" width="1" height="350"></td>
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
