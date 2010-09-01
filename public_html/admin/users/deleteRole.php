<?
    require_once("../util/dba.php");
		require_once("../util/user.php");
    require_once("../util/users.php");
		require_once("../util/roles.php");
    session_start();
    $dba = new dba();
		
		$user = new user( $dba );
    if( !$user->isLogged() ) die("<script language=\"JavaScript\">top.document.location.href='log.php'</script>");

    if( !$id ) $id = $_GET["id"];
    if( !$id ) $id = $_POST["id"];

		if( $cancel || $_POST["cancel"] ) Header("Location:index.php?pane=roles" );
	
    if(   $warned || $_POST["warned"]  )
    {
				$roles     = new roles($dba);
				$roles->deleteRole( $id );
        Header("Location:index.php?pane=roles" ); 
    }
    else
    {
        $delRole = $_GET["rolename"];
    }
		
		$path = "../";
		$message = "Are you sure you want to remove role: ". $delRole . "?";
		$message.= "<input type=\"hidden\" name=\"id\" value=\"$id\">";
		$submit = "warned";
		$cancel = "cancel";
?>
<?require_once("../alert.php");?>