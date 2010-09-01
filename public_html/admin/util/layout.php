<?php
/*
require_once("dba.php");
$dba = new dba();
$layout = new layout( $dba );

$layout->buildLayout(); 

$layout->loadTemplate("test.TMP3");

echo "<xmp>";
echo $layout->getTemplateString( "test.TMP3" );
echo "</xmp>";

echo "<br>";
echo $layout->getTemplateCellNumber( "test.TMP3" );
*/

class layout
{
    var $dba;
    var $p;
    var $properties;
    var $templates;
    var	$layout;
    var $id;
    var $publish;
    var $excusesFromRoles; 
    var $mediaPath = '../../';
    var $formPathPrefix = '..';
    var $layoutPath = '../../layouts/';
    var $dev;
    var $frontdev;

    function layout( $dba, $id = 1, $dev = false, $frontdev = false )
    {
    	if( !is_numeric( $id ) ) $id = 1;
        $this->dba   = $dba;
        $this->p     = $this->dba->getPrefix();
        $this->id    = $id;
	      $this->dev   = $dev;
        $this->frontdev = $frontdev;
    }

    function buildLayout( $selectedLayout = '' )
    {
    	  global $document;
        $cellCount = 0;
	
        if( !$this->properties ) $this->getProperties();
	      if( !$selectedLayout ) $selectedLayout = $this->properties["layout"];

	      $this->loadTemplate( $selectedLayout );
        $template   = $this->getTemplateString( $selectedLayout );
        $cellNumber = $this->getTemplateCellNumber( $selectedLayout );


        if( $this->properties["heading"] || trim( $this->properties["content"] ) )
        {
            $cellCount++;
            $placeHolder = "<TMP cell=\"$cellCount\"></TMP>";
            $inc ='';

            if( $this->frontdev  )
            {
              $inc.= '<a href="admin/documents/index.php?pane=edit&id='. $this->id;
              $inc.= '" onclick="if(opener) opener.focus()" target="contentfrm" ';
              $inc.= 'title="Edit '. $this->properties["name"] .'">';
              $inc.= '<img align="top" src="graphics/edit.gif" border="0" alt="Edit '. $this->properties["name"] .'"></a>';
            }
            
            //if there is a heading, spit it out
            if( $this->properties["heading"]  ) $inc.= '<h1>'. $this->properties["heading"] .'</h1>'."\n";
            if( $this->dev ) $inc.= $document->getTranslatedContent();
            else $inc.= $this->properties["content"];
            
            $inc.= $placeHolder;

            $template = str_replace("<TMP cell=\"$cellCount\"></TMP>", $inc, $template );
        }

        $this->properties["includes"] = $this->getIncludes( $this->id, array() );

        $incN = count( $this->properties["includes"] );
        if( $incN  )
        {
            foreach ( $this->properties["includes"] as $incs ) 
            {
                if( $cellCount < $cellNumber ) $cellCount++;
                $placeHolder = "<TMP cell=\"$cellCount\"></TMP>";
		            $inc = '<a name="doc_'. $incs["incid"] .'"></a>';
		
                if( $this->dev && $incs["type"] == "d"  )
                {
                  $inc.= '<a href="index.php?pane=edit&id='. $incs["incid"] .'&referer=';
                  $inc.= urlencode( $_SERVER["PHP_SELF"] .'?id='. $this->id .'&pane=layout' );
                  $inc.= '" title="Edit '. $incs["name"] .'">';
                  $inc.= '<img src="../graphics/layout/edit.gif" border="0" alt="Edit '. $incs["name"] .'"></a>';
                  
                  $inc.= '<a href="index.php?pane=includes&id='. $incs["incid"] .'&referer=';
                  $inc.= urlencode( $_SERVER["PHP_SELF"] .'?id='. $this->id .'&pane=layout' );
                  $inc.= '" title="Includes for '. $incs["name"] .'">';
                  $inc.= '<img src="../graphics/layout/includes.gif" border="0" alt="Includes for '. $incs["name"] .'"></a>';

                  $inc.= '<a href="index.php?pane=layout&id='. $incs["incid"] .'&referer=';
                  $inc.= urlencode( $_SERVER["PHP_SELF"] .'?id='. $this->id .'&pane=layout' );
                  $inc.= '" title="Layout for '. $incs["name"] .'">';
                  $inc.= '<img src="../graphics/layout/layout.gif" border="0" alt="Layout for '. $incs["name"] .'"></a>';
                }
                if( $this->frontdev && $incs["type"] == "d" )
                {
                  $inc.= '<a href="admin/documents/index.php?pane=edit&id='. $incs["incid"] .'" onclick="if(opener) opener.focus()" target="contentfrm" ';
                  $inc.= 'title="Edit '. $incs["name"] .'">';
                  $inc.= '<img align="top" src="graphics/edit.gif" border="0" alt="Edit '. $incs["name"] .'"></a>';
                }

		            if( $this->dev ) $inc.= $document->translateLinksBack( $incs["content"] );
                else $inc.= $incs["content"];

                $inc.= $placeHolder;
                $template = str_replace("<TMP cell=\"$cellCount\"></TMP>", $inc, $template );
            }

            if( $incN < $cellNumber )
            {
                for( $j = 0; $j < ( $cellNumber - $incN ); $j++ )
                {
                    $cellCount++;
                    $template = str_replace("<TMP cell=\"$cellCount\"></TMP>", '', $template );
                }
            }
        }

        if( !$incN && !trim($this->properties["heading"] ) && !trim( strip_tags( $this->properties["content"] ) ) ) return; 

        $template = preg_replace("/<TMP cell=\"\d+\"><\/TMP>/","", $template );
        return $template;
    }

    function getIncludes( $id, $path )
    {
        $path[ count( $path ) ] = $id;

        //get all the includes for the document
        $sql = "SELECT 
		            inc.id	     AS inckey,
                inc.doc	     AS id,
                inc.internal AS incid,
								inc.external AS incurl,
                inc.type     AS type,
                doc.content  AS content,
		            doc.name     AS name,
                doc.heading  AS heading,
                doc.layout   AS template
            FROM 
                ".$this->p."includes AS inc 
            LEFT JOIN 
                ".$this->p."tree AS doc 
            ON 
                inc.internal = doc.id 
            WHERE 
                inc.doc= $id 
            ORDER BY
                inc.position";

        $result = $this->dba->exec( $sql );
        $n	= $this->dba->getN( $result );

        for( $i = 0; $i < $n; $i++ )
        {
            $record = $this->dba->fetchArray( $result );

            if( $record["type"] == "m" ) 
            {
              $inc = '<a name="media_'. $record["incid"] .'"></a>';

              if( $this->dev )
              {

                $inc.= '<br><a href="../media/index.php?pane=settings&id='. $record["incid"] .'&referer=';
                $inc.= urlencode( $_SERVER["PHP_SELF"] .'?id='. $this->id .'&pane=layout' );
                $inc.= '" title="Properties for media">';
                $inc.= '<img src="../graphics/layout/edit.gif" border="0" alt="Properties for media"></a><br>';
              }
              if( $this->frontdev )
              {
                $inc.= '<br><a href="admin/media/index.php?pane=settings&id='. $record["incid"] .'" ';
                $inc.= ' target="contentfrm" onclick="if( opener ) opener.focus()" title="Properties for media'. $record["name"] .'">';
                $inc.= '<img align="top" src="graphics/edit.gif" border="0" alt="Properties for media '. $incs["name"] .'"></a>';
              }
		          $inc.= $this->loadMedia( $record["incid"] );
	    	      $record["content"] = $inc;
	          }
            if( $record['type'] == 'f' )
            {
              $inc = '<a name="form_'. $record["incid"] .'"></a>';
              if( $this->dev )
              {
                $inc.= '<br><a href="../forms/form.php?id='. $record["incid"] .'&referer=';
                $inc.= urlencode( $_SERVER["PHP_SELF"] .'?id='. $this->id .'&pane=layout' );
                $inc.= '" title="Form properties">';
                $inc.= '<img src="../graphics/layout/edit.gif" border="0" alt="Form properties"></a><br>';
              }
              if( $this->frontdev )
              {
                $inc.= '<br><a href="admin/forms/form.php?id='. $record["incid"] .'" ';
                $inc.= ' target="contentfrm" onclick="if( opener ) opener.focus()" title="Properties for form '. $record["name"] .'">';
                $inc.= '<img align="top" src="graphics/edit.gif" border="0" alt="Properties for form '. $incs["name"] .'"></a>';
              }
		          $inc.= $this->loadForm( $record["incid"] );
	    	      $record["content"] = $inc;
            }
						if( $record['type'] == 'e' )
            {
              $inc = '<a name="external_'. $record["incid"] .'"></a>';
							
							$height = "100%";
		          
							if( $this->dev )
              {

                $inc.= '<br><a href="'.$record["incurl"];
                $inc.= '" title="View external URL" target="_blank">';
                $inc.= '<img src="../graphics/layout/external.gif" border="0" alt="View external URL"></a><br>';
								$height = "400";
              }
              if( $this->frontdev )
              {
                $inc.= '<br><a href="'.$record["incurl"];
                $inc.= '" title="View external URL" target="_blank">';
                $inc.= '<img align="top" src="graphics/edit.gif" border="0" alt="View external URL '. $record["incurl"] .'"></a>';
              }
							
							$inc.= '<iframe height="'.$height.'" width="100%" frameborder="0" scrolling="auto" src="'.$record["incurl"].'"></iframe>';
							/*
							if ( ($fp = @fopen($record["incurl"], "r")) ) 
						 	{
								while ($data = fread($fp, 4096))
								{
									$inc.= $data;
								}
						 	}
							else
							{
								$inc.= "Could not open external url: ".$record["incurl"].".";
							}*/
	    	      $record["content"] = $inc;
            }
            if( $record["type"] == "d" && !in_array( $record["incid"], $path ) )
            {
                $cellCount = 0;
                $record["path"] = $path;

                $this->loadTemplate( $record["template"] );
                $template   = $this->getTemplateString( $record["template"] );
                $cellNumber = $this->getTemplateCellNumber( $record["template"] );

                $record["includes"] = $this->getIncludes( $record["incid"], $path );
                $incN = count( $record["includes"] );

                if( trim( $record["content"] ) || trim( $record["heading"] ) )
                {
                    $cellCount++;

                    $placeHolder = "<TMP cell=\"$cellCount\"></TMP>";
                    $inc = '';
                    if( trim( $record["heading"] ) ) $inc = '<h2>'. $record["heading"] .'</h2>';
                    $inc.= $record["content"];

                    if( $incN ) $inc.= $placeHolder;
                    $template = str_replace("<TMP cell=\"$cellCount\"></TMP>", $inc, $template );
                }
                
                if( $incN  )
                {
                    foreach ( $record["includes"] as $incs ) 
                    {
                        if( $cellCount < $cellNumber ) $cellCount++;
                        $placeHolder = "<TMP cell=\"$cellCount\"></TMP>";

                        $inc = '<a name="doc_'. $incs["incid"] .'"></a>';
                        if( $this->dev && $incs["type"] == "d" )
                        {
                          $inc.= '<br><a href="index.php?pane=edit&id='. $incs["incid"] .'&referer=';
                          $inc.= urlencode( $_SERVER["PHP_SELF"] .'?id='. $this->id .'&pane=layout' );
                          $inc.= '" title="Edit '. $incs["name"] .'">';
                          $inc.= '<img src="../graphics/layout/edit.gif" border="0" alt="Edit '. $incs["name"] .'"></a>';
                          
                          $inc.= '<a href="index.php?pane=includes&id='. $incs["incid"] .'&referer=';
                          $inc.= urlencode( $_SERVER["PHP_SELF"] .'?id='. $this->id .'&pane=layout' );
                          $inc.= '" title="Includes for '. $incs["name"] .'">';
                          $inc.= '<img src="../graphics/layout/includes.gif" border="0" alt="Includes for '. $incs["name"] .'"></a>';

                          $inc.= '<a href="index.php?pane=layout&id='. $incs["incid"] .'&referer=';
                          $inc.= urlencode( $_SERVER["PHP_SELF"] .'?id='. $this->id .'&pane=layout' );
                          $inc.= '" title="Layout for '. $incs["name"] .'">';
                          $inc.= '<img src="../graphics/layout/layout.gif" border="0" alt="Layout for '. $incs["name"] .'"></a>';
                        }
                        if( $this->frontdev && $incs["type"] == "d" )
                        {
                          $inc.= '<br><a href="admin/documents/index.php?pane=edit&id='. $incs["incid"] .'" target="contentfrm" ';
                          $inc.= 'title="Edit '. $incs["name"] .'" onclick="if( opener ) opener.focus()">';
                          $inc.= '<img align="top" src="graphics/edit.gif" border="0" alt="Edit '. $incs["name"] .'"></a>';
                        }
                        $inc.= $incs["content"];

                        if( $tempCount != $incN ) $inc.= $placeHolder;
                        $template = str_replace("<TMP cell=\"$cellCount\"></TMP>", $inc, $template );
                    }
                }
                //remove leftover placeholders from templates
                $template = preg_replace("/<TMP cell=\"\d+\"><\/TMP>/","", $template );

                //replace the content with the loaded template 
                $record["content"] = $template;
            }
            $includes[$i] = $record;
        }
        return $includes;
    }

    function loadTemplate( $templateName = 'single.TMP1' )
    {
        //parse the number of cells from the temp extension
        //read the template from disk
        //add data to the templates array
        if( $templates[ $templateName ] ) return;
        
        $fileName = $this->layoutPath . $templateName;
        if( !file_exists( $fileName ) || !is_file( $fileName ) ) return;

        $template["name"] = $templateName;
       
        $fp = fopen( $fileName,"r" );
        $template["content"] = fread( $fp, filesize( $fileName ) );
        fclose( $fp );

        //get the file extension ( the number of cells )
        $f = explode(".",$templateName );
        $ext = $f[ count( $f ) - 1 ];
        $template["cellcount"] = str_replace( "TMP", "", $ext );
        
        
        $this->templates[ $templateName ] = $template;
    }
    function getTemplateString( $templateName )
    {
        $template = $this->templates[ $templateName ];
        if( !is_array( $template ) ) 
	{
		//try to load the default template
		$this->loadTemplate();

		//check if it works
		if( !trim( $this->templates["single.TMP1"]["content"] ) )
		{
			return '<div style="border:1px solid #cdcdcd"><TMP cell="1"></TMP></div>';
		}
		else
		{
			return $this->templates["single.TMP1"]["content"];
		}
	}

        return $template["content"];
    }
    function getTemplateCellNumber( $templateName )
    {
        $template = $this->templates[ $templateName ];
        if( !is_array( $template ) )
	{
		//try to load the default template
		$this->loadTemplate();

		//check if it works
		if( !is_numeric( $this->templates["single.TMP1"]["cellcount"] ) )
		{
			return 1;
		}
		else
		{
			return $this->templates["single.TMP1"]["cellcount"];
		}
	}

        return $template["cellcount"];
    }
    function getProperties()
    {
        $sql = "SELECT
               name,
               title,
               heading,
               meta,
               description,
               template,
               content,
               parent,
	             layout
            FROM
                ". $this->p. "tree
            WHERE
                id = ". $this->id ."
            AND 
                ( timepublish < NOW() OR timepublish IS NULL )
            AND
                ( timeunpublish > NOW() OR timeunpublish IS NULL )";

        $result = $this->dba->exec( $sql );
        if( $this->dba->getN( $result ) )
        {
                $this->properties = $this->dba->fetchArray( $result );
                $this->publish = true;
        }
        else $this->publish = false;
    }
    function loadForm( $id )
    {
      $forms = new forms( $this->dba );
      $forms->form( $id );
      $fields = new fields( $this->dba, $id );
      $forms->fields = &$fields;
      if( $this->dev ) $forms->dev = true;
			return $forms->render( $fields->getFields() );
    }
    function loadMedia( $id )
    {
        if( !is_numeric( $id ) ) return;
        $sql = "SELECT
                    t1.id,
                    t1.name,
                    t1.description,
                    t1.meta   AS alt,
                    t1.format,
                    t1.size,
                    t1.height,
                    t1.width,
                    t1.hightres,
                    t2.format  AS hrformat
                FROM
                    ".$this->p."mediatree AS t1
                LEFT JOIN
                    ".$this->p."mediatree AS t2
                ON
                    t1.hightres = t2.id
                WHERE
                    t1.id= $id";
        $media    = $this->dba->singleArray( $sql );
        $fileName = $this->mediaPath ."media/". $media["id"] .".". $media["format"]; 

        if( !file_exists( $fileName ) ) return;
        $f = $media["format"];

        if( $f == 'gif' || $f == 'jpg' || $f == 'png' )
        {
            $str='<br>';
            if( $media["hightres"] )
            {
              $str.='<a href="'. $this->mediaPath ."media/". $media["hightres"] .".". $media["hrformat"];
              $str.='" target="_blank">';
            }
            $str.= '<img border="0" src="'. $fileName .'" ';
            $str.= ( $media["width"] )?' width="'. $media["width"] .'" ':'';
            $str.= ( $media["height"] )?' height="'. $media["height"] .'" ':'';
            $str.= ( $media["alt"] )?' alt="'. $media["alt"] .'" ':'';
            $str.= '/>';

            if( $media["hightres"] ) $str.= '</a>';
						return $str;
        }
        elseif( $f == 'swf' )
        {
            $str = '<br><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ';
            $str.= 'codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" ';
            $str.= ( $media["width"] )?' width="'. $media["width"] .'" ':'';
            $str.= ( $media["height"] )?' height="'. $media["height"] .'" ':'';
            $str.= '>';
            $str.= '<param name="movie" value="'. $fileName .'">';
            $str.= '<param name="quality" value="high">';
            $str.= '<embed src="'. $fileName .'" quality="high" ';
            $str.= 'pluginspage="http://www.macromedia.com/go/getflashplayer" ';
            $str.= 'type="application/x-shockwave-flash" ';
            $str.= ( $media["width"] )?' width="'. $media["width"] .'" ':'';
            $str.= ( $media["height"] )?' height="'. $media["height"] .'" ':'';
            $str.= '></embed></object>';
            return $str;
        }
        elseif ( $f == 'html' || $f == 'htm' )
				{
						$handle = fopen($fileName, "r");
						$str.= fread($handle, filesize($fileName));
						fclose($handle);
						return $str;
				}
				else
        {
            $str= '<br><a href="'. $fileName .'" target="_blank">';
            $str.= 'Open <b>'. $media["name"] .'</b> in new window</a>';
            return $str;
        }
    }
    function getLayouts()
    {
        if( !is_dir( $this->layoutPath ) ) return;
        $handle = opendir( $this->layoutPath );
        if( !$handle ) return;
        while( $file = readdir( $handle ) )
        {
            if( stristr( $file, ".TMP" ) )
            {
                $f = explode(".", $file );
                $this->layouts[ count( $this->layouts ) ] = $file;
            }
        }

        closedir( $handle );
        return $this->layouts;
    }
}
?>