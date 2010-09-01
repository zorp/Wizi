<?php
    /*********************************************************************/
    /*   addUser.php                                                    */
    /*********************************************************************/
    require_once("../util/dba.php");
    require_once("../util/endUser.php");
    require_once("../util/endUsers.php");

    session_start();

    if( $submited || $_POST["submited"] )
    {
    	if( !$name )      $name = $_POST["name"];
    	if( !$full_name ) $full_name = $_POST["full_name"];
    	if( !$password )  $password = $_POST["password"];
      if( !$confirm_password ) $confirm_password = $_POST["confirm_password"];
    	if( !$mail )      $mail = $_POST["mail"];

      
      if( $password == $confirm_password )
      {
          $users    = new endUsers( new dba() );
          $editUser = new endUser( new dba(), $users->addUser() );
          $editUser->setName( $name );
          $editUser->setFull_name( $full_name );
          $editUser->setPassword( $password );
          $editUser->setMail( $mail );
          Header("Location:index.php" ); 
      }
      else
      {
        $editUser->mail = $mail;
        $editUser->name = $name;
        $editUser->full_name = $full_name;
        $message = "The passwords you typed didn't match";  
      } 
    }
    $title   = "Add user ";
?>
<?require_once("userForm.php");?>
