<?php
    require_once("../util/dba.php");
    require_once("../util/user.php");
    require_once("../util/role.php");
    require_once("../util/roles.php");
     
    $dba   = new dba();
    session_start();
    $user  = new user( $dba );
    if( !$user->isLogged() ) die("<script language=\"javascript\">top.document.location.href='../log.php'</script>");

    $roles = new roles( $dba );
    if( $submited || $_POST["submited"] )
    {
    	if( !$name ) $name = $_POST["name"];
    	if( !$description ) $description = $_POST["description"];
    	if( !$users ) $users = $_POST["users"];

        $role = new role( $dba, $roles->addRole() );    
        $role->setName( $name );
        $role->setDescription( $description );
        $role->setUser( explode( ",",$users ) );

        Header("Location:index.php?pane=roles" ); 
    }
    $itemList = $roles->user2roles( );
    $title = "Add new role";
?>
<html>
<head>
	<title>Group administration</title>
	<link rel="stylesheet" href="../style/style.css" type="text/css">
</head>
<body class="content_body">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td><img src="../graphics/transp.gif"></td>
			<td><img src="../graphics/horisontal_button/left_selected.gif"></td>
			<td class="faneblad_selected">Add group</td>
			<td><img src="../graphics/horisontal_button/right_selected.gif"></td>
		</tr>
	</table>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="1"><img src="../graphics/transp.gif" border="0" width="1" height="350"></td>
			<td class="tdborder_content" valign="top"><?require_once("roleForm.php");?></td>
		</tr>
	</table>
</body>
</html>
