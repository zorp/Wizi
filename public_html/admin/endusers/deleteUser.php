<?
    require_once("../util/dba.php");
    require_once("../util/user.php");
    require_once("../util/endUser.php");
    require_once("../util/endUsers.php");
    session_start();
    $dba = new dba();

    $user = new user( $dba );
    if( !$id ) $id = $_GET["id"];
    if( !$id ) $id = $_POST["id"];

    if( !$user->isLogged() ) die("<script language=\"JavaScript\">top.document.location.href='log.php'</script>");
    if( $cancel || $_POST["cancel"] ) Header("Location:index.php" ); 
		
    if( $warned || $_POST["warned"]  )
    {
        $users = new endUsers( $dba );
        $users->deleteUser( $id );
        Header("Location:index.php" ); 
    }
    else
    {
        $delUser = new endUser( $dba, $id );
    }
		$path = "../";
		$message = "Are you sure you want to remove user: ". $delUser->name . "?";
		$message.= "<input type=\"hidden\" name=\"id\" value=\"$id\">";
		$submit = "warned";
		$cancel = "cancel";
?>
<?require_once("../alert.php");?>
