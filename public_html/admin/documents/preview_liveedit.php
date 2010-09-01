<?php
    require_once("../util/dba.php");
    require_once("../util/user.php");
    require_once("../util/document.php");
		
		if(!$id) $id = $_GET["id"];
		if(!$id) $id = $_POST["id"];
    if(!$id) die("Parameter spected id ");

    session_start();
    $dba    = new dba();
    $prefix = $dba->getPrefix();
    $user   = new user( $dba );
    if( !$user->isLogged() ) die("You are not logged in, please do so.");
		
    $document = new document( $dba, $id );
		$document->loadProperties();
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Live Content for the page "<?=$document->name?>"</title>
	<link href="../../styles/shared.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
	<link href="../../styles/styles.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
</head>

<body bgcolor="#FFFFFF">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
	<tr>
		<td style="padding:10px;" align="left" valign="top"><?=$document->getTranslatedContent()?></td>
	</tr>
</table>
</body>
</html>