<?php
class overview 
{
    //=================== INSTANCE VARIABLES ======================
    var $dba;
    var $p;
    var $filter = array();
    var $interval = 200; //(seconds)
    var $totalCurrentUsers;

    //===================== CONSTRUCTOR ============================
    function overview( $dba )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
    }

    //===================== FILTERS =========================
    function getFilter( $filter )
    {
       if( !$filter ) return array();

       switch( $filter )
       {
          case('draft'):
            $this->getDraftOverview();
            break;
					case('publish'):
            $this->getPublishOverview();
            break;
          case('nav'):
            $this->getNavOverview();
            break;
          case('title'):
            $this->getTitleOverview();
            break;
          case('media'):
            $this->getMediaUsage();
            break;
          case('docs'):
            $this->getDocsUsage();
            break;
          case('wizitors'):
            $this->getVisitors();
            break;
          default:
            return array();
       }

       return $this->filter;
    }
    function getVisitors()
    {

      //Get number of current visitors to document
      $sql = "SELECT 
                COUNT( DISTINCT (sessid) ) 
             FROM 
                ". $this->p ."stats 
             WHERE 
                UNIX_TIMESTAMP( timestamp ) > ( UNIX_TIMESTAMP() - $interval ) 
             AND
              id= $docid
             AND
              assettype='d'
            GROUP BY 
              id";
  
      //Get total number of current visitors 
      $sql = "SELECT 
                COUNT( DISTINCT (sessid) ) 
             FROM 
                ". $this->p ."stats 
             WHERE 
                UNIX_TIMESTAMP( timestamp ) > ( UNIX_TIMESTAMP() - $interval ) 
             AND
              assettype='d'
            GROUP BY 
              sessid";
/*
And this is with names and titles joined from the hip!
SELECT count( stats.id ) , doc.id, doc.name, doc.title
FROM intra_stats
as stats
left join intra_tree
as doc ON stats.id = doc.id
WHERE UNIX_TIMESTAMP( timestamp ) > ( UNIX_TIMESTAMP( ) - 30000 ) AND assettype = 'd'
GROUP BY id LIMIT 0, 30
*/

      //get all entries for stats within the last 300 seconds
      $sql = "SELECT 
                sessid,
                id
              FROM 
                ".$this->p."stats
              WHERE 
                 UNIX_TIMESTAMP( timestamp ) > ( UNIX_TIMESTAMP( ) - ". $this->interval .")
              AND 
                assettype = 'd'
              ORDER BY timestamp desc";
      
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      $sessid = array();
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->getRecord( $result );
        if( !in_array( $rec[0], $sessid ) )
        {
          $this->filter[ $rec[1] ]["visitors"]++;
          $this->totalCurrentUsers++;
        }
        $sessid[ count( $sessid ) ] = $rec[0];
      }
    }
    function getDocsUsage()
    {
      //get Documents as includes
      $sql = "SELECT
                internal
              FROM
                  ".$this->p."includes
              WHERE
                type='d'";
             
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->getRecord( $result );
        $this->filter[ $rec[0] ]["state"] = true;
      }

      //get documents as references
      $sql = "SELECT
                reference
              FROM
                ".$this->p."references
              WHERE
                 reference_type = 'd'";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->getRecord( $result );
        $this->filter[ $rec[0] ]["state"] = true;
      }
    }
    function getMediaUsage()
    {
      //get pictures as includes
      $sql = "SELECT
                internal
              FROM
                  ".$this->p."includes
              WHERE
                type='m'";
             
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->getRecord( $result );
        $this->filter[ $rec[0] ]["state"] = true;
      }

      //get pictures as references
      $sql = "SELECT
                reference
              FROM
                ".$this->p."references
              WHERE
                ( reference_type = 'm' OR reference_type = 'l' )";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->getRecord( $result );
        $this->filter[ $rec[0] ]["state"] = true;
      }
    }
    function getPublishOverview( )
    {
      //get all the unpublished document
      $sql = "SELECT 
                id
              FROM
                ".$this->p."tree
              WHERE
                    ( timepublish > NOW() )
              OR 
                    ( timeunpublish < NOW() )";
      
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->getRecord( $result );
        $this->filter[ $rec[0] ]["state"] = true;
      }

      //get all the documents scheduled to be taken down
      $sql = "SELECT 
                id,
                DATE_FORMAT(timeunpublish,'%d %m %Y') 
              FROM
                ".$this->p."tree
              WHERE
                    timeunpublish > NOW()
              AND
                    timeunpublish IS NOT NULL";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->getRecord( $result );
        $this->filter[ $rec[0] ]["unpublish"] = $rec[1];
      }

      //get all the documents scheduled to be publish
      $sql = "SELECT 
                id,
                DATE_FORMAT(timepublish,'%d %m %Y') 
              FROM
                ".$this->p."tree
              WHERE
                    timepublish > NOW()
              AND
                    timepublish IS NOT NULL";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->getRecord( $result );
        $this->filter[ $rec[0] ]["publish"] = $rec[1];
      }
    }
    function getNavOverview( )
    {
      $sql = "SELECT 
                id
              FROM
                ".$this->p."tree
              WHERE
                nav=0";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->getRecord( $result );
        $this->filter[ $rec[0] ]["state"] = true;
      }
    }
    function getTitleOverview( )
    {
      $sql = "SELECT 
                id
              FROM
                ".$this->p."tree
              WHERE
                ( title IS NULL OR LENGTH( title ) < 2 )";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->getRecord( $result );
        $this->filter[ $rec[0] ]["state"] = true;
      }
      return array();
    }
		function getDraftOverview( )
    {
      $sql = "SELECT 
                id
              FROM
                ".$this->p."tree
              WHERE
                isdraft = 1";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->getRecord( $result );
        $this->filter[ $rec[0] ]["state"] = true;
      }
      return array();
    }

}
?>
