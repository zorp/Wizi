<?php
class topics
{
    var $dba;
    var $p;
    var $records;

    function topics( $dba )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
    }
    function getTopics( )
    {
      $sql = "SELECT
                topic.id    AS id,
                topic.name  AS name,
                topic.description AS description,
                img.id      AS iconId,
                img.format  AS iconFormat,
                img.height  AS iconHeight,
                img.width   AS iconWidth,
                img.meta    AS iconMeta
            FROM
              ".$this->p."topics AS topic
            LEFT JOIN
              ".$this->p."mediatree AS img
            ON
              topic.icon = img.id";
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $this->records[$i] = $this->dba->fetchArray( $result );
				$this->records[$i]["name"] = stripslashes ( $this->records[$i]["name"] );
				$this->records[$i]["description"] = stripslashes ( $this->records[$i]["description"] );
      }

      return $this->records;
    }
    function addTopic( )
    {
      $sql = "INSERT INTO 
             ".$this->p."topics
             ( name )
             VALUES( 'new topic' )";
      $this->dba->exec( $sql );
      return $this->dba->last_inserted_id();
    }
    function remove( $id )
    {
      if( !is_numeric( $id ) ) return;
      $sql = "DELETE FROM
            ".$this->p."topics
            WHERE
              id=$id";
      $this->dba->exec( $sql );
    }
}
?>
