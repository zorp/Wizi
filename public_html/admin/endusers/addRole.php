<?php
    require_once("../util/dba.php");
    require_once("../util/user.php");
    require_once("../util/endUserRoles.php");
    require_once("../util/endUserRole.php");

    $dba   = new dba();
    session_start();
    $user  = new user( $dba );

    if( !$user->isLogged() ) die("<script language=\"javascript\">top.document.location.href='../log.php'</script>");

    $roles = new endUserRoles( $dba );
    if( $submited || $_POST["submited"] )
    {
     if( !$name ) $name = $_POST["name"];
     if( !$description ) $description = $_POST["description"];
     if( !$users ) $users = $_POST["users"];
        if( !$password ) $password = $_POST["password"];
        if( !$constrain ) $constrain = $_POST["constrain"];
        if( !$showLogin ) $showLogin = $_POST["showLogin"];

        $role = new endUserRole( $dba, $roles->addRole() );    
        $role->setName( $name );
        $role->setPassword( $password );
        $role->setDescription( $description );
        $role->setUser( explode( ",",$users ) );
        $role->setConstrain( $constrain );
        $role->setShowLogin( $showLogin );

        Header("Location:index.php?pane=roles" ); 
    }
    if( !$constrain ) $constrain = $_POST["constrain"];

    $itemList = $roles->user2roles( );
    $constrains = $roles->getAllConstrains();
    $title = "Properties for new restriction";
?>
<html>
<head>
	<title>Group administration</title>
	<link rel="stylesheet" href="../style/style.css" type="text/css"/>
</head>
<body bgcolor="#FFFFFF" class="content_body">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td><img src="../graphics/transp.gif"/></td>
			<td style="cursor:hand;"><img src="../graphics/horisontal_button/left_selected.gif"/></td>
			<td class="faneblad_selected" style="cursor:hand;">Add restriction</td>
			<td style="cursor:hand;"><img src="../graphics/horisontal_button/right_selected.gif"/></td>
		</tr>
	</table>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="1"><img src="../graphics/transp.gif" border="0" width="1" height="350"/></td>
			<td class="tdborder_content" valign="top"><?require_once("roleForm.php");?></td>
		</tr>
	</table>
</body>
</html>
