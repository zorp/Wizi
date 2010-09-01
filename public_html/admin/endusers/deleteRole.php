<?php
    require_once("../util/dba.php");
    require_once("../util/user.php");
    require_once("../util/endUserRoles.php");
    require_once("../util/endUserRole.php");

    session_start();
    $dba = new dba();

    $user = new user( $dba );
    if( !$id ) $id = $_GET["id"];
    if( !$id ) $id = $_POST["id"];

    if( !$user->isLogged() ) die("<script language=\"JavaScript\">top.document.location.href='log.php'</script>");

    if( $cancel || $_POST["cancel"] ) Header("Location:index.php?pane=roles" ); 
    if( $warned || $_POST["warned"]  )
    {
        $roles = new endUserRoles($dba);
        $roles->deleteRole( $id );
        Header("Location:index.php?pane=roles" ); 
    }
    else
    {
        $delRole = new endUserRole( $dba, $id );
    }
		$path = "../";
		$message = "Are you sure you want to remove the restriction: \"". $delRole->name . "\"?";
		$message.= "<input type=\"hidden\" name=\"id\" value=\"$id\">";
		$submit = "warned";
		$cancel = "cancel";
?>
<?require_once("../alert.php");?>
