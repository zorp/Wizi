<?php
  require_once("../util/forms.php");
  require_once("../util/dba.php");

  if( !$id ) $id = $_GET["id"];
  if( !$id ) die("Id paramater expected");

  $dba = new dba();
  $forms = new forms( $dba );
  $forms->form( $id );

  if( $cancel || $_POST["cancel"] ) Header("Location:index.php?pane=forms" ); 
  if( $warned || $_POST["warned"] )
  {
      $forms->remove( $id );
      Header("Location:index.php?pane=forms" ); 
  }

  $path = "../";
  $message = "Are you sure you want to remove the form: '". $forms->name . "'?";
  $message.= "<input type=\"hidden\" name=\"id\" value=\"$id\">";
  $submit = "warned";
  $cancel = "cancel";
?>
<?require_once("../alert.php");?>
