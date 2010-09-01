<?php

/**
 ** Especialize tree media
 ** @author Ronald
 **/
 
class mediaTree extends tree
{
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
													"wvx",
													"xls",
													"zip",
													);
    function removing( $id )
    {
        if( !$id ) return;

        //get all the childrens
        $sql = "SELECT
                    id,
                    format
                FROM
                    ". $this->table ."
                WHERE
                    parent = $id";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );

        for( $i = 0; $i < $n; $i++ )
        {
						$record = $this->dba->getRecord( $result );
            $this->removeFile( $record[0], $record[1] );
            $this->removing( $record[0] );
        }

        //delete all the childrens
        $sql = "DELETE FROM
                    ". $this->table ."
                WHERE
                    parent = $id";
        $this->dba->exec( $sql );
    }
    function remove( $id )
    {
        if( !$id || !is_numeric( $id ) ) return;

        //recourse and remove nodes beneath this one 
        $temp = $this->removing( $id );
				
				//Remove the file
				$sql = "SELECT
                    format
                FROM
                    ". $this->table ."
                WHERE
                    id = $id";
				$result = $this->dba->singleQuery( $sql );
				$this->removeFile( $id, $result );
				
        //now remove the node
        $sql = "DELETE FROM 
                    ". $this->table ."
                WHERE
                    id = $id";
        $this->dba->exec( $sql );
    }
    function add( $id )
    {
        if( !$id || !is_numeric( $id ) ) return;

        //Get the highest position 
        $n = $this->getHighestPositionFromChildrens( $id );
        $n++;

        //add the child
        $sql = "INSERT INTO
                    " .$this->table ."
                (
                    name,
                    parent,
                    position
                )
                VALUES
                (
                    'Untitled',
                     $id,
                     $n
                )";
        $this->dba->exec( $sql );

        //open the parent node
        $this->open( $id );
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
    function duplicating( $id, $parent, $rename = false )
    {
        if( !$id || !$parent ) return;
        $sql = "SELECT 
                    * 
                FROM
                    ". $this->table ."
                WHERE
                    id = $id";
        $record = $this->dba->singleArray( $sql );
        
        if( !$record ) return;
        
        //loop trough the field and insert the values
        foreach( $record as $key => $value)
        {
            if( !is_numeric( $key ) && $key!="id" ) 
            {
                if( $fields ) $fields.=",";
                $fields.= $key;
                
                if( $values ) $values.= ",";
                if( $key == 'parent' ) 
                {
                    $values.= $parent;
                }
                else
                {
                    if( !is_numeric( $value ) )
                    {
                        if( $rename && $key == "name" ) $value.='_copy';
                        $values.= "'$value'";
                    }
                    else
                    {
                        $values.= $value;
                    }
                }
            }
        }

        $sql = "INSERT INTO
                    ". $this->table ."
                ( 
                    $fields 
                )
                VALUES
                (
                    $values
                )";
        
        $this->dba->exec( $sql );

        $last_id = $this->dba->last_inserted_id();
        $this->duplicateFile( $record["id"], $last_id, $record["format"] );

        //loop trought the childs of the item and duplicate them as well
        $sql = "SELECT
                    id
                FROM
                    ". $this->table ."
                WHERE
                    parent = $id";
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $record = $this->dba->getRecord( $result );
            $this->duplicating( $record[0], $last_id );
        }
    }
    function duplicateFile( $id, $newId, $format )
    {
        $path = "../../media/";
        $originalFileName = $path.$id.".".$format;
        $newFile          = $path.$newId.".".$format;
        @copy( $originalFileName, $newFile );
				@chmod($newFile, 0755);
    }
    function getNodes( $id = 0 )
    {
        //get a list of the nodes who have childrens
        $nodeList = $this->getNodesWithChildrens( $id );
        
        //select all the children of the current node
        $sql = "SELECT
                    id        AS 'id',
                    name      AS 'name',
                    format    AS 'format',
										width			AS 'width',
										height		AS 'height',
										meta			AS 'meta'
                FROM
                    ". $this->table ."
                WHERE
                    parent = $id
                ORDER BY
                    position";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $this->node  = $this->dba->fetchArray( $result );

            $this->node["name"] = stripslashes ( $this->node["name"] );
						//add the node field to the node
            $this->node["node"] = ( $nodeList[ $this->node["id"] ] )? TRUE : FALSE;
            
            //add the open field to the node 
            if( $this->fullOpen )
            {
              $this->node["open"] = TRUE;
            }
            else
            {
              $this->node["open"] = ( $this->state[ $this->node["id"] ]  )? TRUE : FALSE;
            }

            //add the indentation field to the node
            $this->node["level"] = $this->level;

            //set the moving flag
            if( $this->node["id"] == $this->movingNode ) 
            {
               $this->node["moving"] = TRUE;
               $this->isMoving =  TRUE; 
               $tempNode = $this->node["id"];
            }
            else
            {
               $this->node["moving"] = ( $this->isMoving )? TRUE : FALSE;
            }

            //add the node to the nodeArray
            $this->nodeArray[ count( $this->nodeArray ) ] = $this->node;

            if( $this->node["open"] )
            {
                 $this->level++;
                 $this->getNodes( $this->node["id"] );
                 $this->level--;
            }

            if( $tempNode == $this->movingNode ) $this->isMoving =  FALSE; 
        }
    }
}
