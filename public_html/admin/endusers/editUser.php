<?php
    /*********************************************************************/
    /*   editUser.php                                                    */
    /*********************************************************************/
    require_once("../util/dba.php");
    require_once("../util/user.php");
    require_once("../util/endUser.php");


    session_start();
    $user   = new user( new dba() );
    if( !$user->isLogged() ) Header("Location:log.php");
    
    if( !$id ) $id = $_GET["id"];
    if( !$id ) $id = $_POST["id"];

    $editUser = new endUser( new dba(), $id );

    if( $submited || $_POST["submited"] )
    {
        if( !$name ) $name = $_POST["name"];
        if( !$full_name ) $full_name = $_POST["full_name"];
        if( !$password ) $password = $_POST["password"];
        if( !$confirm_password ) $confirm_password = $_POST["confirm_password"];
        if( !$mail ) $mail = $_POST["mail"];

        if( $password == $confirm_password )
        {
            $editUser->setName( $name );
            $editUser->setFull_name( $full_name );
            $editUser->setPassword( $password );
            $editUser->setMail( $mail );

            Header("Location:index.php");
        }
        else
        {
            $editUser->mail = $mail;
            $editUser->name = $name;
            $editUser->full_name = $full_name;
            $message = "The passwords you typed didn't match";  
        }
    }

    $title = "Edit user settings for ";
    $title.= ( $editUser->full_name )? $editUser->full_name: $editUser->name;
?>
<?require_once("userForm.php");?>
