<?PHP

class rdf
{
  var $channel;
	function parse( $rdfData, $displaynumber = 10 )
	{
    $this->channel = array(
                           "TITLE"=>'',
                           "LINK"=>'',
                           "DECRIPTION"=>'',
                           "DOCS"=>'',
                           "LANGUAGE"=>'',
                           "GENERATOR"=>'',
                           "CATEGORY"=>'',
                           "ITEM"=>array()
                          );
                        
		$rdfData = ereg_replace("<\?xml.*/channel>","",$rdfData);
		$rdfData = ereg_replace("<\image.*/image>","",$rdfData);
		$rdfData = ereg_replace("</rdf.*","",$rdfData);
		$rdfData = ereg_replace("[\r,\n]", "", $rdfData);
		$rdfData = chop($rdfData);
		
		$rdfArray = explode("</item>",$rdfData);
		
		$max = sizeof($rdfArray);
		
		if ($max > 1)
		{
			$links = array_keys($rdfArray) ;
      if( $displaynumber < $max ) $max = $displaynumber; 
      else( $max-- );

			for ($i = 0 ; $i < $max; $i++ )
			{
				$this->channel["ITEM"][count($this->channel["ITEM"] ) ] = $this->formatLink($rdfArray[$links[$i]]);
			}
		}
	}

	function formatLink ( $item ) // Removes spurious tags from the link that we don't want
	{
		if ( strstr ( $item , "<link>" ) )
		{
			$link = ereg_replace(".*<link>","",$item); // Remove the leading <link> tags
			$link = ereg_replace("</link>.*","",$link); // Remove the closing </link> tags
		}
		if ( strstr ( $item , "<title>" ) )
		{
			$title = ereg_replace(".*<title>","",$item); // Remove the leading <title> tags
			$title = ereg_replace("</title>.*","",$title); // Remove the closing </title> tags
		}
		if ( strstr ( $item , "<description>" ) )
		{
			$description = ereg_replace(".*<description>","",$item); // Remove the leading <description> tags
			$description = ereg_replace("</description>.*","",$description); // Remove the closing </description> tags
		}
		
		if ($title)
		{
      $temp = array("PUBDATE"=>'',"LINK"=>$link,"TITLE"=>$title,"DESCRIPTION"=>$description );
      return $temp;
		}
	}
}
?>
