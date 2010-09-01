<?php
require_once("../util/dba.php");
require_once("../util/topic.php");
if( !$id ) $id = $_GET["id"];
if( !$id ) $id = $_POST["id"];
if( !$id ) die("Parameter id expected");

if( !$choosenIcon ) $choosenIcon = $_GET["choosenIcon"];
if( !$choosenIcon ) $choosenIcon = $_POST["choosenIcon"];

$dba = new dba();
$topic  = new topic( $dba, $id );

if( $submited || $_POST["submited"] )
{
    if( !$name ) $name = $_POST["name"];
    if( !$description ) $description = $_POST["description"];
    if( !$iconId ) $icon = $_POST["iconId"];
    if( !$iconFormat ) $icon = $_POST["iconFormat"];

    $topic->setName( $name );
    $topic->setDescription( $description );
    $topic->setIcon( $iconId, $iconFormat );

    Header("Location:index.php?pane=topics");
}
$title = "Edit topic";
?>
<?require_once("topicForm.php")?>

