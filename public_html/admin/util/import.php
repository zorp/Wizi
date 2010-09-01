<?php
/**
 * Utility class for importing data into wizi ( images and documents )
 * @author Ronald
 */
class import
{
    /** 
     * Connection object and database abstraction layer
     * @type dba 
     */
    var $dba;

    /** 
     * Type of import to be performed
     * @type String
     */
    var $type;

    /** 
     * Prefix for tables in the current installation
     * @type String
     */
    var $p;

    /** 
     * File path to data to be imported
     * @type String
     */
    var $importPath = "../../import/";

    /** 
     * File path to media repository 
     * @type String
     */
    var $mediaLib = "../../media/";

    /** 
     * Status string
     * @type String
     */
    var $status;

    var $files;

    var $hightResImg = array();
    var $lowResImg   = array();

    /** 
     * id of the current parent
     * @type int
     */
    var $currentParent = array();
    var $parentNames   = array();

    function import( $dba, $type )
    {
      $this->dba  = $dba;
      $this->p    = $dba->getPrefix();
      $this->type = $type;

      switch( $this->type )
      {
        case( 'img' ):
          $this->importImage();
          break;
      }
    }
    function importImage()
    {
      //check if the directory is there
      $path = $this->importPath."images";
      if( ! is_dir( $path ) )
      {
        $this->status = "The specified import directory doesn't exist";
        return;
      }

      //set the current parent
      array_push( $this->currentParent, 1 );
      
      //recourse the directories and create a list structure
      $this->files = $this->retrieveDirs( $path );
      
      /*
      echo "<xmp>";
      print_r( $this->files );
      echo "</xmp>";
      $this->status = 1;
      return;
      */
      
      //loop trought the files, insert the items in the db
      //rename and move the files
      $this->insertMediaFromFiles( $this->files );

      //loop trought the highres array and link them to the low res
      if( count( $this->hightResImg ) )
      {
        foreach( $this->hightResImg as $key=>$value )
        {
          $lowName = str_replace( '_high_', '', $key );
          $lowId = $this->lowResImg[ $lowName ];
          if( $lowId )
          {
            $sql = "UPDATE 
                      ".$this->p."mediatree
                    SET
                      hightres = $value
                    WHERE
                      id = $lowId";
            $this->dba->exec( $sql );
          }
        }
      }

      $this->status = 1;
    }
    function insertMediaFromFiles( $files )
    {
      if( !count( $files ) ) return;
      foreach( $files as $key=>$value )
      {
        if( is_string( $key ) )
        {
          if( basename($key) !='images' ) 
          {
            //create the dir and push the id into the stack
            array_push( $this->currentParent, $this->createDirectory( $key ) );
            array_push( $this->parentNames, $key );
          }
        }
        if( is_array( $value ) )
        {
          $this->insertMediaFromFiles( $value );
          if( basename($key) !='images' ) 
          {
            //pop the stack
            array_pop( $this->currentParent );
            
            clearstatcache( );

            //remove the directory
            @unlink( array_pop( $this->parentNames ) );
          }

        }
        else
        {
          $this->createFile( $value );
        }

      }
    }
    function createFile( $file )
    {
      if( !is_file( $file ) ) return;
      global $user;

      $fname = pathinfo( $file );
      $name = $fname['basename']; 
      $dir  = $fname['dirname'];


      $parent = $this->currentParent[ count( $this->currentParent ) - 1 ];

      //get the format
      $p = explode('.', $name );
      $format = ( count( $p ) > 1 )? $p[1]: '';

      //get the size
      $size = filesize( $file );

      //get the width and height
      if( $img = @GetImageSize( $file ) )
      {
              $width  = $img[0];
              $height = $img[1];

              switch ($img[2])
              {
                  case 1: $format = "gif";
                  break;
                  case 2: $format = "jpg"; 
                  break;
                  case 3: $format = "png";
                  break;
                  case 4: $format = "swf";
                  break;
              }
      }
      $height = ( $height )? $height : 0;
      $width  = ( $width )? $width : 0;
			
			//if (!$parent) $parent = 1;
			
			//echo "Parent: ".$parent."<br>";
			//echo "Current Parent: ".$this->currentParent."<br>";
			//echo "File name: ".$name."<br>";
			
      $sql = "INSERT INTO 
                ". $this->p."mediatree
              (
                name,
                parent,
                format,
                size,
                width,
                height,
                creator,
                created
              )
              VALUES
              (
                '$name',
                $parent,
                '$format',
                $size,
                $width,
                $height,
                ". $user->id .",
                NOW()
              )";
      $this->dba->exec( $sql );

      //get last inserted id
      $new_id = $this->dba->last_inserted_id();
      
      //new name
      $newName = $this->mediaLib . $new_id .".". $format;

      //rename the file and move it
      //rename( $file, $newName );
			copy( $file, $newName );

      if( stristr( $name, '_high_' ) )
      {
        $this->hightResImg[ $name ] = $new_id;
      }
      else
      {
        $this->lowResImg[ $name ] = $new_id;
      }

      return $new_id;
    }
    function createDirectory( $file )
    {
      global $user;
      $name =basename($file);
      $parent = $this->currentParent[ count( $this->currentParent ) - 1 ];

      //get the directory name
      $sql = "INSERT INTO 
                ". $this->p."mediatree
              (
                name,
                parent,
                creator,
                created
              )
              VALUES
              (
                '$name',
                $parent,
                ". $user->id .",
                NOW()
              )";

      $this->dba->exec( $sql );
      //get last inserted id
      $new_id = $this->dba->last_inserted_id();
                  
      return $new_id;
    }
    function retrieveDirs($rootdirpath ) 
    {
       if ($dir = @opendir($rootdirpath)) 
       {
         $array[ $rootdirpath ] = '';
         while (($file = readdir($dir)) !== false)
         {
           $counter++;
           if( $file == '.' || $file == '..' || $file =='CVS' || $file == 'README.txt' ) continue;

           //directory -- recourse --
           if( $file != "." && $file != ".." && is_dir($rootdirpath."/".$file) )
           {
             $a = $this->retrieveDirs($rootdirpath."/".$file );
             $array[ $rootdirpath][ $counter ] = $a;
           }
           else
           {
             $array[ $counter ] = $rootdirpath."/".$file;
           }
         }
         closedir($dir);
       }
       return $array;
    }
}
?>
