<?php
require_once("admin/util/dba.php");
$dba = new dba();

if( !$id ) $id = $_GET["id"];
if( !$sessid ) $sessid = $_GET["sessid"];
if( $id && $sessid )
{
  if( !$js )   $js = $_GET["js"];
  if( !$type ) $type = $_GET["type"];
  if( !$size ) $size = $_GET["size"];
  if( !$colors ) $colors = $_GET["colors"];
  if( !$referer ) $referer = $_GET["referer"];
  if( !$java ) $java = $_GET["java"];
  if( $java != 'n' ) $java = ( $java == 'true' )?"y":"n";
  if( !$type ) $type = 'd';
  if( !$HTTP_HOST ) $HTTP_HOST = $_SERVER["HTTP_HOST"];
  if( !$HTTP_REFERER ) $HTTP_REFERER = $_SERVER["HTTP_REFERER"];
  if( !$HTTP_USER_AGENT ) $HTTP_USER_AGENT = $_SERVER["HTTP_USER_AGENT"];

  $sql = "INSERT INTO
          ". $dba->getPrefix() ."stats
          (
            sessid,
            ip,
            referer,
            id,
            assettype,
            useragent,
            js,
            java,
            screen,
            colors
          )
          VALUES
          (
            '$sessid',
            '$HTTP_HOST',
            '$referer',
             $id,
            '$type',
            '$HTTP_USER_AGENT',
            '$js',
            '$java',
            '$size',
            '$colors'
          )";
    $dba->exec( $sql );

    $sql = "DELETE FROM
          ". $dba->getPrefix() ."stats
            WHERE TO_DAYS(NOW()) - TO_DAYS(timestamp) >= 30";
    $dba->exec( $sql );

    //get current counter for unique_visits and visits
    $sql = "SELECT
              unique_visits,
              visits
            FROM
              ". $dba->getPrefix() ."permanent_stats
            WHERE
              id=$id";

    $record = $dba->singleQuery( $sql );

    $unique_visits = $record[0];
    $visits = $record[1];
    $visits++;


    //check if sessid is in db
    $sql = "SELECT
              id
            FROM
              ". $dba->getPrefix() ."stats
            WHERE
              sessid = '$sessid'";

    //if not put this into permanent
    if( !$dba->singleQuery( $sql ) ) $unique_visits++;

    //new entry
    if( $visits == 1 )
    {
        $sql = "INSERT INTO
                  ". $dba->getPrefix() ."permanent_stats
                (
                  id,
                  unique_visits,
                  visits,
                  last_visit
                )
                VALUES
                (
                  $id,
                  1,
                  1,
                  NOW()
                )";
    }
    else
    {
      $sql = "UPDATE
                  ". $dba->getPrefix() ."permanent_stats
              SET
                unique_visits = $unique_visits,
                visits = $visits,
                last_visit = NOW()
              WHERE
                id=$id";
    }
    $dba->exec( $sql );
}


$filename="graphics/transp.gif";
$fp=fopen($filename, "rb");
header( "Content-type: image/gif" );
fpassthru($fp);
?>
