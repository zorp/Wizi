<?php
    require_once("../util/dba.php");
    require_once("../util/user.php");
    require_once("../util/role.php");
    require_once("../util/roles.php");
     
    $dba   = new dba();
    session_start();
    $user  = new user( $dba );
    if( !$user->isLogged() ) die("<script language=\"javascript\">top.document.location.href='../log.php'</script>");

    if( !$id ) $id = $_GET["id"];
    if( !$id ) $id = $_POST["id"];
    if( !$id ) die( "Parameter required: id ");

    $role  = new role( $dba, $id );

    if( $submited || $_POST["submited"] )
    {
        if( !$name ) $name = $_POST["name"];
        if( !$description ) $description = $_POST["description"];
        if( !$users ) $users = $_POST["users"];

        $role->setName( $name );
        $role->setDescription( $description );
        $role->setUser( explode( ",",$users ) );

        Header("Location:index.php?pane=roles" ); 
    }
    $roles = new roles( $dba );    
    $itemList = $roles->user2roles( $role->id );

    if( !$pane ) $pane = $_GET["pane"];
    if( !$pane ) $pane = $_POST["pane"];
    if( !$pane ) $pane = "edit";

    switch( $pane )
    {
        case("edit"):
            $paneinclude="roleForm.php";
            break;
    }
    $title = "Edit properties for \"". $role->name ."\"";
?>
<html>
    <head>
        <title>Group administration</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" class="content_body">
    <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td><img src="../graphics/transp.gif"></td>
            <td onclick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=edit'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=($pane=="edit")? "_selected":"_unselected"?>.gif"></td>
            <td  onclick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=edit'"class="faneblad<?=($pane=="edit")? "_selected":"_unselected"?>" style="cursor:hand;">Properties for <?=$role->name?> group</td>
            <td onclick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=edit'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane=="edit")? "_selected":"_unselected"?>.gif"></td>
        </tr>
    </table>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="1"> <img src="../graphics/transp.gif" border="0" width="1" height="350"> </td>
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
