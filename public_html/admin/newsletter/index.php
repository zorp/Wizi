<?
    require_once("../util/dba.php");
    require_once("../util/user.php");
    require_once("../util/newsletter.php");
		require_once("email_functions.php");

    session_start();
    $dba    = new dba();
    $user   = new user( $dba );

    if(!$id) $id = $_GET["id"];
    if(!$id) $id = $_POST["id"];
    if(!$id) die("Parameter spected id ");

    if(!$pane ) $pane = $_GET["pane"];
    if(!$pane ) $pane = $_POST["pane"];

    if( !$user->isLogged() ) die("<script language=\"JavaScript\">top.document.location.href='../log.php';</script>");

    $newsletter  = new newsletter( $dba, $id );

    if ($id !=1)
		{
			$panes = array( "settings"=>"Edit",
											"email"=>"Email the newsletter",
											"testemail"=>"Test the newsletter"
			 );

	    if( !$pane ) 
	    {
				$pane = $user->pane;
	    	if( !$panes[ $pane ] ) $pane = "settings";
	    }
	    else
	    {
	    	//the user has active selected a pane, make it a default
				$user->setPane( $pane );
	    }
	
	    switch( $pane )
	    {
	        case("settings"):
	            $paneinclude="settings.php";
	            break;
					case("email"):
	            $paneinclude="email.php";
	            break;
					case("testemail"):
	            $paneinclude="testemail.php";
	            break;
	    }
		}
		else
		{
			$panes = array( "subscribers"=>"Newsletter Subscribers",
											"data"=>"Standard settings"
			);
			if( !$pane ) 
	    {
				$pane = $user->pane;
	    	if( !$panes[ $pane ] ) $pane = "subscribers";
	    }
			switch( $pane )
	    {
	        case("subscribers"):
	            //$paneinclude="subscribers.php";
							$paneinclude="forms.php";
	            break;
					case("data"):
	            $paneinclude="data.php";
	            break;
	    }
		}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Newsletter administration</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
					<script language="JavaScript" type="text/javascript">
					function emailThis()
					{
						document.my_form.pane = "email";
						document.my_form.submit();
					}
					function deleteSubscriber( id )
					{
						if( confirm('Delete?') )
						{
							document.location.href = "<?=$_SERVER["PHP_SELF"]?>?id=1&subscriberDel="+id;
						}
					}
				</script>
				<script language="JavaScript" src="../scripts/global_funcs.js"></script>
    </head>
		<body bgcolor="#FFFFFF" class="content_body">
    <table cellpadding="0" cellspacing="0" border="0">
          <tr id="panes">

	    <?foreach( $panes as $key => $value ):?>
		    <td id="<?=$key?>0"><img src="../graphics/transp.gif"></td>
		    <td id="<?=$key?>1" onClick="document.location.href='<?=$_SERVER["PHP_SELF"]?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=( $pane == $key )? "_selected":"_unselected"?>.gif"></td>
		    <td id="<?=$key?>2" onClick="document.location.href='<?=$_SERVER["PHP_SELF"]?>?id=<?=$id?>&pane=<?=$key?>'" class="faneblad<?=( $pane == $key )? "_selected":"_unselected"?>" style="cursor:hand;" ><?=$value?> </td>
		    <td id="<?=$key?>3" onClick="document.location.href='<?=$_SERVER["PHP_SELF"]?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane==$key )? "_selected":"_unselected"?>.gif"></td>

		    <td id="<?=$key?>4"><img src="../graphics/transp.gif" width="4"></td>
	    <?endforeach?>
     </tr>
    </table>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="1"> <img src="../graphics/transp.gif" border="0" width="1" height="350"> </td>
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

