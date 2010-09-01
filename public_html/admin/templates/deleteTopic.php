<?
    require_once("../util/dba.php");
    require_once("../util/user.php");
    require_once("../util/topic.php");
    require_once("../util/topics.php");
    session_start();
    $dba = new dba();

    $user = new user( $dba );
    if( !$user->isLogged() ) die("<script language=\"JavaScript\">top.document.location.href='log.php'</script>");
    
    if( !$id ) $id = $_GET["id"];
    if( !$id ) $id = $_POST["id"];

		if( $cancel || $_POST["cancel"] ) Header("Location:index.php?pane=topics" ); 
	
    if(   $warned || $_POST["warned"]  )
    {
				$topics = new topics( $dba );
        $topics->remove( $id );
        Header("Location:index.php?pane=topics" ); 
    }
    else
    {
        $topic = new topic( $dba, $id );
    }
		
		$path = "../";
		$message = "Are you sure you want to remove the topic ". $topic->name . "?";
		$message.= "<input type=\"hidden\" name=\"id\" value=\"$id\">";
		$submit = "warned";
		$cancel = "cancel";
?>
<?require_once("../alert.php");?>

