<?php
/**
 * Class which maps the media table to an object
 * @author Ronald
 */
class media
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
    var $table;

    /**
     * Unique media id and the table primary key 
     * @type int
     */
    var $id;
    
    /**
     * @type String
     */
    var $description;
    
    /**
     * File extenstion ( tree letter )
     * @type String
     */
    var $extension;

    /**
     * Media name
     * @type String
     */
    var $name;

    /**
     * File format 
     * @type String
     */
    var $format;

    /**
     * Image width 
     * @type int 
     */
    var $width;

    /**
     * Image height 
     * @type int 
     */
    var $height;
		
		/**
     * Download count for media file 
     * @type int 
     */
    var $downloadcount;
		
		/**
     * Date for last download of file 
     * @type datetime 
     */
    var $lastdownload;

    /**
     * File name on disk
     * @type String
     */
    var $diskName;

    /**
     * Name of related file resolution image
     * @type String
     */
    var $hightresName;

    /**
     * Id of related file resolution image
     * @type int 
     */
    var $hightres;
    
    var $hightresFormat;
    /**
     * Path on disk to media repository
     * @type String
     */
    var $path;

    /**
     * Absolut path on disk to media repository
     * @type String
     */
    var $fullPath;
    var $linksFromDocs;
    var $lowResDependencies;
    var $includedBy;
    
    var $formats = array(
													"avi",
													"doc",
													"exe",
													"gif",
													"htm",
													"html",
													"jpg",
													"mdb",
													"mov",
													"mp3",
													"pdf",
													"png",
													"ppt",
													"swf",
													"rar",
													"wma",
													"wmv",
													"wvx",
													"xls",
													"zip",
													);
    var $mediaFormats = array(
															"avi",
															"mp3",
															"wma",
															"wmv",
															"wav",
															"mpg",
															"mpeg",
															"mov");
		var $graphicFormats = array(
																"gif",
																"jpg",
																"png",
																"jpeg",
																"tiff",
																"tif",
																"bmp",
																"swf"
																);
		var $graphicEditFormats = array(
																"gif",
																"jpg",
																"png",
																"jpeg"
																);
		var $graphicshigh = array("gif","jpg","png","jpeg");

		function isGraphic()
		{
			return in_array($this->format,$this->graphicFormats);
		}
		function isEditableGraphic()
		{
			return in_array($this->format,$this->graphicEditFormats);
		}
		function isMedia()
		{
			return in_array($this->format,$this->mediaFormats);
		}
		
    function media( $dba, $id = 0 )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
        $this->table = $this->p."mediatree";
        $this->path = "../../media/"; 

        if( $id ) $this->id  = $id;
        else $this->createMedia();
    }
    function createMedia()
    {
        global $user;
        $uid = $user->id;
        if( !$uid ) $uid = 1;

        $sql ="INSERT INTO
                    ".$this->table."
                (
                    name,
                    creator,
                    created,
                    parent
                )
                VALUES
                (
                    'Untitled',
                    $uid,
                    NOW(),
                    1
                )";
        $this->dba->exec( $sql );
        $this->id = $this->dba->last_inserted_id();
    }
    function loadProperties()
    {
        $sql = "SELECT
                   t1.name,
                   t1.description,
                   t1.meta,
                   t1.format,
                   t1.size,
                   t1.height,
                   t1.width,
                   t1.hightres,
									 t1.downloadcount,
									 DATE_FORMAT(t1.lastdownload,'%d-%m-%Y at %H:%i:%s') as lastdownload,
                   t2.name       AS hightresName,
                   t2.format     AS hightresFormat
                FROM
                    ".$this->table." AS t1
                LEFT JOIN
                    ".$this->table." AS t2
                ON
                    t1.hightres = t2.id
                WHERE 
                    t1.id= ".$this->id;

        $record = $this->dba->singleArray( $sql );

        $this->name        = stripslashes ( $record["name"] );
        $this->description = stripslashes ( $record["description"] );
        $this->format      = stripslashes ( $record["format"] );
        $this->size        = $record["size"];
        $this->height      = $record["height"];
        $this->width       = $record["width"];
        $this->alt         = stripslashes ( $record["meta"] );
        $this->hightres    = $record["hightres"];
        $this->hightresName= stripslashes ( $record["hightresName"] );
        $this->hightresFormat= stripslashes ( $record["hightresFormat"] );
				$this->downloadcount = $record["downloadcount"];
				$this->lastdownload = $record["lastdownload"];
    }
    function setHightRes( $hightres )
    {
      if( !$hightres || !is_numeric( $hightres ) ) $hightres = 'NULL';
      $sql = "UPDATE
                ". $this->table ."
              SET
                hightres = $hightres
              WHERE
                id = ". $this->id;
      $this->dba->exec( $sql );
    }
		function removeHightRes( $mediaid )
    {
      if( !$mediaid || !is_numeric( $mediaid ) ) return false;
      $sql = "UPDATE
                ". $this->table ."
              SET
                hightres = NULL
              WHERE
                id = ". $mediaid;
      $this->dba->exec( $sql );
    }
    function uploadMedia( $file, $file_name )
    {
				if( !trim( $file_name ) ) return "Problem trying to upload the media asset [ file name missing ]";
        if( !trim( $file ) ) return "Problem trying to upload the media asset [ file missing ]";

        $fileNameExploded = explode(".", $file_name );

        $this->extension  = ( count( $fileNameExploded ) == 1 )? " ": $fileNameExploded[ count( $fileNameExploded ) - 1 ];
        
        if( $this->name == "Untitled" || !trim($this->name ) ) $this->name = $fileNameExploded[ 0 ];

        //build the name on disk
        $this->diskName = $this->id .".". $this->extension;
				
        //build the absolute path for the file
        $this->fullPath = $this->path . $this->diskName;
				$this->fullPath = strtolower($this->fullPath);

				if ( !@move_uploaded_file( $file, $this->fullPath ) ) die( "Problem trying to upload the media asset [ copy file ]" );
				chmod($this->fullPath, 0755);

        $this->size = filesize( $this->fullPath );
            
        if( $img = @GetImageSize( $this->fullPath ) )
        {
                $this->width  = $img[0];
                $this->height = $img[1];

                switch ($img[2])
                {
                    case 1: $this->format = "gif";
                    break;
                    case 2: $this->format = "jpg"; 
                    break;
                    case 3: $this->format = "png";
                    break;
                    case 4: $this->format = "swf";
                    break;
                }
        }
        if( !$this->format ) $this->format = ( $this->extension )? $this->extension:"";
        $this->height = ( $this->height )? $this->height:0;
        $this->width = ( $this->width )? $this->width:0;

        $sql = "UPDATE
                    ".$this->table."
                SET
                    name = '". addslashes( $this->name ) ."',
                    format = '". addslashes( strtolower( trim( $this->format ) ) ) ."',
                    size   = '". $this->size ."',
                    height = '". $this->height ."',
                    width = '". $this->width ."'
                WHERE
                    id= ". $this->id;
        $this->dba->exec( $sql );
    }
    function setName( $name )
    {
        if( !trim( $name ) ) return;
        $this->name = stripslashes( $name );
        $sql = "UPDATE
                    ".$this->table."
                SET
                    name = '". addslashes( trim( $name ) ) ."'
                WHERE
                    id=".$this->id;
        $this->dba->exec( $sql );
    }
    function setDescription( $description )
    {
        if( !trim( $description ) ) return;
        $this->description= stripslashes( $description );
        $sql = "UPDATE
                    ".$this->table."
                SET
                    description = '". addslashes( trim( $description ) ) ."'
                WHERE
                    id=".$this->id;
        $this->dba->exec( $sql );
    }
    function setAlt( $meta )
    {
        if( !trim( $meta ) ) return;
        $this->alt = stripslashes( $meta );
        $sql = "UPDATE
                    ".$this->table."
                SET
                    meta = '". addslashes( trim( $meta ) ) ."'
                WHERE
                    id=".$this->id;
        $this->dba->exec( $sql );
    }
		function setHeight( $height )
    {
        if( !is_numeric( $height ) ) return;
        $this->height = $height;
        $sql = "UPDATE
                    ".$this->table."
                SET
                    height = '". $height ."'
                WHERE
                    id=".$this->id;
        $this->dba->exec( $sql );
    }
		function setWidth( $width )
    {
        if( !is_numeric( $width ) ) return;
        $this->width = $width;
        $sql = "UPDATE
                    ".$this->table."
                SET
                    width = '". $width ."'
                WHERE
                    id=".$this->id;
        $this->dba->exec( $sql );
    }
		function setSize( $size )
    {
        //echo "Hello world: ".$size;
				if( !is_numeric( $size ) ) return;
        //$this->size = $size;
        $sql = "UPDATE
                    ".$this->table."
                SET
                    size = '". $size ."'
                WHERE
                    id=".$this->id;
        $this->dba->exec( $sql );
    }
		function updateDownloadCount( $oldcount )
    {
        if (!$oldcount) $oldcount = 0;
				if( !is_numeric( $oldcount ) ) return;
				$newvalue = $oldcount+1;
        $sql = "UPDATE
                    ".$this->table."
                SET
                    downloadcount = ". $newvalue .",
										lastdownload = NOW()
                WHERE
                    id=".$this->id;
        $this->dba->exec( $sql );
    }
    function getDependencies()
    {
        $this->getLinksFromDocs();
        $this->getLowResDependencies();
    }
    /**
     * Get all the lowres images this image is hightres image for
     * @returns void
     */
    function getLowResDependencies()
    {
      $sql = "SELECT
                t1.id     AS id,
                t1.name   AS name,
                t1.format AS format
              FROM
                ".$this->table." AS t1,
                ".$this->table." AS t2
              WHERE
                t1.hightres = t2.id
              AND
                t2.id = ". $this->id;

       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
            $this->lowResDependencies[ $i ] = $this->dba->fetchArray( $result );
       }
    }

    function getLinksFromDocs()
    {
        //get all the docs who link to this doc
        $sql = "SELECT
                    doc.id   as id,
                    doc.name as name,
		    ref.reference_type as type
                FROM
                    ".$this->p."references as ref,
                    ".$this->p."tree as doc
                WHERE
                    ref.reference = ".$this->id." 
                AND
                    ( ref.reference_type='m' OR ref.reference_type='l' )
                AND
                    ref.referer = doc.id
                ORDER BY
                    ref.referer"; 

       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
            $this->linksFromDocs[ $i ] = $this->dba->fetchArray( $result );
       }
    }

    function getIncluders( )
    {
      $sql = "SELECT
                doc.id,
                doc.name
              FROM
                ".$this->p."tree as doc,
                ".$this->p."includes AS inc
              WHERE
                doc.id = inc.doc
              AND
                inc.internal =". $this->id ."
              AND
                 inc.type='m'";

      $result = $this->dba->exec( $sql );
      $n 	= $this->dba->getN( $result );

      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->fetchArray( $result );
        $this->includedBy[ $i ] = $rec;	
      }
	    return $this->includedBy;
    }
		
		function removeMedia( $id )
    {
        if( !$id || !is_numeric( $id ) ) return;

				//Remove the file
				$sql = "SELECT
                    format
                FROM
                    ". $this->table ."
                WHERE
                    id = $id";
				$result = $this->dba->singleQuery( $sql );
				$this->removeFile( $id, $result );
				
        //now remove the media format
        $sql = "UPDATE
                    ".$this->table."
                SET
                    format = NULL,
										name = 'Please Rename'
                WHERE
                    id=".$this->id;
        $this->dba->exec( $sql );
    }
		
		function removeFile( $id, $format )
    {
        if( !$id || !is_numeric( $id ) ) return;
				if( !$format ) return;
				$path     = "../../media/";
        $filename = $path.$id.".".$format;
        $temp = @unlink( trim($filename) );
				return "deleted ".$temp;
    }
}
?>
