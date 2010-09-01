<?php
  if( !$csv  ) $csv = $_GET['csv'];
  if( !$csv  ) die('Csv file name expected');
  if( !$type ) $type = $_GET['type'];
  if( !$type ) $type = 'csv';

  
  $file = 'formdata/'. $csv;
  if( file_exists( $file ) )
  {
    $fp = fopen( $file,'r');
    $content = fread( $fp, filesize( $file ) );
    fclose( $fp );

    if( $type == 'csv' )
    {
      Header("Cache-control: private");
      Header("Content-Type: application/octet-stream");
      Header("Content-Disposition: attachment; filename=$csv");
      die($content);
    }
    else
    {
      Header("Cache-control: private");
      Header('Content-type: application/vnd.ms-excel');
      //Header('Content-type: text/plain');
      Header("Expires: 0");
      Header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      $records = explode("\r\n", $content );

      $str = '<table width="100%" cellpadding="0" cellspacing="0" border="1">'."\n";
      for( $i = 0; $i < count( $records ); $i++ )
      {
        $record = explode("','", $records[$i] );
        if( $i == 0 )
        {
          $str.= "<thead>\n<tr>\n";
          for( $j = 0; $j < count( $record ); $j++ )
          {
            $entry = $record[$j];
            if( $entry{0} == '\'' ) $entry = substr( $entry,1 );
            if( $entry{ strlen( $entry ) - 1 } == '\'' ) $entry = substr( $entry,0,( strlen( $entry ) - 1 ) );
            $str.= "<th>\n";
            $str.= $entry;
            $str.= "</th>\n";
          }
          $str.= "</tr>\n</thead>\n";
        }
        else
        {
          $str.= "<tr>\n";
          for( $j = 0; $j < count( $record ); $j++ )
          {
            $entry = $record[$j];
            if( $entry{0} == '\'' ) $entry = substr( $entry,1 );
            if( $entry{ strlen( $entry ) - 1 } == '\'' ) $entry = substr( $entry,0,( strlen( $entry ) - 1 ) );
            $str.= "<td>\n";
            $str.= $entry;
            $str.= "</td>\n";
          }
          $str.= "</tr>\n";
        }
      }
      $str.= "\n</table>\n";
      echo $str;
    }
  }
  else
  {
    die( 'The file you requested doesn\'t seam to exist' );
  }
?>
