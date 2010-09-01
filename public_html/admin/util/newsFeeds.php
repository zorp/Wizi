<?php
/*
require_once("dba.php");
require_once("rss_parser.php");
require_once("rdf_parser.php");
//instantiate feeds object
$feeds = new newsFeeds( new dba() );
echo "<xmp>";
print_r( $feeds->getNews() );
echo "</xmp>";

//add a feed
//$feeds->newsFeed( $feeds->addFeed() );
//set some properties
//$feeds->setName('testing');
//$feeds->setUrl('http://www.google.com');
//$feeds->setInterval(60000);

//echo "\n";
//print_r( $feeds->getFeeds() );
//echo "\n";

//$feeds->newsFeed( 1 );
//$feeds->fetch();
*/


/**
 * Class which maps to the newsfeed table
 * this class handle sindication of news feeds from other
 * sites
 */
class newsFeeds
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
  * Name of the relational table to be mapped
  * @type String
  */
  var $table = 'newsfeed';


  /**
  * List of feeds
  * "id"=>int,"name"=>String,"url"=>String,"active"=>char ( 'y':'n' )
  * @type mixedArray
  */
  var $feeds;

  /**
  * Feed name
  * @type String
  */
  var $name;         

  /**
  * Url of the rss or rdf file
  * @type String
  */
  var $url;          

  /**
  * Amount of minutes between requests
  * @type int
  */
  var $fetch_interval;         

  /**
  * Flag for activity ( pause or active, 'y':'n' )
  * @type boolean
  */
  var $active;           

  /**
  * Serialize mixed array with the feeds content
  * channel( title, link, description, items( item( title, link, description ) )
  * @type String
  */
  var $cache;

  /**
  * Date string for when the feed was last fetched
  * @type String
  */
  var $lastfetched;

  /**
  * Number of links to display for the current feed
  * @type int
  */
  var $displaynumber;
  
  /**
   * List of links titles and descriptions for a given feed
   * ( ITEM->( LINK, TITLE, DESCRIPTION )  )
   * @type mixedArray
   */
  var $feedArray;

  /**
   * List of all the active and availables feeds
   * @type mixedArray
   */
   var $news;

  function newsFeeds( $dba )
  {
      $this->dba = $dba;
      $this->p   = $this->dba->getPrefix();
      $this->table = $this->p . $this->table;
  }
  function newsFeed( $id )
  {
     if( !is_numeric( $id ) ) return;
     $this->id = $id;
     $this->loadProperties();
  }
  function loadProperties()
  {
    $sql = "SELECT
              name,
              url,
              fetch_interval,
              active,
              cache,
              DATE_FORMAT( lastfetched, '%d.%m.%Y %H:%I:%s' ) AS lastfetched,
              displaynumber
            FROM
              ". $this->table ."
            WHERE
              id = ". $this->id;
     $record = $this->dba->singleArray( $sql );

     $this->name           = $record["name"];
     $this->url            = $record["url"];
     $this->fetch_interval = $record["fetch_interval"];
     $this->active         = $record["active"];
     $this->cache          = $record["cache"];
     $this->lastfetched    = $record["lastfetched"];
     $this->displaynumber  = $record["displaynumber"];
  }
  function getFeeds( )
  {
    $sql = "SELECT
              id,
              name,
              url,
              active
            FROM
              ".$this->table ."
            ORDER BY 
              position";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    for( $i = 0; $i < $n; $i++ )
    {
      $this->feeds[$i] = $this->dba->fetchArray( $result );
    }

    return $this->feeds;
  }
  function getNews()
  {
    $sql = "SELECT 
              id,
              name,
              url,
              displaynumber,
              cache,
              UNIX_TIMESTAMP( lastfetched ) - ( UNIX_TIMESTAMP( NOW() ) - fetch_interval ) AS deltafetch 
            FROM 
              ". $this->table ."
            WHERE
              ( active ='y' OR active IS NULL )
            ORDER BY
              position";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    for( $i = 0; $i < $n; $i++ )
    {
      $this->news[$i] = $this->dba->fetchArray( $result );
      if( !$this->news[$i]["deltafetch"] || $this->news[$i]["deltafetch"] < 0 )
      {
        $this->newsFeed( $this->news[$i]["id"] );
        $this->news[$i]["cache"] = $this->fetch();
      }
      else
      {
        $this->news[$i]["cache"] = unserialize( stripslashes( $this->news[$i]["cache"] ) ); 
      }
    }
	  return $this->news;
	}
	
	function getUserNews($userId)
  {
    if(!is_numeric($userId)) return;
		
		$sql = "SELECT 
              nf.id,
              nf.name,
              nf.url,
              nf.displaynumber,
              nf.cache,
              UNIX_TIMESTAMP( nf.lastfetched ) - ( UNIX_TIMESTAMP( NOW() ) - nf.fetch_interval ) AS deltafetch 
            FROM 
              ".$this->p."newsfeed AS nf,
							".$this->p."end_user2newsfeed AS u2f
            WHERE
              ( active ='y' OR active IS NULL )
						AND
							nf.id = u2f.feed_id
						AND
							".$userId." = u2f.user_id
            ORDER BY
              position";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    for( $i = 0; $i < $n; $i++ )
    {
      $this->news[$i] = $this->dba->fetchArray( $result );
      if( !$this->news[$i]["deltafetch"] || $this->news[$i]["deltafetch"] < 0 )
      {
        $this->newsFeed( $this->news[$i]["id"] );
        $this->news[$i]["cache"] = $this->fetch();
      }
      else
      {
        $this->news[$i]["cache"] = unserialize( stripslashes( $this->news[$i]["cache"] ) ); 
      }
    }
    return $this->news;
  }

	
  function addFeed( )
  {
    $sql = "INSERT INTO 
           ".$this->table."
           ( name )
           VALUES( 'new feed' )";
    $this->dba->exec( $sql );
    return $this->dba->last_inserted_id();
  }
  function remove( $id )
  {
    if( !is_numeric( $id ) ) return;
    $sql = "DELETE FROM
          ".$this->table."
          WHERE
            id=$id";
    $this->dba->exec( $sql );
  }

  function activate( $id )
  {
    if( !is_numeric( $id ) ) return;
    $sql = "UPDATE
          ".$this->table."
          SET
            active ='y'
          WHERE
            id=$id";
    $this->dba->exec( $sql );
  }

  function deactivate( $id )
  {
    if( !is_numeric( $id ) ) return;
    $sql = "UPDATE
          ".$this->table."
          SET
            active ='n'
          WHERE
            id=$id";
    $this->dba->exec( $sql );
  }

  function setName( $string )
  {
    if( !trim( $string ) ) return;

    $this->name = $string;
    $sql = "UPDATE
          ".$this->table."
          SET
            name ='". addslashes( trim( $this->name ) ) ."'
          WHERE
            id=". $this->id;
    $this->dba->exec( $sql );
  }

  function setUrl( $string )
  {
    if( !trim( $string ) ) return;

    $this->url = $string;
    $sql = "UPDATE
          ".$this->table."
          SET
            url ='". addslashes( trim( $this->url ) ) ."'
          WHERE
            id=". $this->id;
    $this->dba->exec( $sql );
  }

  function setInterval( $int )
  {
    if( !is_numeric( $int ) ) return;

    $this->fetch_interval = $int;
    $sql = "UPDATE
          ".$this->table."
          SET
            fetch_interval = $int
          WHERE
            id=". $this->id;
    $this->dba->exec( $sql );
  }

  function saveCache( )
  {
    $cache = addslashes( serialize( $this->feedArray ) );
    $sql = "UPDATE
          ".$this->table."
          SET
            cache = '$cache',
            lastfetched = NOW()
          WHERE
            id=". $this->id;
    $this->dba->exec( $sql );
  }

  function setDisplayNumber( $int )
  {
    if( !is_numeric( $int ) ) return;

    $this->displaynumber = $int;
    $sql = "UPDATE
          ".$this->table."
          SET
            displaynumber = $int
          WHERE
            id=". $this->id;
    $this->dba->exec( $sql );
  }

  function fetch()
  {
	 if (!($fp = @fopen($this->url, "r"))) 
     {
        $this->error = "Could not open feed source \"$this->url\".";
        return false;
     }
     while ($data = fread($fp, 4096)) 
     {
        $content.= $data;
     }

     //check the format ( rdf:rss )
     if( preg_match ("/<rdf:RDF[^>]*>/i", $content ) )
     {
        $rdf = new rdf();
        $rdf->parse($content, $this->displaynumber );
        $this->feedArray = $rdf->channel;
     }
     else 
     {
        global $rss;
        $rss = new rss_parser();
        $rss->parse( $content, $this->displaynumber ) or die( $rss->error );
        if ($rss->error) print $rss->error;

        $this->feedArray = $rss->channel;
     }
     $this->saveCache();
     return $this->feedArray;
  }
  /**
   * Change the order of the feeds
   * Move the requested feed up 
   * @param int id -feed identifier
   * @returns void
   */
  function moveUp( $id )
  {
    if( !is_numeric( $id ) ) return;

      $sql = "SELECT
                id,
                position
              FROM
                ".$this->table."
              ORDER BY
                position";

      $result = $this->dba->exec( $sql );
      $n	= $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
          $record = $this->dba->fetchArray( $result );
          $record["position"] = ( $i + 1 );

          if( $record["id"]== $id && $previousRecord )
          {
              $sql = "UPDATE
                          ".$this->table."
                      SET
                          position=". $previousRecord["position"] ."
                      WHERE
                          id = ". $record["id"];
              $this->dba->exec( $sql );

              $sql = "UPDATE
                          ".$this->table."
                      SET
                          position=". $record["position"] ."
                      WHERE
                          id = ". $previousRecord["id"];
              $this->dba->exec( $sql );

              return;    
          }
          else
          {
              $sql = "UPDATE
                      ".$this->table."
                      SET
                          position=". ( $i + 1 ) ."
                      WHERE
                          id = ". $record["id"];
              $this->dba->exec( $sql );
          }
          $previousRecord = $record;
      }
  }

  function moveDown( $id )
  {
    	if( !is_numeric( $id ) ) return;

      $sql = "SELECT
                  id,
                  position
              FROM
                  ".$this->table."
              ORDER BY
                  position";

      $result = $this->dba->exec( $sql );
      $n	= $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
          $record = $this->dba->fetchArray( $result );
          $record["position"] = $i + 1;

          if( $nextRecord )
          {
            $sql = "UPDATE
                  ".$this->table."
              SET
                  position=". $nextRecord["position"] ."
              WHERE
                  id = ". $record["id"];
            $this->dba->exec( $sql );

            $sql = "UPDATE
                ".$this->table."
            SET
                position=". $record["position"] ."
            WHERE
                id = ". $nextRecord["id"];
            $this->dba->exec( $sql );
            unset( $nextRecord );
          }
          else
          {
            $sql = "UPDATE
                  ".$this->table."
              SET
                  position=". ( $i + 1 ) ."
              WHERE
                  id = ". $record["id"];
            $this->dba->exec( $sql );
          }

          if( $record["id"]== $id ) $nextRecord = $record;
      }
    }
}
?>