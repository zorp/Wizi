<?php 
    /*********************************************************************/
    /*   index.php                                                       */
    /*                                                                   */
    /*                                                                   */
    /*********************************************************************/
    /*   main frameset for the aplication                                */
    /*                                                                   */
    /*********************************************************************/

require_once("util/dba.php");
require_once("util/user.php");
session_start();
$user = new user( new dba() );

if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Wizi Content Management System - By Verk</title>
	<script language="JavaScript" src="scripts/treemenu_frame.js"></script>
	<script language="JavaScript" src="scripts/global_funcs.js"></script>
</head>
<!-- Wizi frames -->
<frameset cols="250,*" framespacing="0" frameborder="0">
	<frame src="tree_frameset.php" name="treefrmfrm" scrolling="auto" frameborder="0" id="treefrmfrm">
	<frame name="mainfrm" src="frameset2.php" frameborder="0" scrolling="auto">
</frameset>
<!-- Wizi Noframes -->
<noframes>
<body style="background-color: #000000;">
	<H1>Wizi</H1>
	<p>Wizi kræver en browser som understøtter framesets.</p>
	<p>Denne version kræver at du benytter enten Internet Explorer 5.5 eller højere, Mozilla 1.0 eller Netscape 7.0</p>
</body>
</noframes>
</html>
