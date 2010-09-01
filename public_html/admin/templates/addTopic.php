<?php
require_once("../util/dba.php");
require_once("../util/topics.php");
require_once("../util/topic.php");
$dba = new dba();

if( $_POST["submited"] )
{
    $topics = new topics( $dba );
    $topic  = new topic( $dba, $topics->addTopic() );

    if( !$name ) $name = $_POST["name"];
    if( !$description ) $description = $_POST["description"];
    if( !$iconId ) $icon = $_POST["iconId"];
    if( !$iconFormat ) $icon = $_POST["iconFormat"];

    $topic->setName( $name );
    $topic->setDescription( $description );
    $topic->setIcon( $iconId, $iconFormat );

    Header("Location:index.php?pane=topics");
}
$title = "Add topic";
?>
<?php require_once("topicForm.php")?>
