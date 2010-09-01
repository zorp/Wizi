<?php
class topic
{
  var $dba;
  var $p;
  var $id;
  var $name;
  var $description;
  var $iconId;
  var $iconName;
  var $iconFormat;
  var $iconHeight;
  var $iconWidth;
  var $iconMeta;

  function topic( $dba, $id )
  {
    if( !is_numeric( $id ) ) return;
    $this->dba = $dba;
    $this->p   = $this->dba->getPrefix();
    $this->id  = $id;
    $this->load();
  }
  function load()
  {
    $sql = "SELECT
                topic.name  AS name,
                topic.description AS description,
                img.id      AS iconId,
                img.name    AS iconName,
                img.format  AS iconFormat,
                img.height  AS iconHeight,
                img.width   AS iconWidth,
                img.meta    AS iconMeta
            FROM
              ".$this->p."topics AS topic
            LEFT JOIN
              ".$this->p."mediatree AS img
            ON
              topic.icon = img.id
            WHERE
              topic.id = ". $this->id;
     $record = $this->dba->singleArray( $sql );
     
     $this->name        = stripslashes ( $record["name"] );
     $this->description = stripslashes ( $record["description"] );
     $this->iconId      = $record["iconId"];
     $this->iconName    = $record["iconName"];
     $this->iconFormat  = $record["iconFormat"];
     $this->iconHeight  = $record["iconHeight"];
     $this->iconWidth   = $record["iconWidth"];
     $this->iconMeta    = $record["iconMeta"];
  }
  function setName( $name )
  {
    if( !trim( $name ) ) return;
    $sql = "UPDATE
              ".$this->p."topics
            SET
              name = '". addslashes( trim( $name ) ) ."'
            WHERE
              id=".$this->id;
    $this->dba->exec( $sql );
  }
  function setDescription( $description )
  {
    if( !trim( $description ) ) return;
    $sql = "UPDATE
              ".$this->p."topics
            SET
              description = '". addslashes( trim( $description ) ) ."'
            WHERE
              id=".$this->id;
    $this->dba->exec( $sql );
  }
  function setIcon( $icon, $format )
  {
    if( !is_numeric( $icon ) ) $icon = 'NULL';
    $format = ( trim( $format ) )? "'$format'":'NULL';

    $sql = "UPDATE
              ".$this->p."topics
            SET
              icon = $icon,
              format = $format
            WHERE
              id=".$this->id;
    $this->dba->exec( $sql );
  }
}
?>
