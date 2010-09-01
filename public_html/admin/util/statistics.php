<?php
/**
 * Statistics class for single documents and the complete site
 */
class statistics
{
    /** 
     * Connection object and database abstraction layer
     * @type dba 
     */
    var $dba;

    /** 
     * Prefix for tables in the current installation
     * @type String
     */
    var $p;

    /**
     * Node id ( document id )
     * @type int
     */
    var $id;

    /**
     * Interval used to fetch current people visiting the site or a expecific page
     * @type int
     */
    var $interval = 200;

    /**
     * statistics constructor
     * @param dba Connection object
     */
    function statistics( $dba )
    {
      $this->dba = $dba;
      $this->p   = $dba->getPrefix();
    }
    
    /**
     * Get the total unique visits and total hits for a page
     * if a node is specified, otherwise do it for the hole site
     * @public
     * @param id Node identifier
     * @returns mixedArray
     */
    function getPermanentStats( $id = 0 )
    {
      if( $id )
      {
        $sql = "SELECT 
                  unique_visits,
                  visits,
                  DATE_FORMAT(last_visit,'%d.%m.%Y %H:%i:%s') as last_visit
                FROM
                  ".$this->p."permanent_stats
                WHERE
                  id = $id";
      }
      else
      {
        $sql = "SELECT
                  MAX( DATE_FORMAT( last_visit, '%d.%m.%Y %H:%i:%s' ) ) as last_visit,
                  sum( unique_visits ) as unique_visits_total,
                  sum( visits ) as visits_total
                FROM
                  ".$this->p."permanent_stats";
      }
      return $this->dba->singleArray( $sql );
    }

    /**
     * From wich day do we have statistic data available
     * @returns Date
     */
    function getStartStatisticData()
    {
      $sql = "SELECT
                  MIN( DATE_FORMAT(timestamp,'%d.%m.%Y %H:%i:%s') )
              FROM
                ". $this->p."stats";
      return $this->dba->singleQuery( $sql ); 
    }
    /**
     * Get the total number of visits to a page between 'from' date and 'to' date
     * @public
     * @param id  Document identifier
     * @param from Date
     * @param to Date
     * @returns int
     */
    function getTotalVisitsForPagePeriod( $id, $from = 0, $to = 0 )
    {
      if( !$from ) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );

      $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql = "SELECT 
                COUNT( * ) 
             FROM 
                ". $this->p ."stats 
             WHERE 
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '$fromDate' ) 
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '$toDate' ) 
             AND
              id= $id
             AND
              assettype='d'
            GROUP BY 
              id";
       return $this->dba->singleQuery( $sql );
    }
    function getTopVisitedPages()
    {
       $temp = array();
       $sql="SELECT 
                doc.id    AS id,
                doc.name  AS name,
                doc.title AS title,
                COUNT( stats.id ) AS total
            FROM 
              ". $this->p. "stats AS stats,
              ". $this->p. "tree  AS doc
            WHERE 
              doc.id = stats.id 
            AND 
              stats.assettype = 'd'
            GROUP BY 
              stats.id
            ORDER BY 
              total
            DESC LIMIT 0, 10"; 
       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $temp[ count( $temp ) ] = $this->dba->fetchArray( $result ); 
       }

       return $temp;
    }
    function getVisitsOverview( $id = 0, $days = 30 )
    {
      $temp = array();
      for( $i = 0; $i < $days; $i++ )
      {
        $temp[count( $temp )] = $this->getHitsOnPageOnDay( $id, $i );
      }

      return array_reverse( $temp );
    }
    function getHitsOnPageOnDay( $id = 0, $day = 0 )
    {
      /*
      //**********SCRAPBOOK***************
      //this gets the total number of hits for every hitet page on the
      //site on the given day 'd'

        SELECT COUNT( * ) 
        as total, DATE_FORMAT( timestamp, '%d.%m.%Y' ) 
        as date, id
        FROM w_stats
        WHERE TO_DAYS( NOW( ) ) - TO_DAYS( timestamp ) = 0 AND assettype = 'd'
        GROUP BY id LIMIT 0, 30 
      //**********************************
      */

      if( $id )
      {
        $sql = "SELECT 
                  COUNT( * ) as total,
                  DATE_FORMAT( timestamp, '%d.%m.%Y' ) as date
               FROM 
                  ". $this->p ."stats 
               WHERE 
                  TO_DAYS(NOW()) - TO_DAYS( timestamp ) = $day
               AND
                id= $id
               AND
                assettype='d'
              GROUP BY 
                id";
       }
       else
       {
        $sql = "SELECT 
                  COUNT( * ) as total,
                  DATE_FORMAT( timestamp, '%d.%m.%Y' ) as date
               FROM 
                  ". $this->p ."stats 
               WHERE 
                  TO_DAYS(NOW()) - TO_DAYS( timestamp ) = $day
               AND
                assettype='d'
              GROUP BY 
                assettype";
       }
       return $this->dba->singleArray( $sql );
    }
    function getUsersOnline( $id = 0 )
    {
      if( $id )
      {
        $sql = "SELECT 
                  COUNT( DISTINCT (sessid) ) 
               FROM 
                  ". $this->p ."stats 
               WHERE 
                  UNIX_TIMESTAMP( timestamp ) > ( UNIX_TIMESTAMP() - ". $this->interval ." ) 
               AND
                id= $id
               AND
                assettype='d'
              GROUP BY 
                id";
      }
      else
      {
        $sql = "SELECT 
                  COUNT( DISTINCT (sessid) ) 
               FROM 
                  ". $this->p ."stats 
               WHERE 
                  UNIX_TIMESTAMP( timestamp ) > ( UNIX_TIMESTAMP() - ". $this->interval ." ) 
               AND
                assettype='d'
               GROUP BY 
                id";
      }
       return $this->dba->singleQuery( $sql );
    }
}

